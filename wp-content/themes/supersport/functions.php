<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package  supersport
 * @since 1.0.0
 */

/**
 * Assign the base version to a var
 */

$theme				= wp_get_theme( 'supersport' );
$supersport_version	= $theme['Version'];

$supersport = (object) array(
	'version'		=> $supersport_version,
	'main'			=> require 'inc/class-supersport.php',
	'customizer'	=> require 'inc/customizer/class-theme-customizer.php',
);

// Include Core Function
require_once 'inc/function-template.php';

// Include Core Template
require_once 'inc/class-template.php';

// Include Magazine Featured Widget
require_once 'inc/widget/magazine-posts-featured.php';

// Include Magazine Homepage Widget
require_once 'inc/widget/magazine-posts-lists.php';
