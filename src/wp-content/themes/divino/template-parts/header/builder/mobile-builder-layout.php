<?php
/**
 * Template part for displaying the Mobile Header
 *
 * @package divino Builder
 */

$divino_mobile_header_type = divino_get_option( 'mobile-header-type' );

if ( 'full-width' === $divino_mobile_header_type ) {

	$divino_mobile_header_type = 'off-canvas';
}

?>
<div id="ast-mobile-header" class="ast-mobile-header-wrap " data-type="<?php echo esc_attr( $divino_mobile_header_type ); ?>">
	<?php
	do_action( 'divino_mobile_header_bar_top' );

	/**
	 * divino Top Header
	 */
	do_action( 'divino_mobile_above_header' );

	/**
	 * divino Main Header
	 */
	do_action( 'divino_mobile_primary_header' );

	/**
	 * divino Mobile Bottom Header
	 */
	do_action( 'divino_mobile_below_header' );

	divino_main_header_bar_bottom();

	// Disable toggle menu if the toggle menu button is not exists in the mobile header items.
	$header_mobile_items = divino_get_option( 'header-mobile-items', array() );
	array_walk_recursive(
		$header_mobile_items,
		static function( string $value ) use ( &$show_mobile_toggle_menu ) {
			if ( 'mobile-trigger' === $value ) {
				$show_mobile_toggle_menu = true;
			}
		}
	);

	if ( $show_mobile_toggle_menu ) {
		if ( ( 'dropdown' === divino_get_option( 'mobile-header-type' ) && divino_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header' ) ) || is_customize_preview() ) {
			$divino_content_alignment = divino_get_option( 'header-offcanvas-content-alignment', 'flex-start' );
			$divino_alignment_class   = 'content-align-' . $divino_content_alignment . ' ';
			?>
			<div class="ast-mobile-header-content <?php echo esc_attr( $divino_alignment_class ); ?>">
				<?php do_action( 'divino_mobile_header_content', 'popup', 'content' ); ?>
			</div>
			<?php
		}
	}
	?>
</div>
