<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.lorainccc.edu
 * @since             1.0.0
 * @package           My_Lccc_Info_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       My LCCC Info Feed
 * Plugin URI:        www.lorainccc.edu
 * Description:       This plugin is designed to create the custom post types and widgets for the MyLCCC site as apart of the new Lorain County Community College Multisite.
 * Version:           1.0.0
 * Author:            David Brattoli
 * Author URI:        www.lorainccc.edu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-lccc-info-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-my-lccc-info-feed-activator.php
 */
function activate_my_lccc_info_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-lccc-info-feed-activator.php';
	My_Lccc_Info_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-my-lccc-info-feed-deactivator.php
 */
function deactivate_my_lccc_info_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-lccc-info-feed-deactivator.php';
	My_Lccc_Info_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_my_lccc_info_feed' );
register_deactivation_hook( __FILE__, 'deactivate_my_lccc_info_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-my-lccc-info-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_my_lccc_info_feed() {

	$plugin = new My_Lccc_Info_Feed();
	$plugin->run();

}
run_my_lccc_info_feed();

require_once( plugin_dir_path( __FILE__ ).'php/lccc_posttypes.php' );

/**
* The Enque Function for the Jquery UI function of the metabox code below
*/
function my_lccc_info_feed_scripts() {

	wp_enqueue_style('my_lccc_info_feed_style', plugin_dir_url( __FILE__ ) . 'css/my_lccc_info_feed_styles.css');

	wp_enqueue_style('my_lccc_font', plugin_dir_url( __FILE__ ) . 'fonts/styles.css');

}
add_action ('init','my_lccc_info_feed_scripts');

function my_lccc_info_feed_wp_admin_scripts() {
 wp_enqueue_script('jquery-ui-datepicker');

	wp_enqueue_script('jquery-ui-core');

	wp_enqueue_script( 'jquery-ui-timepicker-addon-js', plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery','jquery-ui-core','jquery-ui-datepicker' ), '1', true );

 wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);

	wp_enqueue_style('jquery-ui-timepicker-addon-style', plugin_dir_url( __FILE__ ) . 'css/jquery-ui-timepicker-addon.css');
}

add_action( 'admin_enqueue_scripts', 'my_lccc_info_feed_wp_admin_scripts' );

/*
function enqueue_foundation() {
 Add Foundation CSS
 wp_enqueue_style( 'foundation-normalize',  plugin_dir_url( __FILE__ ) . '/foundation/css/normalizemin.css' );
 wp_enqueue_style( 'foundation',  plugin_dir_url( __FILE__ ) . '/foundation/css/foundation.min.css' );
 */

/* Add Foundation JS */

 /*wp_enqueue_script( 'foundation-js', get_template_directory_uri() . '/foundation/js/foundation.min.js', array( 'jquery' ), '1', true );
 wp_enqueue_script( 'foundation-modernizr-js',  plugin_dir_url( __FILE__ ) . '/foundation/js/vendor/modernizr.js', array( 'jquery' ), '1', true );
 */
/* Foudnation Init JS
 wp_enqueue_script( 'foundation-init-js',  plugin_dir_url( __FILE__ ) . 'foundation.js', array( 'jquery', 'foundation-js' ), '1', true );

	  }
add_action( 'wp_enqueue_scripts', 'enqueue_foundation' );
*/

// Add various fields to the JSON output
function eventapi_register_fields() {
	// Add Start Date
	register_api_field( 'lccc_events',
		'event_start_date',
		array(
			'get_callback'		=> 'gofurther_get_event_start_date',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);
   // Add Start Date Month
	register_api_field( 'lccc_events',
		'event_start_date_month',
		array(
			'get_callback'		=> 'gofurther_get_event_start_date_month',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);
    // Add Start Date Day
	register_api_field( 'lccc_events',
		'event_start_date_day',
		array(
			'get_callback'		=> 'gofurther_get_event_start_date_day',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);
    // Add Start time
	register_api_field( 'lccc_events',
		'event_start_time',
		array(
			'get_callback'		=> 'gofurther_get_event_start_time',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);

    // Add Event end_date
	register_api_field( 'lccc_events',
		'event_end_date',
		array(
			'get_callback'		=> 'gofurther_get_event_end_date',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);

    // Add Event end_time
	register_api_field( 'lccc_events',
		'event_end_time',
		array(
			'get_callback'		=> 'gofurther_get_event_end_time',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);


    // Add Stocker bg_color
	register_api_field( 'lccc_events',
		'event_meta_box_stocker_bg_color',
		array(
			'get_callback'		=> 'gofurther_get_event_stocker_bg_color',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);

    // Add Stocker link
	register_api_field( 'lccc_events',
		'event_meta_box_stocker_ticket_link',
		array(
			'get_callback'		=> 'gofurther_get_event_stocker_ticket_link',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);

// Add Announcment Start Date
	register_api_field( 'lccc_announcement',
		'announcement_start_date',
		array(
			'get_callback'		=> 'lccc_get_announcement_start_date',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);
 
 // Add Start Date Month
	register_api_field( 'lccc_announcement',
		'announcement_start_date_month',
		array(
			'get_callback'		=> 'lccc_get_announcement_start_date_month',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);
 
 // Add Start Date Day
	register_api_field( 'lccc_announcement',
		'announcement_start_date_day',
		array(
			'get_callback'		=> 'lccc_get_announcement_start_date_day',
			'update_callback'	=> null,
			'schema'			=> null
		)
	);
}

function gofurther_get_event_start_date( $object, $field_name, $request ) {
	return event_meta_box_get_meta('event_start_date');
}
function gofurther_get_event_start_date_month( $object, $field_name, $request ) {
	$starteventdate = event_meta_box_get_meta('event_start_date');
    	$startdate=strtotime($starteventdate);
    	$eventstartmonth=date("M",$startdate);
	return $eventstartmonth;
}
function gofurther_get_event_start_date_day( $object, $field_name, $request ) {
    $starteventdate = event_meta_box_get_meta('event_start_date');
    $startdate=strtotime($starteventdate);
    $eventstartday =date("j",$startdate);
	return $eventstartday;
}
function gofurther_get_event_start_time( $object, $field_name, $request ) {
	return event_meta_box_get_meta('event_start_time');
}
function gofurther_get_event_end_date( $object, $field_name, $request ) {
	return event_meta_box_get_meta('event_end_date');
}
function gofurther_get_event_end_time( $object, $field_name, $request ) {
	return event_meta_box_get_meta('event_end_time');
}
function gofurther_get_event_stocker_bg_color( $object, $field_name, $request ) {
	return event_meta_box_get_meta('event_meta_box_stoccker_bg_color');
}
function gofurther_get_event_stocker_ticket_link( $object, $field_name, $request ) {
	return event_meta_box_get_meta('event_meta_box_stocker_ticket_link');
}

function lccc_get_announcement_start_date( $object, $field_name, $request ) {
	return announcement_meta_box_get_meta('announcement_start_date');
}

function lccc_get_announcement_start_date_month( $object, $field_name, $request ) {
	$starteventdate = announcement_meta_box_get_meta('announcement_start_date');
    	$startdate=strtotime($starteventdate);
    	$eventstartmonth=date("M",$startdate);
	return $eventstartmonth;
}
function lccc_get_announcement_start_date_day( $object, $field_name, $request ) {
    $starteventdate = announcement_meta_box_get_meta('announcement_start_date');
    $startdate=strtotime($starteventdate);
    $eventstartday =date("j",$startdate);
	return $eventstartday;
}

add_action( 'rest_api_init', 'eventapi_register_fields');

require_once( plugin_dir_path( __FILE__ ).'php/lccc_pluginmetabox.php' );

require_once( plugin_dir_path( __FILE__ ).'php/displayfunctions.php' );

require_once( plugin_dir_path( __FILE__ ).'php/lccc_eventwidget.php' );

require_once( plugin_dir_path( __FILE__ ).'php/lccc_announcementwidget.php' );

require_once( plugin_dir_path( __FILE__ ).'php/lccc_announcement-subsite-widget.php' );

require_once( plugin_dir_path( __FILE__ ).'php/lccc_stocker_eventwidget.php' );

require_once( plugin_dir_path( __FILE__ ).'php/rest-api-fetch.php' );

require_once( plugin_dir_path( __FILE__ ).'php/lccc-event-rest-widget.php' );

require_once( plugin_dir_path( __FILE__ ).'php/lccc-announcement-rest-widget.php' );

add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if( is_category() ) {
    $post_type = get_query_var('post_type');
    if($post_type)
        $post_type = $post_type;
    else
        $post_type = array('nav_menu_item', 'post', 'lccc_events'); // don't forget nav_menu_item to allow menus to work!
    $query->set('post_type',$post_type);
    return $query;
    }
}

function get_cat_slug($cat_id) {
	$cat_id = (int) $cat_id;
	$category = get_category($cat_id);
	return $category->slug;
}

?>