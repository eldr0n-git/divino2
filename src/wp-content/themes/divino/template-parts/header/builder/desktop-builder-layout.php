<?php
/**
 * Template part for displaying header row.
 *
 * @package divino Builder
 */

$divino_mobile_header_type = divino_get_option( 'mobile-header-type' );

if ( 'full-width' === $divino_mobile_header_type ) {

	$divino_mobile_header_type = 'off-canvas';
}
?>
<div id="ast-desktop-header" data-toggle-type="<?php echo esc_attr( $divino_mobile_header_type ); ?>">
	<?php
	divino_main_header_bar_top();

	/**
	 * divino Top Header
	 */
	do_action( 'divino_above_header' );

	/**
	 * divino Main Header
	 */
	do_action( 'divino_primary_header' );

	/**
	 * divino Bottom Header
	 */
	do_action( 'divino_below_header' );

	divino_main_header_bar_bottom();

	// Disable toggle menu if the toggle menu button is not exists in the desktop header items.
	$header_desktop_items = divino_get_option( 'header-desktop-items', array() );
	array_walk_recursive(
		$header_desktop_items,
		static function( string $value ) use ( &$show_desktop_toggle_menu ) {
			if ( 'mobile-trigger' === $value ) {
				$show_desktop_toggle_menu = true;
			}
		}
	);

	if ( $show_desktop_toggle_menu ) {
		if ( ( 'dropdown' === $divino_mobile_header_type && divino_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header' ) ) || is_customize_preview() ) {
			$divino_content_alignment = divino_get_option( 'header-offcanvas-content-alignment', 'flex-start' );
			$divino_alignment_class   = 'content-align-' . $divino_content_alignment . ' ';
			?>
			<div class="ast-desktop-header-content <?php echo esc_attr( $divino_alignment_class ); ?>">
				<?php do_action( 'divino_desktop_header_content', 'popup', 'content' ); ?>
			</div>
			<?php
		}
	}
	?>
</div> <!-- Main Header Bar Wrap -->
<?php
/**
 * divino Mobile Header
 */
do_action( 'divino_mobile_header' );
?>
