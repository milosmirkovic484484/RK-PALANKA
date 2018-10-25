<?php
/**
 * Theme Customizer Class
 *
 * @package supersport
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'supersport_Customizer' ) ) :
	/**
	 * The supersport Customizer class
	 */
	class supersport_Customizer {

		public function __construct() {
			add_action( 'customize_register',       array( $this, 'customize_register' ), 10 );
			add_filter( 'body_class',               array( $this, 'layout_class' ) );

			// Override default style

			add_filter('basepress_setting_default_values', array($this, 'override_default_style'));
			
		}

		public function customize_register() {

			//TODO: Start register customize
		}

		public function layout_class( $classes ) {

			//TODO: Any style apply here

			return $classes;
		}

		public function add_customizer_css() {

		}
		public function override_default_style() {
			return array(
				'basepress_heading_color'               => '#333333',
				'basepress_text_color'                  => '#333333',
				'basepress_accent_color'                => '#025FB0',

				'basepress_footer_background_color'     => '#1a1a1a',
				'basepress_footer_heading_color'        => '#ffffff',
				'basepress_footer_text_color'           => '#666666',
				'basepress_footer_link_color'           => '#737373',
				'basepress_layout'                      => 'right',
				'background_color'                      => 'ffffff',
			);
		}
	}

endif;

return new supersport_Customizer();