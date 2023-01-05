<?php
/**
 *	ClarkU People Post Type
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');



/**
 * Define the custom people post type
 */
function clarku_people_post_type() {

	register_post_type('cu_people', array(
		'label' => 'People',
		'description' => 'For faculty, staff, and others.',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'capability_type' => 'post',
		'hierarchical' => true,
		'rewrite' => array('slug' => 'people'),
		'query_var' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'supports' => array('title','thumbnail','editor','revisions','author','custom-fields'), // perhaps 'editor', 'excerpt'
		'labels' => array (
			'name' => 'People',
			'singular_name' => 'Person',
			'menu_name' => 'People',
			'add_new' => 'Add Person',
			'add_new_item' => 'Add New Person',
			'edit' => 'Edit',
			'edit_item' => 'Edit Person',
			'new_item' => 'New Person',
			'view' => 'View Person',
			'view_item' => 'View Person',
			'search_items' => 'Search People',
			'not_found' => 'No People Found',
			'not_found_in_trash' => 'No People Found in Trash',
			'parent' => 'Parent Person',
		),
		'menu_icon'   => 'dashicons-id-alt',
	));

	register_taxonomy('peoplegroups', array (
		0 => 'cu_people'
		), array(
			'hierarchical' => true,
			'labels' => array (
				'name' => 'Groups',
				'singular_name' => 'Group',
				'search_items' => 'Search groups',
				'popular_items' => 'Popular groups',
				'all_items' => 'All groups',
				'parent_item' => 'Parent group',
				'parent_item_colon' => 'Parent group:',
// 				'name_field_description' => '',
// 				'slug_field_description' => '',
				'parent_field_description' => 'Assign a parent group',
				'edit_item' => 'Edit group',
				'view_item' => 'View group',
				'update_item' => 'Update group',
				'add_new_item' => 'Add new group',
				'new_item_name' => 'New group',
				'not_found' => 'No groups found',
				'no_terms' => 'No groups',
				'filter_by_item' => 'Filter by group',
				'items_list_navigation' => 'Groups',
// 				'most_used' => 'Most used',
// 				'back_to_items' => 'Groups',
				'item_link' => 'Group link',
				'item_link_description' => 'A link to a group',
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'show_in_rest' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'person'),
			'singular_label' => 'Group'
		)
	);


}
add_action('init', 'clarku_people_post_type', 9);
