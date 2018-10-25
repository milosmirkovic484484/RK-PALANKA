<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Pho
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function pho_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'pho_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function pho_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds utility class for full-width layouts
	if ( ! is_active_sidebar( 'sidebar' ) || is_404() ) {
		$classes[] = 'no-sidebar';
	}

	// Adds utility class for when featured posts area is shown
	if ( is_front_page() && pho_has_featured_posts() ) {
		$classes[] = 'has-featured';
	}

	// Adds archives layout class
	$classes[] = 'layout-' . get_theme_mod( 'archives_layout', 'standard' );

	// Adds custom background classes, needed because of https://core.trac.wordpress.org/ticket/28687
	if ( 'ffffff' != get_theme_mod( 'background_color', 'ffffff' ) )
		$classes[] = 'custom-background-color';
	if ( '' != get_theme_mod( 'background_image', '' ) )
		$classes[] = 'custom-background-image';

	return $classes;
}
add_filter( 'body_class', 'pho_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function pho_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'pho' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'pho_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function pho_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'pho_setup_author' );

/**
 * Gets Google Fonts embed URL, if needed.
 *
 * Checks if Google Fonts are selected from Typography
 * options in Theme Customizer.
 *
 * @uses   get_theme_mod
 * @return string | false
 */
function pho_get_google_font_url() {
	$font_families = array();

	// Check if body font is not Helvetica (all remaining options are Google Fonts)
	if ( 'Helvetica' != get_theme_mod( 'body_font', 'Helvetica' ) ) {
		$font_families[] = get_theme_mod( 'body_font' ) . ':400,400italic,700,700italic';
	} 
	// Check if heading font is not Helvetica and is different than body font
	if ( 'Helvetica' != get_theme_mod( 'headings_font', 'Helvetica' ) && get_theme_mod( 'body_font' ) != get_theme_mod( 'headings_font' ) ) {
		$font_families[] = get_theme_mod( 'headings_font' ) . ':400,400italic';
	} 

	if ( ! empty( $font_families ) ) {
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

		return $fonts_url;
	}

	return false;
}

/**
 * Count number of widgets in a sidebar
 * Used to add classes to widget areas so widgets can be displayed one, two or three per row
 *
 * @uses	wp_get_sidebars_widgets()		http://codex.wordpress.org/Function_Reference/wp_get_sidebars_widgets
 * @since	Pho 1.0
 */
function pho_count_widgets( $sidebar_id ) {
	/* 
	 * Count widgets in footer widget area
	 * Used to set widget width based on total count
	 */
	$sidebars_widgets_count = wp_get_sidebars_widgets();
	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		$widget_classes = 'widget-count-' . count( $sidebars_widgets_count[ $sidebar_id ] );
		if ( $widget_count % 4 == 0 || $widget_count > 6 ) : // four per row if four widgets or more than 6
			$widget_classes .= ' per-row-4';
		elseif ( $widget_count % 3 == 0 || $widget_count > 3 ) :
			$widget_classes .= ' per-row-3';
		elseif ( 2 == $widget_count ) :
			$widget_classes .= ' per-row-2';
		endif; 
		 
		return $widget_classes;
	endif;
}