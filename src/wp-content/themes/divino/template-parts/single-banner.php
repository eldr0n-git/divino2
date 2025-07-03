<?php
/**
 * Template part for displaying single post's entry banner.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 4.0.0
 */

$divino_post_type      = strval( get_post_type() );
$divino_banner_control = 'ast-dynamic-single-' . esc_attr( $divino_post_type );

// If banner will be with empty markup then better to skip it.
if ( false !== strpos( divino_entry_header_class( false ), 'ast-header-without-markup' ) ) {
	return;
}

// Conditionally updating data section & class.
$divino_attr = 'class="ast-single-entry-banner"';
if ( is_customize_preview() ) {
	$divino_attr = 'class="ast-single-entry-banner ast-post-banner-highlight site-header-focus-item" data-section="' . esc_attr( $divino_banner_control ) . '"';
}

$divino_data_attrs = 'data-post-type="' . $divino_post_type . '"';

$divino_layout_type = divino_get_option( $divino_banner_control . '-layout', 'layout-1' );
$divino_data_attrs .= 'data-banner-layout="' . $divino_layout_type . '"';

if ( 'layout-2' === $divino_layout_type && 'custom' === divino_get_option( $divino_banner_control . '-banner-width-type', 'fullwidth' ) ) {
	$divino_data_attrs .= 'data-banner-width-type="custom"';
}

$divino_featured_background = divino_get_option( $divino_banner_control . '-featured-as-background', false );
if ( 'layout-2' === $divino_layout_type && $divino_featured_background ) {
	$divino_data_attrs .= 'data-banner-background-type="featured"';
}

?>
<section <?php echo wp_kses_post( $divino_attr . ' ' . $divino_data_attrs ); ?>>

<div class="ast-container">
		<?php
		if ( is_customize_preview() ) {
			divino_Builder_UI_Controller::render_banner_customizer_edit_button();
		}
			divino_banner_elements_order();
		?>
	</div>
</section>
