<?php
// Shortcode to display wine style for wine products only
function divino_wine_style_shortcode() {
    global $post;
    
    // Check if this is a product
    if (!is_singular('product')) {
        return '';
    }
    
    // Check if product has product_kind taxonomy with value "wine"
    $product_kinds = wp_get_post_terms($post->ID, 'product_kind', array('fields' => 'slugs'));
    
    // Only display wine style for wine products
    if (!in_array('wine', $product_kinds)) {
        return '';
    }
    
    // Get wine style terms
    $wine_styles = wp_get_post_terms($post->ID, 'wine_style');
    
    if (empty($wine_styles)) {
        return '';
    }
    
    // Format the output
    $style_names = array_map(function($term) {
        return $term->name;
    }, $wine_styles);
    
    return '<div class="wine-style-display"><span class="wine-style-label">Стиль: </span>' . implode(', ', $style_names) . '</div>';
}
add_shortcode('divino_wine_style', 'divino_wine_style_shortcode');