<?php
/*
Plugin Name: Newscodes - Free Version
Plugin URI: http://www.mihajlovicnenad.com/newscodes
Description: News elements for your Wordpress webiste! - http://www.mihajlovicnenad.com
Version: 2.0.3
Author: Mihajlovic Nenad
Author URI: http://www.mihajlovicnenad.com

Text Domain: nwscds
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NewsCodes' ) ) :

	final class NewsCodes {

		public static $version = '2.0.3';

		protected static $_instance = null;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->includes();
			$this->init_hooks();

			do_action( 'newscodes_loaded' );
		}

		private function init_hooks() {
			register_activation_hook( __FILE__, array( 'NC_Setup', 'install' ) );
			add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );
			add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
			add_action( 'init', array( $this, 'init' ), 0 );
			add_action( 'init', array( 'NC_Shortcodes', 'init' ), 0 );

			add_action( 'vc_before_init', array($this, 'composer') );
			add_action( 'cornerstone_load_elements', array( $this, 'cornerstone' ) );
			add_filter( 'cornerstone_icon_map', array( $this, 'cornerstone_icon_map' ) );

			add_filter( 'nc_supported_styles', array( $this, 'defaults' ), 10, 1 );
			add_filter( 'nc_load_less_styles', array( $this, 'load_styles' ), 10, 1 );
		}

		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		public function includes() {
			include_once( 'includes/nc-setup.php' );
			include_once( 'includes/nc-core.php' );
			include_once( 'includes/nc-shortcodes.php' );

			if ( $this->is_request( 'admin' ) ) {
				include_once( 'includes/nc-admin-metaboxes.php' );
				include_once( 'includes/nc-admin-controls.php' );
				include_once( 'includes/nc-admin.php' );
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->frontend_includes();
			}
		}

		public function frontend_includes() {
			include_once( 'includes/nc-frontend.php' );
		}

		public function include_template_functions() {
			include_once( 'includes/nc-functions.php' );
		}

		public function init() {

			do_action( 'before_newscodes_init' );

			$this->load_plugin_textdomain();

			do_action( 'after_newscodes_init' );

		}

		public function load_plugin_textdomain() {

			$domain = 'newcodes';
			$dir = untrailingslashit( WP_LANG_DIR );
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			if ( $loaded = load_textdomain( $domain, $dir . '/plugins/' . $domain . '-' . $locale . '.mo' ) ) {
				return $loaded;
			}
			else {
				load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
			}

		}

		public function setup_environment() {
		}

		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		public function template_path() {
			return apply_filters( 'newscodes_template_path', 'newscodes/' );
		}

		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		public function plugin_basename() {
			return untrailingslashit( plugin_basename( __FILE__ ) );
		}

		public function ajax_url() {
			return admin_url( 'admin-ajax.php', 'relative' );
		}

		public function cornerstone() {

			require_once( 'includes/nc-cornerstone.php' );

		}

		public function composer() {

			require_once( 'includes/nc-composer.php' );

		}

		public function cornerstone_icon_map( $icon_map ) {
			$icon_map['newscodes-map'] = NC()->plugin_url() . '/lib/images/cs-icon.svg';
			return $icon_map;
		}

		public function version() {
			return self::$version;
		}

		public function defaults( $styles ) {
			return $styles + array( 'default' => array( 'name' => __( 'Default', 'nwscds' ), 'type' => 'default', 'styles' => array( 'classic-red-pt-serif' => array ( 'name' => 'Classic Red PT Serif', 'nc_heading' => array ( 'font-color' => '#222', 'font-family' => 'ggl-pT-serif', 'font-size' => '32px', 'font-style' => '', 'font-variant' => '', 'font-weight' => 'bold', 'letter-spacing' => '', 'line-height' => '36px', 'text-decoration' => '', 'text-transform' => 'none', 'text-align' => '', ), 'nc_heading_hover' => '#f00', 'nc_meta' => array ( 'font-color' => '#3a3a3a', 'font-family' => 'sys-arial', 'font-size' => '12px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '12px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_meta_background' => '#fff', 'nc_excerpt' => array ( 'font-color' => '#777', 'font-family' => 'ggl-pT-serif', 'font-size' => '16px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '26px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_taxonomy_color' => '#fff', 'nc_taxonomy_background' => '#f00', 'nc_navigation' => array ( 'font-color' => '#8e8e8e', 'font-family' => '', 'font-size' => '', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_navigation_hover' => '#f00', 'nc_navigation_style' => 'background-color', 'nc_tabs' => array ( 'font-color' => '#8e8e8e', 'font-family' => '', 'font-size' => '', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_tabs_hover' => '#f00', 'nc_tabs_style' => 'background-color', 'nc_format_standard' => '#888', 'nc_format_aside' => '#4358ab', 'nc_format_chat' => '#b1b1b1', 'nc_format_gallery' => '#b382e8', 'nc_format_link' => '#fb8c04', 'nc_format_image' => '#4fc03f', 'nc_format_quote' => '#332f53', 'nc_format_status' => '#92836d', 'nc_format_video' => '#f00', 'nc_format_audio' => '#f00', 'nc_tabs_padding' => 'default', 'nc_image_padding' => '35px', 'nc_meta_padding' => 'default', 'nc_heading_padding' => '25px', 'nc_excerpt_padding' => 'default', 'nc_pagination_padding' => '35px', ), 'handwritten-blue' => array ( 'name' => 'Handwritten Blue', 'nc_heading' => array ( 'font-color' => '#1e73be', 'font-family' => 'ggl-annie-use-your-telescope', 'font-size' => '', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '0.06em', 'line-height' => '', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_heading_hover' => '#222222', 'nc_meta' => array ( 'font-color' => '#666666', 'font-family' => '', 'font-size' => '', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_meta_background' => '#fff', 'nc_excerpt' => array ( 'font-color' => '#777', 'font-family' => 'inc-opensans', 'font-size' => '16px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '300', 'letter-spacing' => '', 'line-height' => '26px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_taxonomy_color' => '#fff', 'nc_taxonomy_background' => '#1e73be', 'nc_navigation' => array ( 'font-color' => '#1e73be', 'font-family' => 'inc-opensans', 'font-size' => '15px', 'font-style' => '', 'font-variant' => '', 'font-weight' => 'bold', 'letter-spacing' => '', 'line-height' => '22px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_navigation_hover' => '#222222', 'nc_navigation_style' => 'background-color', 'nc_tabs' => array ( 'font-color' => '#1e73be', 'font-family' => '', 'font-size' => '', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_tabs_hover' => '#222222', 'nc_tabs_style' => 'background-color', 'nc_format_standard' => '#888', 'nc_format_aside' => '#4358ab', 'nc_format_chat' => '#b1b1b1', 'nc_format_gallery' => '#b382e8', 'nc_format_link' => '#fb8c04', 'nc_format_image' => '#4fc03f', 'nc_format_quote' => '#332f53', 'nc_format_status' => '#92836d', 'nc_format_video' => '#f00', 'nc_format_audio' => '#1f80e0', 'nc_tabs_padding' => 'default', 'nc_image_padding' => 'default', 'nc_meta_padding' => 'default', 'nc_heading_padding' => 'default', 'nc_excerpt_padding' => 'default', 'nc_pagination_padding' => 'default', ), 'with-impact-pale-oswald' => array ( 'name' => 'With Impact Pale Oswald', 'nc_heading' => array ( 'font-color' => '#1c140d', 'font-family' => 'ggl-oswald', 'font-size' => '30px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '600', 'letter-spacing' => '-0.02em', 'line-height' => '34px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_heading_hover' => '#b9d7d9', 'nc_meta' => array ( 'font-color' => '#b9d7d9', 'font-family' => 'inc-opensans', 'font-size' => '13px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '13px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_meta_background' => '#fff', 'nc_excerpt' => array ( 'font-color' => '#1c140d', 'font-family' => 'inc-opensans', 'font-size' => '13px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '20px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_taxonomy_color' => '#fff', 'nc_taxonomy_background' => '#b9d7d9', 'nc_navigation' => array ( 'font-color' => '#deeeef', 'font-family' => 'inc-opensans', 'font-size' => '15px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '26px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_navigation_hover' => '#b9d7d9', 'nc_navigation_style' => '3d', 'nc_tabs' => array ( 'font-color' => '#deeeef', 'font-family' => '', 'font-size' => '15px', 'font-style' => '', 'font-variant' => '', 'font-weight' => '', 'letter-spacing' => '', 'line-height' => '20px', 'text-decoration' => '', 'text-transform' => '', 'text-align' => '', ), 'nc_tabs_hover' => '#b9d7d9', 'nc_tabs_style' => '3d', 'nc_format_standard' => '#6b96bf', 'nc_format_aside' => '#6b96bf', 'nc_format_chat' => '#6b96bf', 'nc_format_gallery' => '#6b96bf', 'nc_format_link' => '#6b96bf', 'nc_format_image' => '#6b96bf', 'nc_format_quote' => '#6b96bf', 'nc_format_status' => '#6b96bf', 'nc_format_video' => '#6b96bf', 'nc_format_audio' => '#6b96bf', 'nc_tabs_padding' => '15px', 'nc_image_padding' => '25px', 'nc_meta_padding' => '15px', 'nc_heading_padding' => '10px', 'nc_excerpt_padding' => '15px', 'nc_pagination_padding' => '15px', ) ) ) );
		}

		public function load_styles( $styles ) {

			$styles['default']['id'] = 'default';
			$styles['default']['url'] =  NC()->plugin_url() . '/lib/css/newscodes-styles.css';

			return $styles;

		}

	}

	function NC() {
		return NewsCodes::instance();
	}

	NewsCodes::instance();

endif;

?>