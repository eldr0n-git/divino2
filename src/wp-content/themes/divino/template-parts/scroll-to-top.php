<?php
/**
 * Scroll To Top Template
 *
 * @package Astra
 * @since 4.0.0
 */

// Bail early if it is not astra customizer.
if ( is_customize_preview() && ! divino_Customizer::is_divino_customizer() ) {
	return;
}

$divino_addon_scroll_top_alignment = divino_get_option( 'scroll-to-top-icon-position' );
$divino_addon_scroll_top_devices   = divino_get_option( 'scroll-to-top-on-devices' );
?>

<div id="ast-scroll-top" tabindex="0" class="<?php echo esc_attr( apply_filters( 'divino_scroll_top_icon', 'ast-scroll-top-icon' ) ); ?> ast-scroll-to-top-<?php echo esc_attr( $divino_addon_scroll_top_alignment ); ?>" data-on-devices="<?php echo esc_attr( $divino_addon_scroll_top_devices ); ?>">
	<?php
	if ( divino_Icons::is_svg_icons() ) {
		divino_Icons::get_icons( 'arrow', true );
	}
	?>
	<span class="screen-reader-text"><?php esc_html_e( 'Scroll to Top', 'astra' ); ?></span>
</div>
