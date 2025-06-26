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
<div class="wp-block-group alignfull">
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
		<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
			<!-- wp:site-title {"level":0} /-->

            <div class="search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <label>
                        <span class="screen-reader-text">Поиск по названию:</span>
                        <input type="search" class="search-field" placeholder="Поиск по названию" value="<?php echo get_search_query(); ?>" name="s" />
                        <input type="hidden" name="post_type" value="product" />
                    </label>
                    <button type="submit" class="search-submit">🔍</button>
                </form>
            </div>




			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
			<div class="wp-block-group">
				<!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
        <div class="cat_section">
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggle = document.querySelector('.catalog-toggle');
                const menu = document.querySelector('.catalog-menu');

                toggle?.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
                });

                document.addEventListener('click', () => {
                menu.style.display = 'none';
                });
            });
            </script>
            <div class="catalog-dropdown">
                <button class="button button--primary catalog-toggle"><span class="button__text">Каталог</span></button>

                <ul class="catalog-menu">
                    <?php
                    $terms = get_terms([
                        'taxonomy' => 'product_cat',
                        'hide_empty' => false,
                        'parent' => 0,
                    ]);


                    foreach ($terms as $term) {
                        $link = get_term_link($term);
                        echo '<li><a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
