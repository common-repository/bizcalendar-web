<?php
define( 'DOING_AJAX', true );
define( 'SHORTINIT', TRUE );

$GLOBALS['wp_plugin_paths'] = array();
require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
require ABSPATH . WPINC . '/http.php';
require ABSPATH . WPINC . '/post.php';
require ABSPATH . WPINC . '/pluggable.php';
require ABSPATH . WPINC . '/class-wp-post.php';
require ABSPATH . WPINC . '/link-template.php';
require ABSPATH . WPINC . '/general-template.php';
require ABSPATH . WPINC . '/class-http.php';
require ABSPATH . WPINC . '/class-wp-http-streams.php';
require ABSPATH . WPINC . '/class-wp-http-curl.php';
require ABSPATH . WPINC . '/class-wp-http-proxy.php';
require ABSPATH . WPINC . '/class-wp-http-cookie.php';
require ABSPATH . WPINC . '/class-wp-http-encoding.php';
require ABSPATH . WPINC . '/class-wp-http-response.php';
require ABSPATH . WPINC . '/class-wp-http-requests-response.php';
require ABSPATH . WPINC . '/class-wp-http-requests-hooks.php';

if(!function_exists('_wp_get_current_user')){
	class FakeUser {
		static $instance;
		static function exists(){ return false; }
	}
	function _wp_get_current_user(){
		return FakeUser::$instance ? FakeUser::$instance : (FakeUser::$instance = new FakeUser);
	}
}
wp_plugin_directory_constants();

require 'bizcalendar.php';

header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
header( 'X-Robots-Tag: noindex' );

$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';
if ( ! has_action( "wp_ajax_nopriv_{$action}" ) ) {
	wp_die( '0', 400 );
}
do_action( "wp_ajax_nopriv_{$action}" );
wp_die( '0' );