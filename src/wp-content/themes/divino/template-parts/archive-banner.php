<?php
/**
 * Template part for displaying archive post's entry banner.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 4.0.0
 */

$divino_post_type      = ! empty( $args ) && ! empty( $args['post_type'] ) ? $args['post_type'] : divino_get_post_type();
$divino_banner_control = 'ast-dynamic-archive-' . esc_attr( $divino_post_type );

// If description is the only meta available in structure & its blank then no need to render banner markup.
$divino_archive_structure       = divino_get_option( $divino_banner_control . '-structure', array( $divino_banner_control . '-title', $divino_banner_control . '-description' ) );
$divino_get_archive_description = divino_get_archive_description( $divino_post_type );
if ( 1 === count( $divino_archive_structure ) && in_array( $divino_banner_control . '-description', $divino_archive_structure ) && empty( $divino_get_archive_description ) ) {
	return;
}

// Conditionally updating data section & class.
$divino_attr = 'class="ast-archive-entry-banner"';
if ( is_customize_preview() ) {
	$divino_attr = 'class="ast-archive-entry-banner ast-post-banner-highlight site-header-focus-item" data-section="' . esc_attr( $divino_banner_control ) . '"';
}

$divino_layout_type = divino_get_option( $divino_banner_control . '-layout' );
$divino_data_attrs  = 'data-post-type="' . $divino_post_type . '" data-banner-layout="' . $divino_layout_type . '"';

if ( 'layout-2' === $divino_layout_type && 'custom' === divino_get_option( $divino_banner_control . '-banner-width-type', 'fullwidth' ) ) {
	$divino_data_attrs .= 'data-banner-width-type="custom"';
}

$divino_background_type = divino_get_option( $divino_banner_control . '-banner-image-type', 'none' );
if ( 'layout-2' === $divino_layout_type && 'none' !== $divino_background_type ) {
	$divino_data_attrs .= 'data-banner-background-type="' . $divino_background_type . '"';
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
