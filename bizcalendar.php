<?php
/**
 * @package BizCalendarWeb
 * @version 1.1.0.31
 */
/*
Plugin Name: BizCalendar Web
Plugin URI: http://wordpress.org/plugins/setrio-bizcalendar/
Description: Modul de programări online pentru clinicile medicale care folosesc BizMedica / Online appointments form for medical clinics using BizMedica software
Author: Setrio Soft
Version: 1.1.0.31
Author URI: http://www.setrio.ro/
*/

// Securitate pentru rulare directa a scriptului
defined( 'ABSPATH' ) or defined( 'SHORTINIT' ) or die( 'No script kiddies please!' );

if ( ! defined( 'BIZCALENDAR_PLUGIN_DIR' ) ) {
	define( 'BIZCALENDAR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'BIZCALENDAR_PLUGIN_FILE' ) ) {
	define( 'BIZCALENDAR_PLUGIN_FILE', __FILE__ );
}

// Instalare
require_once("setup.php");
require_once("main.php");

register_activation_hook( __FILE__, 'setrio_bizcal_install' );
register_deactivation_hook( __FILE__, 'setrio_bizcal_deactivate' );

// add_action('wp_enqueue_scripts', 'setrio_bizcal_enqueue_scripts');

add_action('init', 'setrio_bizcal_shortcodes_init');
add_action('plugins_loaded', 'setrio_bizcal_init');
add_action('wp_footer', 'setrio_bizcal_ensure_form_is_added');

add_filter('body_class', 'setrio_bizcal_add_customer_body_class');
add_filter('clean_url', 'setrio_bizcal_add_async_forrecaptcha', 11, 1);

add_filter('script_loader_tag', 'setrio_bizcal_script_add_type_attribute' , 10, 3);

/// Admin
require_once("admin/bizcalendar-admin.php");

// AJAX
add_action('wp_ajax_get_medical_specialities', 'setrio_bizcal_ajax_get_medical_specialities');
add_action('wp_ajax_nopriv_get_medical_specialities', 'setrio_bizcal_ajax_get_medical_specialities');

add_action('wp_ajax_get_locations', 'setrio_bizcal_ajax_get_locations');
add_action('wp_ajax_nopriv_get_locations', 'setrio_bizcal_ajax_get_locations');

add_action('wp_ajax_get_medical_services', 'setrio_bizcal_ajax_get_medical_services');
add_action('wp_ajax_nopriv_get_medical_services', 'setrio_bizcal_ajax_get_medical_services');

add_action('wp_ajax_get_physicians', 'setrio_bizcal_ajax_get_physicians');
add_action('wp_ajax_nopriv_get_physicians', 'setrio_bizcal_ajax_get_physicians');

add_action('wp_ajax_get_prices', 'setrio_bizcal_ajax_get_medical_services_with_prices');
add_action('wp_ajax_nopriv_get_prices', 'setrio_bizcal_ajax_get_medical_services_with_prices');

add_action('wp_ajax_get_payment_types', 'setrio_bizcal_ajax_get_payment_types');
add_action('wp_ajax_nopriv_get_payment_types', 'setrio_bizcal_ajax_get_payment_types');

add_action('wp_ajax_get_allowed_payment_types', 'setrio_bizcal_ajax_get_allowed_payment_types');
add_action('wp_ajax_nopriv_get_allowed_payment_types', 'setrio_bizcal_ajax_get_allowed_payment_types');

add_action('wp_ajax_get_date_availabilities', 'setrio_bizcal_ajax_get_date_availabilities');
add_action('wp_ajax_nopriv_get_date_availabilities', 'setrio_bizcal_ajax_get_date_availabilities');

add_action('wp_ajax_get_availability', 'setrio_bizcal_ajax_get_availability');
add_action('wp_ajax_nopriv_get_availability', 'setrio_bizcal_ajax_get_availability');

add_action('wp_ajax_register_appointment', 'setrio_bizcal_ajax_register_appointment');
add_action('wp_ajax_nopriv_register_appointment', 'setrio_bizcal_ajax_register_appointment');

add_action('wp_ajax_get_price_for_service', 'setrio_bizcal_ajax_get_price_for_service');
add_action('wp_ajax_nopriv_get_price_for_service', 'setrio_bizcal_ajax_get_price_for_service');
add_action('wp_ajax_nopriv_setrio_testmail', 'setrio_bizcal_ajax_testmail');

add_action('wp_ajax_setrio_date_rel_abs', 'setrio_bizcal_ajax_dates');
add_action('wp_ajax_nopriv_setrio_date_rel_abs', 'setrio_bizcal_ajax_dates');

if( !function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
function bizcal_setrio_update(){
	if (!class_exists('WP_Filesystem')){
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php');
	}
	if (!class_exists('WP_Filesystem_Direct')){
		require_once(ABSPATH.'wp-admin/includes/file.php');
		require_once(ABSPATH.'wp-admin/includes/class-wp-filesystem-direct.php');
	}
	WP_Filesystem();
	global $wp_filesystem;
	
	$old_version = get_option('setrio_bizcal_old_version', '');
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	
	if(!version_compare($old_version,$plugin_version,'=')){
		update_option('setrio_bizcal_old_version', $plugin_version);
		if($old_version < $plugin_version){
			
			global $wpdb;
			
			if(version_compare($plugin_version,'1.1.0.14','>=')){
				if(version_compare($old_version,'1.1.0.14','<')){
					
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					
					$table_name = 'bizcal_request_log';
					$column_name = 'ip';
					$create_ddl = "ALTER TABLE `" . $wpdb->prefix . $table_name . "` ADD COLUMN `" . $column_name . "` VARCHAR(40) AFTER `response`";
					maybe_add_column($table_name, $column_name, $create_ddl);
				}
			}
			
			if(version_compare($plugin_version,'1.1.0.17','>=')){
				if(version_compare($old_version,'1.1.0.17','<')){
					$log_folder = WP_CONTENT_DIR .'/Mobilpay/Logs/';
					if(!is_dir($log_folder)){
						$created = wp_mkdir_p($log_folder);
						$wp_filesystem->put_contents($log_folder . 'index.html', '<html><body></body></html>');
						$wp_filesystem->put_contents($log_folder . '.htaccess', 'Deny from all');
					}
					$cert_folder = WP_CONTENT_DIR .'/Mobilpay/Certificates/';
					if(!is_dir($cert_folder)){
						$created = wp_mkdir_p($cert_folder);
						$wp_filesystem->put_contents($cert_folder . 'index.html', '<html><body></body></html>');
						$wp_filesystem->put_contents($cert_folder . '.htaccess', 'Deny from all');
					}
				}
			}
		}
	}
}
if(!defined('DOING_AJAX')){
	bizcal_setrio_update();
}
if(!empty($_GET['setrio-bizcal-mobilpay-status'])){
	add_action('wp_loaded', 'setrio_bizcal_online_payment_mobilpay_status',999999999999);
}
if(!empty($_GET['setrio-bizcal-mobilpay-confirm'])){
	add_action('init', 'setrio_bizcal_online_payment_mobilpay_confirm');
}
?>