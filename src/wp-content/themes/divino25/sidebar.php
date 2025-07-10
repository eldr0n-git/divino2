<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package divino25
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>

	<div class="widget">
		<h2 class="widget-title">Filters</h2>
		<?php echo do_shortcode('[woocommerce_product_filter_price]'); ?>
		<?php echo do_shortcode('[woocommerce_product_filter_category]'); ?>
		<?php echo do_shortcode('[woocommerce_product_filter_tag]'); ?>
	</div>
</aside><!-- #secondary -->
