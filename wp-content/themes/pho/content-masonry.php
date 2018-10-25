<?php
/**
 * @package Pho
 */
?>

<?php tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'masonry-post' ); ?>>
	<?php tha_entry_top(); ?>

	<div class="masonry-inner">
		<header class="entry-header">
			<?php if ( has_post_thumbnail() ) { ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail(); ?></a>
			<?php } ?>

			<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php pho_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( 'image' != get_post_format() ) { ?>
		<div class="entry-summary">
			<?php if ( 'quote' == get_post_format() || 'status' == get_post_format() || 'aside' == get_post_format() ) {
				the_content();
			} else {
				the_excerpt();
			} ?>
		</div><!-- .entry-summary -->
		<?php } ?>

		<footer class="entry-footer">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'pho' ) );
				if ( $categories_list && pho_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'pho' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'pho' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'pho' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>

			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'pho' ), __( '1 Comment', 'pho' ), __( '% Comments', 'pho' ) ); ?></span>
			<?php endif; ?>

			<?php edit_post_link( __( 'Edit', 'pho' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .masonry-inner -->

	<?php tha_entry_bottom(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php tha_entry_after(); ?>