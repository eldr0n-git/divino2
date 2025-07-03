<?php
/**
 * Шаблон страницы таксономии product_kind
 * Использует шаблон WooCommerce archive-product.php с полной темой
 */

get_header( 'shop' ); // подхватит header-shop.php, если есть, иначе header.php

woocommerce_output_content_wrapper(); // обычно открывает <div class="woocommerce"> и т.п.

wc_get_template_part( 'archive', 'product' ); // реальный шаблон WooCommerce

woocommerce_output_content_wrapper_end();

get_footer( 'shop' ); // аналогично
