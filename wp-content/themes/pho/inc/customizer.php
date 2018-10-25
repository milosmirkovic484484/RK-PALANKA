<?php
/**
 * Pho Theme Customizer
 *
 * @package Pho
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pho_customize_register( $wp_customize ) {
	// Register Theme section
	$wp_customize->add_section(
		'theme',
		array(
			'title'    => __( 'Theme', 'pho' ),
			'priority' => 1
		)
	);

	// Primary Color
	$wp_customize->add_setting( 'primary_color', array(
		'default'     => '#e14546',
		'type'        => 'theme_mod',
		'capability'  => 'edit_theme_options',
		'transport'   => 'refresh',
	) );     
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize,
		'primary_color',
		array(
			'label'      => __( 'Primary Color', 'pho' ),
			'section'    => 'theme',
			'settings'   => 'primary_color',
			'priority'   => 10,
		) 
	) );

	// Archives layout
	$wp_customize->add_setting(
		'archives_layout',
		array(
			'default' => 'standard'
		)
	);
	$wp_customize->add_control(
		'archives_layout',
		array(
			'label'      => __( 'Archives layout', 'pho' ),
			'section'    => 'theme',
			'settings'   => 'archives_layout',
			'type'       => 'radio',
			'choices'    => array(
				'standard'  => 'Standard',
				'masonry'   => 'Masonry',
			),
			'priority'   => 20
		) 
	);

	// Logo
	$wp_customize->add_setting( 'logo', array(
		'type'        => 'theme_mod',
		'capability'  => 'edit_theme_options',
		'transport'   => 'refresh'
	) );     
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'logo',
		array(
			'label'      => __( 'Upload a logo (will replace site title)', 'pho' ),
			'section'    => 'theme',
			'settings'   => 'logo',
			'context'    => 'logo',
			'priority'   => 30,
		)
	) );

	// Typography 
	$wp_customize->add_setting(
		'body_font',
		array(
			'default' => 'Helvetica'
		)
	);
	$wp_customize->add_setting(
		'headings_font',
		array(
			'default' => 'Helvetica'
		)
	);
	$wp_customize->add_control(
		'body_font',
		array(
			'label'      => __( 'Body font', 'pho' ),
			'section'    => 'theme',
			'settings'   => 'body_font',
			'type'       => 'select',
			'choices'    => array(
				'Helvetica'   => 'Helvetica',
				'Cabin'       => 'Cabin',
				'Open Sans'   => 'Open Sans',
				'Droid Sans'  => 'Droid Sans',
				'Droid Serif' => 'Droid Serif',
				'Raleway'     => 'Raleway'
			),
			'priority'   => 40
		) 
	);
	$wp_customize->add_control(
		'headings_font',
		array(
			'label'      => __( 'Headings font', 'pho' ),
			'section'    => 'theme',
			'settings'   => 'headings_font',
			'type'       => 'select',
			'choices'    => array(
				'Helvetica'   => 'Helvetica',
				'Cabin'       => 'Cabin',
				'Open Sans'   => 'Open Sans',
				'Droid Sans'  => 'Droid Sans',
				'Droid Serif' => 'Droid Serif',
				'Raleway'     => 'Raleway'
			),
			'priority'   => 50
		) 
	);

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'pho_customize_register' );

/**
 * Adds Customizer CSS to front-end.
 */
function pho_customize_css() {
	// Check if body font is not default (Helvetica)
	if ( 'Helvetica' != get_theme_mod( 'body_font', 'Helvetica' ) ) { ?>
	<style id="pho-body-font" type="text/css">
		body,
		button,
		input,
		select,
		textarea,
		.site-description {
			font-family: <?php echo get_theme_mod( 'body_font' ); ?>;
		}
	</style>
	<?php }

	// Check if headings font is not default (Helvetica)
	if ( 'Helvetica' != get_theme_mod( 'headings_font', 'Helvetica' ) ) { ?>
	<style id="pho-headings-font" type="text/css">
		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			font-family: <?php echo get_theme_mod( 'headings_font' ); ?>;
		}
	</style>
	<?php }

	// Check if primary color is not equal to default value
	if ( '#e14546' != get_theme_mod( 'primary_color', '#e14546' ) ) { ?>
	<style id="pho-color" type="text/css">
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"],
	.paging-navigation span,
	.entry-content th,
	.comment-content th,
	.slider-control-paging .slider-active:before,
	.slider-control-paging .slider-active:hover:before,
	.reply a,
	.more-link,
	#infinite-handle span {
		background: <?php echo get_theme_mod( 'primary_color' ); ?>;
	}
	a,
	.main-navigation ul.nav-menu > .current_page_item a,
	.main-navigation ul.nav-menu > .current-menu-item a,
	.main-navigation ul.nav-menu > li > a:hover,
	.widget a,
	.entry-content blockquote:before,
	.comment-content blockquote:before,
	.entry-summary blockquote:before,
	#footer-bottom a {
		color: <?php echo get_theme_mod( 'primary_color' ); ?>;
	}
	.main-navigation ul ul,
	.entry-content blockquote,
	.comment-content blockquote,
	.entry-summary blockquote {
		border-color: <?php echo get_theme_mod( 'primary_color' ); ?>;
	}
	</style>
	<?php }
}
add_action( 'wp_head', 'pho_customize_css' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pho_customize_preview_js() {
	wp_enqueue_script( 'pho_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'pho_customize_preview_js' );
