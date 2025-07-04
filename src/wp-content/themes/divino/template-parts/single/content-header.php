<?php
/**
 * Template for Single Page
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 4.0.0
 */

if ( apply_filters( 'divino_single_layout_one_banner_visibility', true ) ) {

	if ( ! ( is_front_page() && 'page' === get_option( 'show_on_front' ) && divino_get_option( 'ast-dynamic-single-page-disable-structure-meta-on-front-page', false ) ) ) {
		?>
			<header class="entry-header <?php divino_entry_header_class(); ?>">
				<?php divino_banner_elements_order(); ?>
			</header> <!-- .entry-header -->
		<?php
	}
}
?>

<div class="entry-content clear"
	<?php
			echo wp_kses_post(
				divino_attr(
					'article-entry-content-page',
					array(
						'class' => '',
					)
				)
			);
			?>
>

	<?php divino_entry_content_before(); ?>

	<?php the_content(); ?>

	<?php divino_entry_content_after(); ?>

	<?php
		wp_link_pages(
			array(
				'before'      => '<div class="page-links">' . esc_html( divino_default_strings( 'string-single-page-links-before', false ) ),
				'after'       => '</div>',
				'link_before' => '<span class="page-link">',
				'link_after'  => '</span>',
			)
		);
		?>

</div><!-- .entry-content .clear -->
