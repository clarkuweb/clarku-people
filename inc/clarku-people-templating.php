<?php
/**
 * functions for finding templates.
 * http://jeroensormani.com/how-to-add-template-files-in-your-plugin/
 */

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see wcpt_locate_template()
 *
 * @param string 	$template_name			Template to load.
 * @param array 	$args					Args passed for the template file.
 * @param string 	$string $template_path	Path to templates.
 * @param string	$default_path			Default path to template files.
 */
function clarku_people_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;

	$template_file = clarku_people_locate_template( $template_name, $tempate_path, $default_path );

	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	
	include $template_file;

}



/**
 * Locate page template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/template-parts/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/clarku-people/templates/$template_name.
 *
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string	Path to the template file.
 */
function clarku_people_locate_template( $template_name, $template_path = '', $default_path = '' ) {

// 	echo '<pre>Template Name: ';
// 	var_dump( $template_name );
// 	echo '</pre>';

	// Set variable to search in template-parts folder of theme.
	if ( ! $template_path ) {
		$template_path = 'template-parts/';
	}

	// Set default plugin templates path.
	if ( ! $default_path ) {
		$default_path = CLARKU_PEOPLE_PATH . 'templates/'; // Path to the template folder
	}

	// Search template file in theme folder.
	$template = locate_template( array( $template_path . $template_name, $template_name ) );

	// Get plugins template file.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	return apply_filters( 'clarku_people_locate_template', $template, $template_name, $template_path, $default_path );

}


/**
 * Template loader.
 *
 * The template loader will check if WP is loading a template
 * for a specific Post Type and will try to load the template
 * from out 'templates' directory.
 *
 *
 * @param	string	$template	Template file that is being loaded.
 * @return	string				Template file that should be loaded.
 */
function clarku_people_template_loader( $template ) {
	
	if ( is_single() && get_post_type() === 'cu_people' ) {
	
		// if it's a people page, then override $template with the custom one
		if( is_embed() ) {
			$file = 'embed-people.php';
		} else {
			$file = 'single-people.php';
		}
		
		if ( file_exists( clarku_people_locate_template( $file ) ) ) {
			$template = clarku_people_locate_template( $file );
		}

	}

	return $template;

}
add_filter( 'template_include', 'clarku_people_template_loader', 99 );
