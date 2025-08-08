<?php
// Shortcode to display wine style for wine products only
function divino_wine_style_shortcode() {
    if ( ! function_exists( 'wc_get_product' ) ) {
        return '';
    }
    $product = wc_get_product();
    if ( ! $product ) {
        return '';
    }
    $post_id = $product->get_id();
    
    // Check if product has product_kind taxonomy with value "wine"
    $product_kinds = wp_get_post_terms($post_id, 'product_kind', array('fields' => 'slugs'));
    
    // Only display wine style for wine products
    if (is_wp_error($product_kinds) || !in_array('wine', $product_kinds)) {
        return '';
    }
    
    // Get wine style terms
    $wine_styles = wp_get_post_terms($post_id, 'wine_style');
    
    if (empty($wine_styles) || is_wp_error($wine_styles)) {
        return '';
    }
    
    // Format the output
    $style_names = array_map(function($term) {
        return $term->name;
    }, $wine_styles);
    
    return '<span class="wine-style"><span class="value">' . implode(', ', $style_names) . '</span></span>';
    // return '<div class="wine-style-display"><strong class="wine-style-label">Стиль: </strong><span class="wine-style-value">' . implode(', ', $style_names) . '</span></div>';
}
add_shortcode('divino_wine_style', 'divino_wine_style_shortcode');


// ---------- Гутенберг-блок ----------
function divino_winestyle_register_block() {
    register_block_type( 'divino/winestyle', array(
        'render_callback' => 'divino_winestyle_block_render',
    ) );
}
add_action( 'init', 'divino_winestyle_register_block' );

function divino_winestyle_block_render( $attributes, $content ) {
    return divino_wine_style_shortcode();
}