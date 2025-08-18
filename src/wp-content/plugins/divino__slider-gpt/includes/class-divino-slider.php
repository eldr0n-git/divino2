<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Divino_Slider_Plugin {

    const CPT = 'divino_slide';
    const NONCE = 'divino_slide_meta_nonce';

    public function __construct() {
        add_action( 'init', array( $this, 'register_cpt' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );

        // Shortcode
        add_shortcode( 'divino_slider', array( $this, 'render_slider' ) );

        // Block
        add_action( 'init', array( $this, 'register_block' ) );
    }

    public function register_cpt() {
        $labels = array(
            'name'               => __( 'Слайды', 'divino-slider' ),
            'singular_name'      => __( 'Слайд', 'divino-slider' ),
            'add_new'            => __( 'Добавить слайд', 'divino-slider' ),
            'add_new_item'       => __( 'Добавить новый слайд', 'divino-slider' ),
            'edit_item'          => __( 'Редактировать слайд', 'divino-slider' ),
            'new_item'           => __( 'Новый слайд', 'divino-slider' ),
            'view_item'          => __( 'Просмотр слайда', 'divino-slider' ),
            'search_items'       => __( 'Найти слайды', 'divino-slider' ),
            'not_found'          => __( 'Слайды не найдены', 'divino-slider' ),
            'not_found_in_trash' => __( 'В корзине слайдов нет', 'divino-slider' ),
            'menu_name'          => __( 'Divino Slider', 'divino-slider' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-images-alt2',
            'supports'           => array( 'title', 'thumbnail', 'page-attributes' ), // title + featured image + order
            'has_archive'        => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
        );
        register_post_type( self::CPT, $args );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'divino_slide_details',
            __( 'Параметры слайда', 'divino-slider' ),
            array( $this, 'render_meta_box' ),
            self::CPT,
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( self::NONCE, self::NONCE );

        $desc          = get_post_meta( $post->ID, '_divino_desc', true );
        $btn_text      = get_post_meta( $post->ID, '_divino_btn_text', true );
        $btn_url       = get_post_meta( $post->ID, '_divino_btn_url', true );
        $slide_style   = get_post_meta( $post->ID, '_divino_slide_style', true );
        $title_style   = get_post_meta( $post->ID, '_divino_title_style', true );
        $desc_style    = get_post_meta( $post->ID, '_divino_desc_style', true );
        $btn_style     = get_post_meta( $post->ID, '_divino_btn_style', true );
        $duration      = get_post_meta( $post->ID, '_divino_duration', true );
        if ( $duration === '' ) { $duration = '4000'; }


        echo '<p><label for="divino_desc"><strong>' . __( 'Описание', 'divino-slider' ) . '</strong></label><br/>';
        echo '<textarea id="divino_desc" name="divino_desc" rows="3" style="width:100%;">' . esc_textarea( $desc ) . '</textarea></p>';

        echo '<p><label for="divino_btn_text"><strong>' . __( 'Текст кнопки', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="text" id="divino_btn_text" name="divino_btn_text" value="' . esc_attr( $btn_text ) . '" style="width:100%;" /></p>';

        echo '<p><label for="divino_btn_url"><strong>' . __( 'Ссылка кнопки', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="url" id="divino_btn_url" name="divino_btn_url" value="' . esc_attr( $btn_url ) . '" style="width:100%;" placeholder="https://..." /></p>';

        echo '<hr/>';

        echo '<p><label for="divino_duration"><strong>' . __( 'Время показа слайда (мс)', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="number" id="divino_duration" min="500" step="100" name="divino_duration" value="' . esc_attr( $duration ) . '" /></p>';

        echo '<hr/>';

        echo '<p><label for="divino_slide_style"><strong>' . __( 'Inline-стили контейнера слайда', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="text" id="divino_slide_style" name="divino_slide_style" value="' . esc_attr( $slide_style ) . '" style="width:100%;" placeholder="background-position:center; color:#fff;" /></p>';

        echo '<p><label for="divino_title_style"><strong>' . __( 'Inline-стили заголовка', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="text" id="divino_title_style" name="divino_title_style" value="' . esc_attr( $title_style ) . '" style="width:100%;" placeholder="font-size:42px; text-shadow:0 2px 6px rgba(0,0,0,.4);" /></p>';

        echo '<p><label for="divino_desc_style"><strong>' . __( 'Inline-стили описания', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="text" id="divino_desc_style" name="divino_desc_style" value="' . esc_attr( $desc_style ) . '" style="width:100%;" /></p>';

        echo '<p><label for="divino_btn_style"><strong>' . __( 'Inline-стили кнопки', 'divino-slider' ) . '</strong></label><br/>';
        echo '<input type="text" id="divino_btn_style" name="divino_btn_style" value="' . esc_attr( $btn_style ) . '" style="width:100%;" placeholder="padding:12px 20px; border-radius:8px;" /></p>';
    }

    public function save_post( $post_id ) {
        if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( $_POST[ self::NONCE ], self::NONCE ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
        if ( isset( $_POST['post_type'] ) && self::CPT === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
        }

        $map = array(
            '_divino_desc'         => array( 'key' => 'divino_desc', 'sanitize' => 'wp_kses_post' ),
            '_divino_btn_text'     => array( 'key' => 'divino_btn_text', 'sanitize' => 'sanitize_text_field' ),
            '_divino_btn_url'      => array( 'key' => 'divino_btn_url', 'sanitize' => 'esc_url_raw' ),
            '_divino_slide_style'  => array( 'key' => 'divino_slide_style', 'sanitize' => 'sanitize_text_field' ),
            '_divino_title_style'  => array( 'key' => 'divino_title_style', 'sanitize' => 'sanitize_text_field' ),
            '_divino_desc_style'   => array( 'key' => 'divino_desc_style', 'sanitize' => 'sanitize_text_field' ),
            '_divino_btn_style'    => array( 'key' => 'divino_btn_style', 'sanitize' => 'sanitize_text_field' ),
            '_divino_duration'     => array( 'key' => 'divino_duration', 'sanitize' => 'absint' ),
        );

        foreach ( $map as $meta_key => $cfg ) {
            if ( isset( $_POST[ $cfg['key'] ] ) ) {
                $val = $_POST[ $cfg['key'] ];
                switch ( $cfg['sanitize'] ) {
                    case 'wp_kses_post':
                        $val = wp_kses_post( $val );
                        break;
                    case 'sanitize_text_field':
                        $val = sanitize_text_field( $val );
                        break;
                    case 'esc_url_raw':
                        $val = esc_url_raw( $val );
                        break;
                    case 'absint':
                        $val = absint( $val );
                        break;
                }
                update_post_meta( $post_id, $meta_key, $val );
            }
        }
    }

    public function admin_enqueue( $hook ) {
        // Could enqueue admin-specific CSS/JS if needed
    }

    public function frontend_enqueue() {
        // Swiper assets (from CDN for simplicity)
        wp_enqueue_style( 'divino-swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
        wp_enqueue_script( 'divino-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );
        wp_enqueue_script( 'divino-slider-init', DIVINO_SLIDER_URL . 'assets/js/slider-init.js', array( 'divino-swiper-js' ), '1.0.0', true );
    }

    /**
     * Query slides and render HTML (used by shortcode and block render)
     */
    public function render_slider( $atts = array() ) {
        $atts = shortcode_atts( array(
            'limit' => -1, // all
        ), $atts, 'divino_slider' );

        $q = new WP_Query( array(
            'post_type'      => self::CPT,
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
            'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
        ) );

        if ( ! $q->have_posts() ) {
            return '<div class="divino-slider-empty">Слайды не найдены.</div>';
        }

        ob_start();
        ?>
        <div class="divino-slider-wrapper wp-block-group alignwide has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
            <div class="swiper divino-swiper ">
                <div class="swiper-wrapper wp-block-group alignwide is-content-justification-space-between is-nowrap wp-container-core-group-is-layout-8165f36a wp-block-group-is-layout-flex">
                    <?php
                    while ( $q->have_posts() ) : $q->the_post();
                        $post_id = get_the_ID();
                        $title   = get_the_title();
                        $image   = get_the_post_thumbnail_url( $post_id, 'full' );
                        $desc    = get_post_meta( $post_id, '_divino_desc', true );
                        $btn_text= get_post_meta( $post_id, '_divino_btn_text', true );
                        $btn_url = get_post_meta( $post_id, '_divino_btn_url', true );
                        $s_style = get_post_meta( $post_id, '_divino_slide_style', true );
                        $t_style = get_post_meta( $post_id, '_divino_title_style', true );
                        $d_style = get_post_meta( $post_id, '_divino_desc_style', true );
                        $b_style = get_post_meta( $post_id, '_divino_btn_style', true );
                        $dur     = get_post_meta( $post_id, '_divino_duration', true );
                        if ( empty( $dur ) ) { $dur = 4000; }

                        // Fallback if no image
                        if ( ! $image ) {
                            $image = 'https://via.placeholder.com/1600x600?text=Divino+Slide';
                        }
                        ?>
                        <div class="swiper-slide" data-swiper-autoplay="<?php echo esc_attr( $dur ); ?>">
                            <div class="divino-slide" style="background-image:url('<?php echo esc_url( $image ); ?>'); <?php echo esc_attr( $s_style ); ?>">
                                <div class="divino-slide-inner">
                                    <?php if ( $title ) : ?>
                                        <h2 class="divino-slide-title" style="<?php echo esc_attr( $t_style ); ?>"><?php echo esc_html( $title ); ?></h2>
                                    <?php endif; ?>

                                    <?php if ( $desc ) : ?>
                                        <div class="divino-slide-desc" style="<?php echo esc_attr( $d_style ); ?>">
                                            <?php echo wp_kses_post( wpautop( $desc ) ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $btn_text && $btn_url ) : ?>
                                        <p class="divino-slide-actions">
                                            <a class="divino-slide-btn" href="<?php echo esc_url( $btn_url ); ?>" style="<?php echo esc_attr( $b_style ); ?>">
                                                <?php echo esc_html( $btn_text ); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <style>
            .swiper-slide {
                width: 100% !important;
            }
            .divino-slider-wrapper { position: relative; margin: 30px 0 !important; }
            .divino-swiper { width: 100%; height: 60vh; min-height: 320px; border-radius: 30px;}
            .divino-slide {
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: center;
                justify-content: center;
                /* padding: 40px; */
                border-radius: 32px;
            }
            .divino-slide-inner {
                height: 100%;
                width: 100%;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
            }
            .divino-slide-title { margin: 0 0 10px; }
            .divino-slide-desc { margin: 0 auto 20px; max-width: 820px; }
            .divino-slide-btn { text-decoration: none; display: inline-block; }
        </style>
        <?php
        return ob_get_clean();
    }

    public function register_block() {
        // Register the block from block.json
        register_block_type( DIVINO_SLIDER_PATH . 'block', array(
            'render_callback' => array( $this, 'block_render_callback' ),
        ) );
    }

    public function block_render_callback( $attributes, $content, $block ) {
        return $this->render_slider();
    }
}
