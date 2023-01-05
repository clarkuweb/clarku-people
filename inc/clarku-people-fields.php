<?php
/**
 *	ClarkU People Tool fields
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');


function clarku_people_fields() {
	$fields = array(
		'cu_people_sortname' => __( 'Sort Name', 'clarku' ),
		'cu_people_title' => __( 'Title', 'clarku' ),
		'cu_people_email' => __( 'Email', 'clarku' ),
		'cu_people_phone' => __( 'Phone', 'clarku' ) 
	);
	return $fields;
}


function clarku_people_metabox() {
	add_meta_box(
		'clarku_people_meta',           // Unique ID
		__('People Fields', 'clarku'),  // Box title
		'clarku_people_metabox_html',   // Content callback, must be of type callable
		'cu_people'                     // Post type
	);
}
add_action( 'add_meta_boxes', 'clarku_people_metabox' );



function clarku_people_metabox_html( $post ) {

	$fields = clarku_people_fields();
	
	foreach( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, '_' . $key, TRUE );
		?>
		<div class="components-panel__row">
		<div class="components-base-control__field">
			<label class="components-base-control__label" for="cu-people-phone"><?php echo $label ?> </label>
			<input class="components-text-control__input" type="text" id="<?php echo esc_attr( sanitize_title_with_dashes( $key ) ); ?>" name="<?php echo esc_attr( $key ); ?>" required="" value="<?php echo esc_attr( $value ); ?>">
		</div>
		</div>
		<?php
	}
}


// @todo: validate each field more specifically?
function clarku_people_save_postdata( $post_id ) {
	$fields = clarku_people_fields();
	foreach( $fields as $key => $label ) {
		if ( array_key_exists( $key, $_POST ) ) {
			update_post_meta( $post_id, '_' . $key, sanitize_text_field( $_POST[$key] ) );
		}
	}
}
add_action( 'save_post', 'clarku_people_save_postdata' );
