<?php 

if ( ! function_exists( 'supersport_post_thumbnail' ) ) {

	function supersport_post_thumbnail() {

		$position_thumbnail = apply_filters('supersport_thumnail_position', array() );

		if ( ! empty( $position_thumbnail ) && $position_thumbnail[0] == 'top-post-thumbnail' ) {
		?>

			<div class="thumbnail full">

				<a href="<?php the_permalink() ?>" rel="bookmark" class="featured-thumbnail">

				<?php

					if ( has_post_thumbnail() ) {

						the_post_thumbnail( 'supersport-full-thumbnail-list' );
					
					} else {

				?>
					
					<img class="wp-post-image" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/supersport-full-thumbnail-list.jpg' ) ?>" />

				<?php } ?>

				</a>

			</div>

		<?php } else { ?>
			
			<div class="thumbnail">

				<a href="<?php the_permalink() ?>" rel="bookmark" class="featured-thumbnail">

				<?php
					if ( has_post_thumbnail() ) {

						the_post_thumbnail( 'supersport-thumbnail-list' );

					} else {
				?>

					<img class="wp-post-image" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/supersport-thumbnail-list.jpg' ) ?>" />

				<?php } ?>

				</a>

			</div>

		<?php
				
		}
			
	}

}

if ( ! function_exists( 'supersport_post_excerpt' ) ) {

	function supersport_post_excerpt() {

		$position_thumbnail = apply_filters('supersport_thumnail_position', array() );

		if ( ! empty( $position_thumbnail ) && $position_thumbnail[0] == 'top-post-thumbnail' ) {

			do_action( 'supersport_post_content_before_before' );
			do_action( 'supersport_post_content_before');

		} else {

			do_action( 'supersport_post_content_before');
			do_action( 'supersport_post_content_before_before' );

		}

		echo '<div class="entry-content">';

		$supersport_display_excerpt = apply_filters('supersport_display_excerpt', true);


		if ( $supersport_display_excerpt ) {

			the_excerpt();

		} else {

			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue Reading %s <span class="meta-nav">&rarr;</span>', 'supersport' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

		}

		echo '</div>';

		do_action( 'supersport_post_content_after' );
	}
}

if ( ! function_exists( 'supersport_credit' ) ) {

	function supersport_credit() {
	?>

		<div class="site-info">

			<?php if ( apply_filters( 'basepress_credit_link', true ) ) { ?>

				<?php printf( esc_attr__( '%1$s Copyright © 2017 designed by %2$s', 'supersport' ), 'supersport', '<a href="https://themecountry.com" title="SprtPress - The best free blog theme for WordPress" rel="author">ThemeCountry</a>' ); ?>

			<?php } ?>

		</div><!-- .site-info -->

	<?php
	}
}

if ( ! function_exists( 'supersport_homepage_more_posts' ) ) {
	function supersport_homepage_more_posts() {
		?>

		<div id="magazine-load-more-wrap">
			<a id="magazine-load-more-post" href="<?php echo esc_url(get_permalink( get_option( 'page_for_posts' ) )); ?>"><?php _e( 'See More Posts <i class="fa fa-chevron-right" aria-hidden="true"></i>', 'supersport' ) ?></a>
		</div>

		<?php
	}
}

if ( ! function_exists('supersport_primary_nav_wrapper')) {

	function supersport_primary_nav_wrapper() {
		echo '<div class="main-navigation">';
	}

}

if ( ! function_exists( 'supersport_primary_nav' ) ) {

	function supersport_primary_nav() {
		if ( has_nav_menu( 'primary' ) ) {

		?>

		<nav id="site-navigation" class="main-nav" role="navigation">

			<span class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php _e('Main Navigation', 'supersport') ?></span>


			<?php
				// Display Primary Navigation.
				wp_nav_menu( array(
						'theme_location'	=> 'primary',
						'menu_id'			=> 'primary-navigation',
						'container'			=> '',
						'menu_class'		=> 'main-navigation-menu',
						'echo'				=> true,
						'fallback_cb'		=> 'basepress_default_menu',
					)
				);
			?>
		
		</nav><!-- #site-navigation -->
		
		<?php
		}
	}
}

if ( ! function_exists( 'supersport_social_nav' ) ) {

	function supersport_social_nav() {
		if ( has_nav_menu( 'social-menu' ) ) {
		?>

		<nav id="social-navigation" class="menu-social" role="navigation">

			<?php
				// Display Primary Navigation.
				wp_nav_menu( array(
					'theme_location'	=> 'social-menu',
					'menu_id'			=> 'social-menu',
					'container'			=> '',
					'menu_class'		=> 'social-icons-menu',
					'echo'				=> true,
					'fallback_cb'		=> 'basepress_default_menu',
					)
				);
			?>

		</nav><!-- #social-navigation -->
		
		<?php
		}
	}
}

if ( ! function_exists( 'supersport_top_search_header' ) ) {

	function supersport_top_search_header() {
	?>

		<div class="top-search">

			<a id="trigger-overlay">
				<i class="fa fa-search"></i>
			</a>

			<div class="overlay overlay-slideleft">
				
				<div class="search-row">

					<a ahref="#" class="overlay-close"><i class="fa fa-times"></i></a>

					<form method="get" id="searchform" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" _lpchecked="1">
						<input type="text" name="s" id="s" class="search-field" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_html_e('Search ...', 'supersport'); ?>" />
					</form>

				</div>

			</div>

		</div><!-- .top-search -->

	<?php

	}

}

if ( ! function_exists('supersport_primary_nav_wrapper_close') ) {

	function supersport_primary_nav_wrapper_close() {
		echo '</div>';
	}

}

if ( ! function_exists( 'supersport_featured_homepage_widget' ) ) {

	function supersport_featured_homepage_widget() {

		if ( ! is_active_sidebar( 'featured-homepage' ) ) {
			return;
		}
		echo '<div id="magazine-homepage-featured" class="widget-area magazine-homepage clearfix">';
			dynamic_sidebar( 'featured-homepage' );
		echo '</div>';

	}
}

if ( ! function_exists('supersport_magazin_layout_wrapper')) {

	function supersport_magazin_layout_wrapper() {

		if ( ! is_active_sidebar( 'featured-homepage' ) || ! is_active_sidebar( 'sidebar-1' ) ) {
			return;
		}
		echo '<div class="magazine-homepage-wrap clearfix">';

	}

}

if ( ! function_exists( 'supersport_magazine_homepage_widget' ) ) {
	
	function supersport_magazine_homepage_widget() {

		if ( ! is_active_sidebar( 'magazine-homepage' ) ) {

				// Display only to users with permission.
				if ( current_user_can( 'edit_theme_options' ) ) : ?>

				<div id="magazine-homepage-layout" class="main-magazine-homepage magazine-homepage clearfix">

					<p class="empty-widget-area">
						<?php esc_html_e( 'Please go to Appearance &#8594; Widgets and add at least one widget to the "Magazine Homepage" widget area. You can use the Magazine Posts widgets to set up the theme like the demo website.', 'supersport' ); ?>
					</p>

				</div>

				<?php
			endif;

			return;
		}

		echo '<div id="magazine-homepage-layout" class="main-magazine-homepage magazine-homepage clearfix">';
			do_action('supersport_before_magazine_homepage');
			dynamic_sidebar( 'magazine-homepage' );
			do_action('supersport_after_magazine_homepage');
		echo '</div>';

	}
}

if ( ! function_exists( 'supersport_sidebar_homepage_widget' ) ) {

	function supersport_sidebar_homepage_widget() {

		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			return;
		}

		echo '<aside id="secondary" class="sidebar widget-area" role="complementary">';
			dynamic_sidebar( 'sidebar-1' );
		echo '</aside>';

	}

}

if ( ! function_exists('supersport_magazin_layout_wrapper_close') ) {

	function supersport_magazin_layout_wrapper_close() {
		if ( ! is_active_sidebar( 'featured-homepage' ) || ! is_active_sidebar( 'sidebar-1' ) ) {
			return;
		}
		
		echo '</div>';
	}

}

if ( ! function_exists( 'supersport_primary_category' ) ) {
	
	function supersport_primary_category() {

		$firstCategory = get_the_category_list( esc_html__( ', ', 'supersport' ) );

		echo $firstCategory;
	}

}

/** 
 * Widget Featured Posts Render
 */
/**
 * Get posts for Widget Featured Post on Homepage
 * 
 * @param array $settings
 * @return object
 */
function supersport_widget_get_featured_posts( $settings, $number_posts = 5 ) {

	if ( 'RAND' === $settings['order_posts'] ) {

		$orderby = array( 'orderby' => 'rand' );

	} else {

		$orderby = array( 'order' => $settings['order_posts']);

	}

	$args = array(
		'posts_per_page'		=> $number_posts,
		'ignore_sticky_posts'	=> true,
		'cat'					=> (int)$settings['category']
	);

	$args = wp_parse_args( $args, $orderby);

	return new WP_Query( $args );
}

function supersport_featured_posts_thumbnail( $thumbnail_size = '' ) {
	?>

	<a href="<?php the_permalink() ?>" rel="bookmark">

		<?php if ( has_post_thumbnail() ) : ?>

			<?php the_post_thumbnail( $thumbnail_size ); ?>

		<?php else : ?>

			<img class="wp-post-image" src="<?php echo esc_url( get_stylesheet_directory_uri() ) ?>/images/<?php echo $thumbnail_size . '.jpg'; ?>" />

		<?php endif; ?>

	</a>

	<?php
}

function supersport_featured_posts_title( $settings ) {

	?>
	<header class="entry-header">

	<?php
		if( true == $settings['cats_post'] ) :

			echo '<div class="info-category"><span class="con-cat">';
			echo get_the_category_list( esc_html__( ' ', 'supersport' ) );
			echo '</div>';

		endif;
	?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>	

		<?php 
			if ( function_exists('basepress_post_header_meta'))
				basepress_post_header_meta() 

		?>

	</header><!-- .entry-header -->

	<?php
}

function supersport_featured_posts_layout_6( $settings ) {
	
	$loop = supersport_widget_get_featured_posts( $settings , 3 );

	// Check if there are posts
	if( $loop->have_posts() ) :
		
		?>
		<div class="magazine-posts-featured clearfix">

			<div class="three-posts clearfix">
			<?php
			while( $loop->have_posts() ) :

				$loop->the_post();
				?>

				<?php if ( 3 <= $loop->current_post ) break; ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'full-height-post default-magazin-title clearfix' ); ?>>
						
						<div class="post-thumbnail">
							<?php supersport_featured_posts_thumbnail( 'supersport-thumbnail-medium' ) ?>
						</div>

						<div class="posts-info-container medium-post-content">
							<?php supersport_featured_posts_title($settings); ?>
						</div>

					</article>

				<?php

				endwhile;
				?>
				</div><!-- end .three-posts -->

			</div><!-- /.magazine-posts-featured -->

			<?php
			
		endif;

		// Reset Postdata
		wp_reset_postdata();

	} // supersport_featured_post_layout_6()


function supersport_featured_posts_layout_8( $settings ) {
		
		$loop = supersport_widget_get_featured_posts( $settings );

		// Check if there are posts
		if( $loop->have_posts() ) : ?>

			<div class="magazine-posts-featured clearfix">

			<?php

			while( $loop->have_posts() ) :

				$loop->the_post(); ?>

				<?php if ( 0 == $loop->current_post ) : ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'mid-large-post clearfix' ); ?>>

						<div class="post-thumbnail">
							<?php supersport_featured_posts_thumbnail( 'supersport-post-featured' ) ?>
						</div>

						<div class="posts-info-container">
							<?php supersport_featured_posts_title( $settings ); ?>
						</div>

					</article>

				<?php else: ?>

					<?php if ( 1 == $loop->current_post ) : ?>
						<div class="mid-medium-posts clearfix">
					<?php endif; ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'mid-medium-post clearfix' ); ?> >
						
						<div class="post-thumbnail">
							<?php supersport_featured_posts_thumbnail( 'supersport-post-featured-grid' ) ?>
						</div>
						
						<div class="posts-info-container medium-post-content">
							<?php supersport_featured_posts_title($settings); ?>
						</div>

					</article>
					<?php endif; ?>

				<?php endwhile; ?>
			
				<?php if ( 1 == $loop->current_post ) : ?>
					</div> <!-- /.mid-medium-posts -->
				<?php endif; ?>

				</div><!-- /.magazine-posts-featured -->
				
			<?php
		endif;
		
		// Reset Postdata
		wp_reset_postdata();

} // supersport_featured_post_layout_8()

/**
 * Magazine Homepage List
 */

function supersport_magazine_homepage_get_posts( $settings ) {

	$query_arguments = array(
		'posts_per_page' => (int) $settings['number'],
		'ignore_sticky_posts' => true,
		'cat' => (int)$settings['category']
	);

	return new WP_Query( $query_arguments );
}
/**
 * Render post for magazine style 1: Top Images
 * 
 * @param [type] $settings [description]
 * @return [type] [description]
 */
function supersport_magazine_homepage_style_3( $settings ) {

	$loop = supersport_magazine_homepage_get_posts( $settings );

	// Check if there are posts
	if( $loop->have_posts() ) :

		// Display Posts
		while( $loop->have_posts() ) :

			$loop->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'full-post list-post clearfix' ); ?>>

					<header class="entry-header">

						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>								
						<?php 
						if ( function_exists('basepress_post_header_meta') )
							basepress_post_header_meta() ?>

					</header><!-- .entry-header -->

					<div class="thumbnail">
						<?php supersport_featured_posts_thumbnail( 'supersport-full-thumbnail-list' ) ?>
					</div>

					<div class="entry-content">

						<?php the_excerpt(); ?>

					</div><!-- .entry-content -->

				</article>

			<?php
		endwhile;
		?>

		<?php

	endif;

	// Reset Postdata
	wp_reset_postdata();

}

function supersport_magazine_homepage_style_2( $settings ) {

	$loop = supersport_magazine_homepage_get_posts( $settings );
	$left_right = apply_filters( 'supersport_magazine_left_right_thumbnail', 'right-post post-item clearfix' );

	// Check if there are posts
	if( $loop->have_posts() ) :
		?>
		
		<div class="magazine-posts-lists clearfix">
			
			<?php
			// Display Posts
			while( $loop->have_posts() ) :

				$loop->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( $left_right ); ?>>

					<div class="thumbnail">

						<?php if ( has_post_thumbnail() ) : ?>

							<?php supersport_featured_posts_thumbnail( 'supersport-thumbnail-list' ) ?>

						<?php else : ?>

							<img class="wp-post-image" src="<?php echo esc_url( get_stylesheet_directory_uri() ) ?>/images/supersport-thumbnail-list.jpg" />

						<?php endif; ?>

					</div>

					<header class="entry-header">

						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>								
						<?php 
							if ( function_exists('basepress_post_header_meta'))
								basepress_post_header_meta();
						 ?>

					</header><!-- .entry-header -->

					<div class="entry-content">

						<?php the_excerpt(); ?>

					</div><!-- .entry-content -->

				</article>

			<?php
			endwhile;
			?>

			</div>

			<?php
			
		endif;
		
		// Reset Postdata
		wp_reset_postdata();

} // supersport_magazine_homepage_style_2()

function supersport_magazine_homepage_style_1( $settings ) {

	add_filter('supersport_magazine_left_right_thumbnail', 'supersport_magazine_left_right_thumbnail');

	supersport_magazine_homepage_style_2( $settings );

	remove_filter('supersport_magazine_left_right_thumbnail', 'supersport_magazine_left_right_thumbnail');

}

function supersport_magazine_left_right_thumbnail( $left_right ) {

	return 'left-post post-item clearfix';

}