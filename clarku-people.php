<?php
/*
Plugin Name: ClarkU People
Plugin URI: 
Description: Create custom people post type for people
Version: 0.1
Author: ClarkU
Author URI: 
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

define( 'CLARKU_PEOPLE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CLARKU_PEOPLE_URL', plugin_dir_url( __FILE__ ) );

function clarku_people_enqueue( $force=FALSE ) {
	if( is_singular( 'cu_people' ) || TRUE === $force ) {
		wp_enqueue_style( 'clarku-people-styles', plugins_url( 'assets/people.css', __FILE__ ) );
	}
}
add_action( 'wp_enqueue_scripts', 'clarku_people_enqueue' );

function clarku_people_enqueue_editor_styles() {
	wp_enqueue_style( 'clarku-people-editor-styles', plugins_url( 'assets/people-editor.css', __FILE__ ) );
}
add_action('enqueue_block_editor_assets', 'clarku_people_enqueue_editor_styles');

/**
 * Create a shortcode for querying people.
 * The shortcode accepts arguments: group (the category slug), posts_per_page, before, after
 * e.g. [clarku-people group="faculty"]
 */
function clarku_people_shortcode($attributes, $content, $shortcode) {
    // normalize attribute keys, lowercase
    $attributes = array_change_key_case((array)$attributes, CASE_LOWER);
    
    // default attributes
    $attributes = shortcode_atts(array(
			'group' => '', // slug, slug2, slug3
			'group_operator' => 'OR',
			'id' => NULL,
			'posts_per_page' => 96,
			'thumbnail' => '',
			'link' => TRUE, // link to the people post
			'email' => TRUE, // display the person's email
			'phone' => TRUE, // display the person's phone
			'before' => '<div class="clarku-people">',
			'after' => '</div>',
    ), $attributes, $shortcode);
    
    // check the shortcode attributes for boolean falses, and convert from default if necessary
    foreach( array('link', 'email', 'phone') as $value ) {
    	$attributes[$value] = (bool)$attributes[$value]; 
    }
		if( strtolower( $attributes['thumbnail'] ) == 'false' ) {
			$attributes['thumbnail'] = FALSE;
		}
    
		ob_start();
		clarku_people_get_people( $attributes );
		$output = ob_get_clean();
		return $output;
		
}
add_shortcode( 'clarku-people', 'clarku_people_shortcode' );



/**
 * Print a list of people
 * Wrapper for WP_Query with some baked in defaults
 * @param arr $args @see https://codex.wordpress.org/Class_Reference/WP_Query
 */
function clarku_people_get_people($args) {

	clarku_people_enqueue( TRUE );

	$default_args = array(
		'post_type' => 'cu_people',
		'order' => 'DESC',
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_cu_people_sortname',
				'compare' => 'EXISTS'
			),
			array(
				'key' => '_cu_people_sortname',
				'compare' => '!=',
				'value' => ''
			),
		),

		'orderby' => array( 'meta_value' => 'ASC', 'date' => 'DESC' ),
	);

	// check for a numeric posts_per_page value
	if ( $args['posts_per_page'] && is_numeric( $args['posts_per_page'] ) ) {
		$default_args['posts_per_page'] = $args['posts_per_page'];
	}

	if ( NULL !== $args['id'] && is_numeric( $args['id'] ) ) {
		// we have an ID, get the person
		$default_args['p'] = $args['id'];
		//echo '<pre>',print_r($default_args, TRUE), '</pre>';
	} else if ( ! empty( $args['group'] ) ) {
		// we have a group or groups, get each term's id and build the tax query
		$tq = array(
			'relation' => $args['group_operator'],
		);
		$terms = explode( ',', $args['group'] );
		foreach ( $terms as $t ) {
			$term_id = NULL;
			$term = get_terms( 'peoplegroups', 'hide_empty=1&slug=' . sanitize_title( trim($t) ) );
			$term_id = $term[0]->term_id;
			$tq[] = array(
				'taxonomy' => 'peoplegroups',
				'field' => 'id',
				'terms' => $term_id
			);
		}
		$default_args['tax_query'] = $tq;
	}

	echo html_entity_decode( $args['before'] );

	// kinda hacky... due to a WPQuery limitation
	// first, query the people with a sortname
	clarku_people_loop( $default_args, $args );
	

	// second, query the people without a sortname
	$default_args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key' => '_cu_people_sortname',
				'compare' => 'NOT EXISTS'
			),
			array(
				'key' => '_cu_people_sortname',
				'compare' => '=',
				'value' => ''
			),
		);
	$default_args['orderby'] = array( 'date' => 'DESC' );
	
	clarku_people_loop( $default_args, $args );

	echo html_entity_decode ( $args['after'] );

}



/**
 * Query and loop over people
 */
function clarku_people_loop( $query_args, $short_code_args ) {

	$loop = new WP_Query( $query_args );
	$i = 0;

	while ($loop->have_posts()) {
		$i++;
		$loop->the_post();
		clarku_people_get_template( 'person-card.php', $short_code_args );
	}	
	wp_reset_postdata();
}


/**
 * Allow a page to override the archive URL
 */
function clarku_people_archive_override($query_vars) {
	// do nothing in wp-admin
	if( is_admin() ) {
		return $query_vars;
	}

	// check if the query is for the root people slug (archive)
	if( isset( $query_vars['post_type'] ) && 'cu_people' === $query_vars['post_type'] && ! isset( $query_vars['name'] ) ) {	
		//attempt to load the page matching the $pagename slug
		$page = get_page_by_path( 'people' , OBJECT );

		if ( isset( $page ) ){
			// replace the query with a page query
			$query_vars = array( 'pagename' => 'people' );
		}
	}
	
	return $query_vars;
}
add_filter('request', 'clarku_people_archive_override' );


function clarku_people_activate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'clarku_people_activate' );


/**
 * Renders the people block both on page and in editor
 */
function clarku_people_block_render_callback( $block_attributes, $content ) {

	$shortcode_bits = array('clarku-people');
	
	$class_names = array('clarku-people');
	
	if( isset( $block_attributes['align'] ) ) {
		$class_names[] = 'align' . $block_attributes['align'];
	}
	if( isset( $block_attributes['className'] ) ) {
		$class_names[] = $block_attributes['className'];
	}
	if( isset( $block_attributes['peoplegroup'] ) ) {
		$shortcode_bits[] = 'group="' . $block_attributes['peoplegroup'] . '"';
	}	
	if( isset( $block_attributes['link'] ) ) {
		$shortcode_bits[] = 'link="' . $block_attributes['link'] . '"';
	}	

	$shortcode_bits[] = 'before=\'<div class="clarku-people ' . implode(' ', $class_names ) . '">\'';
	$shortcode_bits[] = 'after="</div>"';

	$sc = '[' . implode( ' ', $shortcode_bits ) . ']';
	
	$people = do_shortcode($sc);
	return ( $people );

}

/**
 * Registers the block editor block for people
 */
function clarku_people_block() {
	// automatically load dependencies and version
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'block/editor.asset.php');

	wp_register_script(
		'clarku-people-block-editor', plugins_url( 'block/editor.js', __FILE__ ), $asset_file['dependencies'], $asset_file['version']
	);
	wp_enqueue_style(
		'clarku-people-block-editor-styles', plugins_url( 'block/editor.css', __FILE__ )
	);


	register_block_type( 'clarku/people', array(
		'api_version' => 2,
		'editor_script' => 'clarku-people-block-editor',
		'editor_style' => 'clarku-people-block-editor-styles',
		'render_callback' => 'clarku_people_block_render_callback',
		'supports' => array(
			'align' => ['wide', 'full'],
		),
		'attributes' => array( 
			'peoplegroup' => ['type' => 'string'],
			'columns' => ['type' => 'string'],
			'link' => ['type' => 'boolean']
		)
	));
}
add_action( 'init', 'clarku_people_block' );





// require the individual field definitions from a different file
require_once dirname(__FILE__) . '/inc/clarku-people-fields.php';

// require the individual field definitions from a different file
require_once dirname(__FILE__) . '/inc/clarku-people-post-type.php';

// require the templating functions
require_once dirname(__FILE__) . '/inc/clarku-people-templating.php';