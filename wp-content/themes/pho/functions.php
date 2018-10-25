<?php
/**
 * Pho functions and definitions
 *
 * @package Pho
 */


if ( ! function_exists( 'pho_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function pho_setup() {

	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 780; /* pixels */
	}

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Pho, use a find and replace
	 * to change 'pho' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'pho', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', pho_get_google_font_url() ) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 370, 210, true );
	add_image_size( 'pho-full-width', 1170, 660, true );

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'pho_get_featured_posts',
		'max_posts'               => 8,
	) );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'  => __( 'Primary Menu', 'pho' ),
		'footer'   => __( 'Footer Menu', 'pho' ),
		'social'   => __( 'Social Menu', 'pho' )
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'status', 'image', 'quote' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'pho_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );
}
endif; // pho_setup
add_action( 'after_setup_theme', 'pho_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Pho 1.0
 */
function pho_content_width() {
	if ( ! is_active_sidebar( 'sidebar' ) ) {
		$GLOBALS['content_width'] = 1170;
	}
}
add_action( 'template_redirect', 'pho_content_width' );

/**
 * Getter function for Featured Content.
 *
 * @since Pho 1.0
 *
 * @return array An array of WP_Post objects.
 */
function pho_get_featured_posts() {
	/**
	 * Filter the featured posts to return.
	 *
	 * @since Pho 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'pho_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Pho 1.0
 *
 * @return bool Whether there are featured posts.
 */
function pho_has_featured_posts() {
	return ! is_paged() && (bool) pho_get_featured_posts();
}

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function pho_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'pho' ),
		'id'            => 'sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'pho' ),
		'id'            => 'footer-widget-area',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'pho_widgets_init' );

/**
 * Enqueue scripts and styles.
 *
 * @uses pho_get_google_font_url()
 */
function pho_scripts() {
	// Genericons
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons/genericons.css', array(), '3.0.2' );

	// Google Fonts
	if ( pho_get_google_font_url() ) {
		wp_register_style( 'pho-fonts', pho_get_google_font_url() );
		wp_enqueue_style( 'pho-fonts' );
	}

	// Main stylesheet
	wp_enqueue_style( 'pho-style', get_stylesheet_uri() );

	// Navigation
	wp_enqueue_script( 'pho-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	// Skip link focus
	wp_enqueue_script( 'pho-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	// Comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Masonry
	if ( ( is_archive() || is_home() ) && 'masonry' == get_theme_mod( 'archives_layout', 'standard' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	// Slider
	if ( is_front_page() && pho_has_featured_posts() ) {
		wp_enqueue_script( 'pho-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '1.0', true );
		wp_localize_script( 'pho-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'pho' ),
			'nextText' => __( 'Next', 'pho' )
		) );
	}

	// Main JS file
	wp_enqueue_script( 'pho-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'pho_scripts' );

/**
 * Initialize Masonry.
 */
function pho_masonry_init() {
if ( ( is_archive() || is_home() ) && 'masonry' == get_theme_mod( 'archives_layout', 'standard' ) ) { ?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	var container = $('#posts-wrapper');
	container.imagesLoaded( function() {
		$(container).masonry({
			itemSelector: '.hentry',
			gutter:       0,
		});
	});
});
	/*
	var container = document.querySelector('#posts-wrapper');
	var msnry;

	alert( container );
	imagesLoaded( container, function() {
		msnry = new Masonry( container, {
			itemSelector: '.hentry',
			gutter:       30,
			isResizable:  true,
		});
	});
	*/
</script>
<?php }
}
add_action( 'wp_footer', 'pho_masonry_init', 100 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load THA hooks.
 */
require get_template_directory() . '/inc/libraries/tha/tha-theme-hooks.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Pho_Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';
}