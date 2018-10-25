<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Pho
 */

if ( ! function_exists( 'pho_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function pho_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'pho' ); ?></h1>
		<div class="nav-links">

			<?php
			global $wp_query;
			$big = 999999999; // an unlikely integer

			echo paginate_links( array(
				'base'        => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'      => '?paged=%#%',
				'current'     => max( 1, get_query_var('paged') ),
				'total'       => $wp_query->max_num_pages,
				'prev_text'   => _x( '&larr;', 'Previous posts link', 'pho' ),
				'next_text'   => _x( '&rarr;', 'Next posts link', 'php' )
			) );
			?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'pho_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function pho_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'pho' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link(
					'<div class="nav-previous">%link</div>',
					get_the_post_thumbnail( $previous->ID, 'thumbnail' ) . '<div><span class="label">' . __( 'Previous post', 'pho' ) . '</span><span class="link">%title</span></div>'
				);
				next_post_link(
					'<div class="nav-next">%link</div>',
					get_the_post_thumbnail( $next->ID, 'thumbnail' ) . '<div><span class="label">' . __( 'Next post', 'pho' ) . '</span><span class="link">%title</span></div>'
				);
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'pho_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function pho_posted_on() {
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	} else {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Posted on %s', 'post date', '_s' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', '_s' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function pho_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'pho_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'pho_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so pho_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so pho_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in pho_categorized_blog.
 */
function pho_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'pho_categories' );
}
add_action( 'edit_category', 'pho_category_transient_flusher' );
add_action( 'save_post',     'pho_category_transient_flusher' );
