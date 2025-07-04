<?php
/**
 * Blog Pro - Blog Layout 5 Template
 *
 * Update this template for Default Blog Style
 *
 * @package divino Addon
 */

$blog_structure_order = divino_get_option( 'blog-post-structure', array() );

?>
<div <?php divino_blog_layout_class( 'blog-layout-5' ); ?>>
	<?php $divino_addon_blog_featured_image = apply_filters( 'divino_featured_image_enabled', true ); ?>
	<?php if ( $divino_addon_blog_featured_image && in_array( 'image', $blog_structure_order ) ) { ?>
		<?php
		// Blog Post Featured Image.
			divino_get_post_thumbnail( '<div class="ast-blog-featured-section post-thumb ' . esc_html( apply_filters( 'divino_attr_ast-grid-col-6_output', 'ast-grid-col-6' ) ) . '">', '</div>' );
		?>
	<?php } ?>

	<div class="post-content <?php echo esc_html( apply_filters( 'divino_attr_ast-grid-col-6_output', 'ast-grid-col-6' ) ); ?>">

		<?php
			/** @psalm-suppress TooManyArguments */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			divino_blog_post_thumbnail_and_title_order( array( 'image' ) );
		/** @psalm-suppress TooManyArguments */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		?>

		<div class="entry-content clear"
		<?php
				echo wp_kses_post(
					divino_attr(
						'article-entry-content-blog-layout-3',
						array(
							'class' => '',
						)
					)
				);
				?>
				>

			<?php divino_entry_content_before(); ?>
			<?php divino_entry_content_after(); ?>

			<?php
				wp_link_pages(
					array(
						'before'      => '<div class="page-links">' . esc_html( divino_default_strings( 'string-blog-page-links-before', false ) ),
						'after'       => '</div>',
						'link_before' => '<span class="page-link">',
						'link_after'  => '</span>',
					)
				);
				?>
		</div><!-- .entry-content .clear -->
	</div><!-- .post-content -->
</div> <!-- .blog-layout-5 -->
