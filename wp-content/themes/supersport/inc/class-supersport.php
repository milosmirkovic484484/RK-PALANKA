<?php
/**
 * Theme Class
 *
 * @package supersport
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SuperSport' ) ) :
	/**
	 * The main Theme class to init & setup theme
	 * 
	 */
	class SuperSport {

		public function __construct() {

			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_action( 'widgets_init', array( $this, 'widgets_init' ), 20 );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			add_filter('basepress_google_font_families', array($this, 'default_font'));

			// Theme Thumbnails
			$this->_add_image_sizes();
		}

		public function default_font() {

			return array( 'Roboto-Slab' => 'Roboto|Roboto+Slab:400,700');
		}

		/**
		 * Any theme setup go here
		 *
		 * @since 1.0.0
		 * 
		 */
		public function setup() {

			load_child_theme_textdomain( 'supersport', get_stylesheet_directory() . '/languages' );

			register_nav_menus( array(
				'social-menu' => __( 'Social Menu', 'supersport' )
			) );
		}

		/**
		 * Widget init
		 * 
		 * @since 1.0.0
		 */
		public function widgets_init() {

			register_sidebar( array(
				'name'			=> esc_html__( 'Featured Homepage', 'supersport' ),
				'id'			=> 'featured-homepage',
				'description'	=> esc_html__( 'Appears on Magazine Homepage template only. You can use the Magazine Posts widgets here.', 'supersport' ),
				'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<div class="gamma widget-title">',
				'after_title'	=> '</div>',
			));
			
			register_sidebar( array(
				'name'			=> esc_html__( 'Magazine Homepage', 'supersport' ),
				'id'			=> 'magazine-homepage',
				'description'	=> esc_html__( 'Appears on Magazine Homepage template only. You can use the Magazine Posts widgets here.', 'supersport' ),
				'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<div class="gamma widget-title">',
				'after_title'	=> '</div>',
			));

		}

		/**
		 * Child scripts & styles
		 * 
		 * @since 1.0.0
		 */
		public function scripts() {

			wp_enqueue_script( 'supersport-custom', get_stylesheet_directory_uri() . '/js/custom.js', array(), '20170609', true );

		}

		public function admin_scripts() {
			wp_enqueue_style( 'base-admin-css', get_stylesheet_directory_uri() . '/assets/css/admin-style.css', false, '1.0.0' );
		}

		/**
		 * Theme Thumbnails 
		 * 
		 * @return [type] [description]
		 */
		private function _add_image_sizes() {

			add_theme_support( 'post-thumbnails' );

			// Default Thumbnail Sizes
			add_image_size( 'supersport-thumbnail-medium', 366, 240, true ); // Big Verticle Boxed
			add_image_size( 'supersport-post-featured', 365, 520, true ); // Featured Big
			add_image_size( 'supersport-post-featured-grid', 386, 258, true ); // Featured Box
			add_image_size( 'supersport-thumbnail-related-post', 260, 160, true ); // Featured Full Height			
			add_image_size( 'supersport-full-thumbnail-list', 750, 386, true ); // Post Full List Thubmnail Post
			add_image_size( 'supersport-thumbnail-list', 260, 160, true ); // Post small List Thubmnail Post
			
			
		}

	}

endif;

return new SuperSport();

