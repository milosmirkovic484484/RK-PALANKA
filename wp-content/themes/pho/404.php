<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Pho
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<div class="page-content">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'pho' ); ?></h1>

					<p><?php _e( 'It looks like nothing was found at this location. Maybe try search?', 'pho' ); ?></p>

					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
