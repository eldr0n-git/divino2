<?php
defined( 'ABSPATH' ) || exit;

global $product;

// Проверка на валидность и видимость
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	// Открытие ссылки на товар
	do_action( 'woocommerce_before_shop_loop_item' );

	// СКИДКА (если есть)
	do_action( 'woocommerce_show_product_loop_sale_flash' );

	// Кастомный вывод миниатюры
	$product_id = get_the_ID();
	$thumbnail_id = get_post_thumbnail_id( $product_id );
	$img_small = wp_get_attachment_image_src( $thumbnail_id, 'product-card' );

	if ( $img_small ) :
	?>
		<picture>
			<source srcset="<?php echo esc_url( $img_small[0] ); ?>.webp" type="image/webp">
			<img src="<?php echo esc_url( $img_small[0] ); ?>"
			     width="260"
			     height="370"
			     alt="<?php the_title_attribute(); ?>"
			     loading="lazy"
			     class="product-card-image" />
		</picture>
	<?php endif; ?>

	<?php
	// Заголовок и цена внутри ссылки
	?>
	<h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
	<?php woocommerce_template_loop_price(); ?>

	<?php
	// Название товара (повторно, если нужно)
	do_action( 'woocommerce_shop_loop_item_title' );

	// Рейтинг и цена (если нужно)
	do_action( 'woocommerce_after_shop_loop_item_title' );

	// Закрытие ссылки + кнопка "в корзину"
	do_action( 'woocommerce_after_shop_loop_item' );

	echo '<!-- divino25 content-product.php -->';
	?>
</li><!-- divino25 content-product.php -->
