<?php
// Register Custom Post Type
add_action( 'init', 'register_cpt_lccc_announcement' );

function register_cpt_lccc_announcement() {

	$labels = array(
		'name' => __( 'LCCC Announcements', 'lccc_announcement' ),
		'singular_name' => __( 'LCCC Announcement', 'lccc_announcement' ),
		'add_new' => __( 'Add New', 'lccc_announcement' ),
		'add_new_item' => __( 'Add New Announcement', 'lccc_announcement' ),
		'edit_item' => __( 'Edit Announcement', 'lccc_announcement' ),
		'new_item' => __( 'New Announcement', 'lccc_announcement' ),
		'view_item' => __( 'View Announcement', 'lccc_announcement' ),
		'search_items' => __( 'Search Announcements', 'lccc_announcement' ),
		'not_found' => __( 'No Announcements Found', 'lccc_announcement' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'lccc_announcement' ),
		'parent_item_colon' => __( 'Parent Item', 'lccc_announcement' ),
		'menu_name' => __( 'LCCC Announcements', 'lccc_announcement' ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'description' => 'This is the post type created for the displaying the announcements of the Lorain County Community College',
		'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', 'revisions' ),
		'taxonomies' => array( 'category' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_rest' => true,
  'rest_base'  => 'lccc_announcement',
  'rest_controller_class' => 'WP_REST_Posts_Controller',
		'menu_icon' => 'dashicons-megaphone',
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'lccc_announcement', $args );
}

// Register Custom Post Type

add_action( 'init', 'register_cpt_lccc_events' );

function register_cpt_lccc_events() {

	$labels = array(
		'name' => __( 'LCCC Events', 'lccc_events' ),
		'singular_name' => __( 'LCCC Event', 'lccc_events' ),
		'add_new' => __( 'Add New', 'lccc_events' ),
		'add_new_item' => __( 'Add New Event', 'lccc_events' ),
		'edit_item' => __( 'Edit Event', 'lccc_events' ),
		'new_item' => __( 'New Event', 'lccc_events' ),
		'view_item' => __( 'View Event', 'lccc_events' ),
		'search_items' => __( 'Search Events', 'lccc_events' ),
		'not_found' => __( 'No Events Found', 'lccc_events' ),
		'not_found_in_trash' => __( 'No lccc events found in Trash', 'lccc_events' ),
		'parent_item_colon' => __( 'Parent LCCC Event:', 'lccc_events' ),
		'menu_name' => __( 'LCCC Events', 'lccc_events' ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'description' => 'This is the post type created for the displaying the events of the Lorain County Community College',
		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'excerpt', 'author' ),
		'taxonomies' => array( 'where_to_display','event_categories' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_rest' => true,
  'rest_base' => 'lccc_events',
  'rest_controller_class' => 'WP_REST_Posts_Controller',
		'menu_icon' => 'dashicons-calendar-alt',
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'lccc_events', $args );
}

function be_register_taxonomies() {
	$taxonomies = array(
		array(
			'slug'         => 'where_to_display',
			'single_name'  => 'Where To Display',
			'plural_name'  => 'Where To Display Locations',
			'post_type'    => 'lccc_events',
					'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'where-to-display' ),
		),
		array(
			'slug'         => 'event_categories',
			'single_name'  => 'Event Category',
			'plural_name'  => 'Event Categoies',
			'post_type'    => 'lccc_events',
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'event-categories' ),
		),
	
	);
	foreach( $taxonomies as $taxonomy ) {
		$labels = array(
			'name' => $taxonomy['plural_name'],
			'singular_name' => $taxonomy['single_name'],
			'search_items' =>  'Search ' . $taxonomy['plural_name'],
			'all_items' => 'All ' . $taxonomy['plural_name'],
			'parent_item' => 'Parent ' . $taxonomy['single_name'],
			'parent_item_colon' => 'Parent ' . $taxonomy['single_name'] . ':',
			'edit_item' => 'Edit ' . $taxonomy['single_name'],
			'update_item' => 'Update ' . $taxonomy['single_name'],
			'add_new_item' => 'Add New ' . $taxonomy['single_name'],
			'new_item_name' => 'New ' . $taxonomy['single_name'] . ' Name',
			'menu_name' => $taxonomy['plural_name']
		);
		
		$rewrite = isset( $taxonomy['rewrite'] ) ? $taxonomy['rewrite'] : array( 'slug' => $taxonomy['slug'] );
		$hierarchical = isset( $taxonomy['hierarchical'] ) ? $taxonomy['hierarchical'] : true;
	
		register_taxonomy( $taxonomy['slug'], $taxonomy['post_type'], array(
			'hierarchical' => $hierarchical,
			 'show_tagcloud' => false,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'show_admin_column' => true,
			'rewrite' => $rewrite,
		));
	}
	
}
add_action( 'init', 'be_register_taxonomies' );
					
?>