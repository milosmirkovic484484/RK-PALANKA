<?php
/**
 * Template functions
 *
 * @package supersport
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SuperSport_Template' ) ) {

	class SuperSport_Template {

		public function __construct() {

			add_action( 'init', array( $this, 'update_hooks' ) );

		}

		public function update_hooks (){

			// Remove hooks
			$this->_remove_hooks();

			// Add new hooks
			$this->_add_hooks();

		}

		private function _remove_hooks() {

			/**
			 * Post - Hooks
			 */
			remove_action( 'basepress_post_content_after', 'basepress_entry_meta_footer', 10 );
			remove_action( 'basepress_single_post', 'basepress_post_header_meta', 20 );
			remove_action( 'basepress_loop_post', 'basepress_post_content', 30 );
			remove_action( 'basepress_footer', 'basepress_credit', 40 );
			remove_action( 'basepress_header', 'basepress_primary_navigation', 20 );
			remove_action( 'basepress_homepage', 'basepress_homepage_content', 10);
			remove_action( 'basepress_single_post_bottom', 'basepress_pro_author_bio', 30 );

			remove_action( 'basepress_loop_post',           'basepress_post_header', 10 );

			remove_action( 'basepress_category_menu',		'basepress_category_navigation',		0 );

			remove_action( 'basepress_loop_post',  			'basepress_post_header_wrapper',   		 5 );
			remove_action( 'basepress_loop_post',           'basepress_post_header',          		10 );
			remove_action( 'basepress_loop_post',  			'basepress_post_header_wrapper_close', 	20 );
		}

		private function _add_hooks() {

			/**
			 * Header - Hooks
			 */
			add_action( 'basepress_header', 'supersport_primary_nav_wrapper', 15 );
			add_action( 'basepress_header', 'supersport_primary_nav', 20 );			
			add_action( 'basepress_header', 'supersport_top_search_header', 25 );
			add_action( 'basepress_header', 'supersport_social_nav', 25 );
			add_action( 'basepress_header', 'supersport_primary_nav_wrapper_close', 30 );


			/**
			 * Post - Hooks
			 */
			add_action( 'basepress_loop_post', 'supersport_post_excerpt', 30 );
			add_action( 'basepress_after_post_title', 'basepress_post_header_meta', 10);
			add_action( 'supersport_post_content_before', 'supersport_post_thumbnail', 10 );


			add_action( 'supersport_post_content_before_before',  'basepress_post_header_wrapper', 			15 );
			add_action( 'supersport_post_content_before_before',  'basepress_post_header', 					20 );
			add_action( 'supersport_post_content_before_before',  'basepress_post_header_wrapper_close', 	25 );

			add_action( 'basepress_footer', 'supersport_credit', 40 );


			/**
			 * Homepage - Hooks
			 */
			add_action('basepress_homepage', 'supersport_featured_homepage_widget', 	10	);
			add_action('basepress_homepage', 'supersport_magazin_layout_wrapper', 		15	);
			add_action('basepress_homepage', 'supersport_magazine_homepage_widget', 	20	);
			add_action('basepress_homepage', 'supersport_sidebar_homepage_widget', 		30	);
			add_action('basepress_homepage', 'supersport_magazin_layout_wrapper_close', 35	);

			/**
			 * Filters
			 */
			add_filter('basepress_enable_post_metadata', array( $this, 'enable_post_metadata' ));

			/**
			 * supersport Hooks
			 */
			add_action('supersport_after_magazine_homepage', 'supersport_homepage_more_posts', 10);

			/**
			 * Remove action - Plugin supersport Pro (When Active)
			 */
			do_action('after_supersport_setup');

		}

		public function enable_post_metadata() {

				$metadata = apply_filters('supersport_enable_post_metadata', array( 'author','category','date' ) );

				return $metadata;
		}

	}
}

return new SuperSport_Template();