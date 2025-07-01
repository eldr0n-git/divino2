<?php
/**
 * Title: Header
 * Slug: divino/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Site header with site title and navigation.
 *
 * @package WordPress
 * @subpackage divino25
 * @since divino 1.0
 */

?>
<!-- wp:group {"align":"full","layout":{"type":"default"}} -->
<div class="header__cnt wp-block-group alignfull">
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
		<div class="title_section wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
			<!-- wp:site-title {"level":0} /-->

            <div class="search">
                <form role="search__form" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <label>
                        <span class="screen-reader-text">Поиск по названию:</span>
                        <input type="search" class="search__input" placeholder="Поиск по названию" value="<?php echo get_search_query(); ?>" name="s" />
                        <input type="hidden" name="post_type" value="product" />
                    </label>
                    <button type="submit" class="search__submit">🔍</button>
                </form>
            </div>


            <div class="mainNav">
                <ul class="mainNav__cnt">
                    <!-- <li class="mainNav__item">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="mainNav__link">Главная</a>
                    </li> -->
                    <li class="mainNav__item">
                        <a href="<?php echo esc_url(home_url('/?page_id=46')); ?>" class="mainNav__link mainNav__link--geo">
                            <span class="mainNav__linktext">Где купить</span>
                        </a>
                    </li>
                    <li class="mainNav__item">
                        <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : esc_url(home_url('/cart/')); ?>" class="mainNav__link mainNav__link--contact">
                            <span class="mainNav__linktext">Задать вопрос</span>
                        </a>
                    </li>
                    <li class="mainNav__item">
                        <a href="<?php echo class_exists('WooCommerce') && function_exists('wc_get_account_url') ? esc_url(wc_get_account_url()) : esc_url(home_url('/my-account/')); ?>" class="mainNav__link mainNav__link--account">
                            <span class="mainNav__linktext">Аккаунт</span>
                        </a>
                    </li>
                </ul>
            </div>

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
        <div class="cat_section wp-block-group alignwide is-content-justification-space-between is-nowrap is-layout-flex wp-container-core-group-is-layout-8165f36a wp-block-group-is-layout-flex">
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggle = document.querySelector('.catalog-toggle');
                const menu = document.querySelector('.megamenu');
                const veil = document.querySelector('.actionMegamenuVeil');

                toggle?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.style.display = (menu.style.display === 'flex') ? 'none' : 'flex';
                    veil.style.display = (veil.style.display === 'flex') ? 'none' : 'flex';
                    toggle.classList.toggle('active');
                });

                document.addEventListener('click', () => {
                    menu.style.display = 'none';
                    veil.style.display = 'none';
                    toggle.classList.remove('active');
                });
            });
            </script>
            <div class="catalog-dropdown">
                <button class="button button--primary catalog-toggle"><span class="button__text">Каталог</span></button>


            </div>
            <div class="cat_menu">
                <ul class="cat_menu__cnt">
                    <?php
                    $terms = get_terms([
                        'taxonomy' => 'product_kind',
                        'hide_empty' => false,
                        'parent' => 0, // Сначала получаем родительские категории
                    ]);

                    if (!is_wp_error($terms) && !empty($terms)) {
                        foreach ($terms as $parent_term) {
                            // Получаем дочерние категории для каждой родительской
                            $child_terms = get_terms([
                                'taxonomy' => 'product_kind',
                                'hide_empty' => false,
                                'parent' => $parent_term->term_id,
                            ]);

                            if (!is_wp_error($child_terms) && !empty($child_terms)) {
                                foreach ($child_terms as $child_term) {
                                    $link = get_term_link($child_term);
                                    if (!is_wp_error($link)) {
                                        echo '<li class="cat_menu__item"><a href="' . esc_url($link) . '" class="cat_menu__link">' . esc_html($child_term->name) . '</a></li>';
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="basket-toggle">
                <div class="basket-toggle__cnt">
                    <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : esc_url(home_url('/cart/')); ?>" class="basket-toggle__link">
                        <span class="basket-toggle__icon"></span>
                        <span class="basket-toggle__label">Корзина</span>
                        <span class="basket-toggle__count" data-count="<?php echo WC()->cart->get_cart_contents_count(); ?>"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="megamenu">
            <div class="megamenu__cnt">
                <div class="catalog-menu">
                <?php
                    function display_product_kind_terms($parent = 0)
                    {
                        $terms = get_terms([
                            'taxonomy' => 'product_kind',
                            'hide_empty' => false,
                            'parent' => $parent,
                        ]);

                        if (!empty($terms)) {
                            echo '<ul>';
                            foreach ($terms as $term) {
                                $link = get_term_link($term);
                                echo '<li><a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a>';

                                // Рекурсивный вызов для подкатегорий
                                display_product_kind_terms($term->term_id);

                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                    }

                    // Выводим всё дерево с корневых категорий
                    display_product_kind_terms();
                    ?>
                </div>
            </div>
        </div>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
 <div class="veil actionMegamenuVeil"></div>

 <?php product_kind_breadcrumb(); ?>
