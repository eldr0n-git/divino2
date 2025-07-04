<?php
/**
 * Template for Blog
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

?>
<div <?php divino_blog_layout_class( 'blog-layout-1' ); ?>>
	<div class="post-content <?php echo wp_kses_post( divino_attr( 'ast-grid-common-col' ) ); ?>" >
		<?php divino_blog_post_thumbnail_and_title_order(); ?>
		<div class="entry-content clear"
		<?php
				echo wp_kses_post(
					divino_attr(
						'article-entry-content-blog-layout',
						array(
							'class' => '',
						)
					)
				);
				?>
		>
			<?php
				divino_entry_content_before();
				divino_entry_content_after();

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
</div> <!-- .blog-layout-1 -->
