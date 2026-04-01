<!-- header.php from divino25 theme  patterns-->
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

            <div class="search-block">
                <form role="search__form" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <label>
                        <span class="screen-reader-text">Поиск по названию:</span>
                        <input type="search" class="search__input" placeholder="Поиск по названию" value="<?php echo get_search_query(); ?>" name="s" />
                        <input type="hidden" name="post_type" value="product" />
                    </label>
                    <button type="submit" class="search__submit">🔍</button>
                </form>
            </div>


            <!-- Burger Menu Toggle Button -->
            <button class="burger-menu-toggle" aria-label="Открыть меню">
                <span class="burger-menu-toggle__line"></span>
                <span class="burger-menu-toggle__line"></span>
                <span class="burger-menu-toggle__line"></span>
            </button>

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
                        <a href="<?php echo esc_url(home_url('/?page_id=553')); ?>" class="mainNav__link mainNav__link--contact">
                            <span class="mainNav__linktext">Задать вопрос</span>
                        </a>
                    </li>
                    <li class="mainNav__item">
                    <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('myaccount')) : esc_url(home_url('/my-account/')); ?>" class="mainNav__link mainNav__link--account">
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
                    menu.style.visibility = (menu.style.visibility === 'visible') ? 'hidden' : 'visible';
                    veil.style.visibility = (veil.style.visibility === 'visible') ? 'hidden' : 'visible';
                    toggle.classList.toggle('active');
                    menu.style.opacity = (menu.style.opacity === '1') ? '0' : '1';
                    veil.style.opacity = (veil.style.opacity === '1') ? '0' : '1';

                    menu.classList.toggle('megamenu--active');
                });

                document.addEventListener('click', () => {
                    menu.style.visibility = 'hidden';
                    veil.style.visibility = 'hidden';
                    menu.style.opacity = 0;
                    veil.style.opacity = 0;
                    toggle.classList.remove('active');
                    menu.classList.remove('megamenu--active'); // Reset top position to default
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
                                $counterx = 0;
                                foreach ($child_terms as $child_term) {
                                    if ( $counterx < 2 ) {
                                         $link = get_term_link($child_term);
                                    if (!is_wp_error($link)) {
                                        echo '<li class="cat_menu__item"><a href="' . esc_url($link) . '" class="cat_menu__link">' . esc_html($child_term->name) . '</a></li>';
                                    }
                                    $counterx++;
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
                        <span class="basket-toggle__count" data-count="<?php echo divino_get_cart_contents_count(); ?>"><?php echo divino_get_cart_contents_count(); ?></span>
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

<!-- Burger Menu Overlay -->
<div class="burger-menu-overlay" id="burgerMenuOverlay">
    <div class="burger-menu__close-btn" id="burgerMenuClose">
        <span></span>
    </div>
    <nav class="burger-menu__nav">
        <ul class="burger-menu__list">
            <!-- Main Navigation Links -->
            <li class="burger-menu__item">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="burger-menu__link">
                    <span class="burger-menu__link-text">Главная</span>
                </a>
            </li>

            <!-- Product Kind Menu Links -->
            <li class="burger-menu__item burger-menu__item--heading">
                <span class="burger-menu__heading">Каталог</span>
            </li>
            <?php
            $product_kind_terms = get_terms([
                'taxonomy' => 'product_kind',
                'hide_empty' => false,
                'parent' => 0,
            ]);

            if (!is_wp_error($product_kind_terms) && !empty($product_kind_terms)) {
                foreach ($product_kind_terms as $parent_term) {
                    $child_terms = get_terms([
                        'taxonomy' => 'product_kind',
                        'hide_empty' => false,
                        'parent' => $parent_term->term_id,
                    ]);

                    if (!is_wp_error($child_terms) && !empty($child_terms)) {
                        foreach ($child_terms as $child_term) {
                            $link = get_term_link($child_term);
                            if (!is_wp_error($link)) {
                                echo '<li class="burger-menu__item burger-menu__item--child">';
                                echo '<a href="' . esc_url($link) . '" class="burger-menu__link">';
                                echo '<span class="burger-menu__link-text">' . esc_html($child_term->name) . '</span>';
                                echo '</a>';
                                echo '</li>';
                            }
                        }
                    }
                }
            }
            ?>

            <!-- User Account Section -->
            <li class="burger-menu__item burger-menu__item--heading">
                <span class="burger-menu__heading">Аккаунт</span>
            </li>
            <?php if (is_user_logged_in()) : ?>
                <li class="burger-menu__item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="burger-menu__link">
                        <span class="burger-menu__link-text">Мой аккаунт</span>
                    </a>
                </li>
                <li class="burger-menu__item">
                    <a href="<?php echo esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>" class="burger-menu__link">
                        <span class="burger-menu__link-text">Мои заказы</span>
                    </a>
                </li>
                <li class="burger-menu__item">
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="burger-menu__link">
                        <span class="burger-menu__link-text">Выйти</span>
                    </a>
                </li>
            <?php else : ?>
                <li class="burger-menu__item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="burger-menu__link">
                        <span class="burger-menu__link-text">Войти / Регистрация</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Shopping Cart Link (under Account section) -->
            <li class="burger-menu__item">
                <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : esc_url(home_url('/cart/')); ?>" class="burger-menu__link">
                    <span class="burger-menu__link-text">Корзина</span>
                </a>
            </li>

            <!-- Additional Links from mainNav -->
            <li class="burger-menu__item burger-menu__item--heading">
                <span class="burger-menu__heading">Информация</span>
            </li>
            <li class="burger-menu__item">
                <a href="<?php echo esc_url(home_url('/?page_id=46')); ?>" class="burger-menu__link">
                    <span class="burger-menu__link-text">Где купить</span>
                </a>
            </li>
            <li class="burger-menu__item">
                <a href="<?php echo esc_url(home_url('/?page_id=553')); ?>" class="burger-menu__link">
                    <span class="burger-menu__link-text">Задать вопрос</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

 <div class="veil actionMegamenuVeil"></div>

 <?php product_kind_breadcrumb(); ?>
