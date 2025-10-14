<?php
/**
 * Рендер блока Product Kind Menu
 */
// Выводим меню с дочерними терминами таксономии 'product_kind'
// для этого нужно сначала получить родительские термины, а затем для каждого
// родительского термина получить его дочерние термины
$terms = get_terms([
    'taxonomy' => 'product_kind',
    'hide_empty' => false,
    'parent' => 0, // Сначала получаем родительские термины
]);

$child_terms = [];

if (!is_wp_error($terms) && !empty($terms)) {
    foreach ($terms as $parent_term) {
        // Получаем дочерние термины для каждого родительского термина
        $child_terms = array_merge($child_terms, get_terms([
            'taxonomy' => 'product_kind',
            'hide_empty' => false,
            'parent' => $parent_term->term_id, // Запрашиваем только дочерние термины
        ]));
    }
}



if (!empty($child_terms) && !is_wp_error($child_terms)) : ?>
<div class="divino-product-kind-wrapper wp-block-group alignwide has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
    <nav class="product-kind-menu">
        <ul class="product-kind-menu__list">
            <?php foreach ($child_terms as $term) : 
                $term_link = get_term_link($term);
                $css_class = 'product-kind-' . $term->slug;
            ?>
                <li class="product-kind-menu__item <?php echo esc_attr($css_class); ?>">
                    <a href="<?php echo esc_url($term_link); ?>" 
                       class="product-kind-menu__link">
                        <span class="text"><?php echo esc_html($term->name); ?></span>
                        <div class="product-kind-menu__image product-kind-menu__masked"></div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>