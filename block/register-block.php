<?php


/**
 * 
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

	$shortcode_bits[] = 'before=\'<div class="clarku-people ' . implode(' ', $class_names ) . '">\'';
	$shortcode_bits[] = 'after="</div>"';

	$sc = '[' . implode( ' ', $shortcode_bits ) . ']';
	
	$people = do_shortcode($sc);
	return ( $people );

}

/**
 * 
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
			'columns' => ['type' => 'string']
		)
	));
}
add_action( 'init', 'clarku_people_block' );

