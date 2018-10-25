<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Pho
 */
?>

	<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>

	<?php tha_sidebars_before(); ?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php tha_sidebar_top(); ?>

		<?php dynamic_sidebar( 'sidebar' ); ?>

		<?php tha_sidebar_bottom(); ?>
	</div><!-- #secondary -->
	<?php tha_sidebars_after(); ?>	

	<?php endif; ?>