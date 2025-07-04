<?php
/**
 * Template part for displaying the a row of the footer
 *
 * @package divino Builder
 */

$divino_footer_row = get_query_var( 'row' );
if ( divino_wp_version_compare( '5.4.99', '>=' ) ) {
	$divino_footer_row = wp_parse_args( $args, array( 'row' => '' ) );
	$divino_footer_row = isset( $divino_footer_row['row'] ) ? $divino_footer_row['row'] : '';
}

if ( divino_Builder_Helper::is_footer_row_empty( $divino_footer_row ) ) {

	$divino_footer_row_option = 'above' === $divino_footer_row ? 'hba' : ( 'below' === $divino_footer_row ? 'hbb' : 'hb' );
	$divino_footer_columns    = divino_get_option( $divino_footer_row_option . '-footer-column' );
	$divino_footer_layout     = divino_get_option( $divino_footer_row_option . '-footer-layout' );
	$divino_row_stack_layout  = divino_get_option( $divino_footer_row_option . '-stack' );

	$divino_row_desk_layout = isset( $divino_footer_layout['desktop'] ) ? $divino_footer_layout['desktop'] : 'full';
	$divino_tab_layout      = isset( $divino_footer_layout['tablet'] ) ? $divino_footer_layout['tablet'] : 'full';
	$divino_mob_layout      = isset( $divino_footer_layout['mobile'] ) ? $divino_footer_layout['mobile'] : 'full';

	$divino_desk_stack_layout = isset( $divino_row_stack_layout['desktop'] ) ? $divino_row_stack_layout['desktop'] : 'stack';
	$divino_tab_stack_layout  = isset( $divino_row_stack_layout['tablet'] ) ? $divino_row_stack_layout['tablet'] : 'stack';
	$divino_mob_stack_layout  = isset( $divino_row_stack_layout['mobile'] ) ? $divino_row_stack_layout['mobile'] : 'stack';

	$divino_footer_row_classes = array(
		'site-' . esc_attr( $divino_footer_row ) . '-footer-wrap',
		'ast-builder-grid-row-container',
		'site-footer-focus-item',
		'ast-builder-grid-row-' . esc_attr( $divino_row_desk_layout ),
		'ast-builder-grid-row-tablet-' . esc_attr( $divino_tab_layout ),
		'ast-builder-grid-row-mobile-' . esc_attr( $divino_mob_layout ),
		'ast-footer-row-' . esc_attr( $divino_desk_stack_layout ),
		'ast-footer-row-tablet-' . esc_attr( $divino_tab_stack_layout ),
		'ast-footer-row-mobile-' . esc_attr( $divino_mob_stack_layout ),
	);
	?>
<div class="<?php echo esc_attr( implode( ' ', $divino_footer_row_classes ) ); ?>" data-section="section-<?php echo esc_attr( $divino_footer_row ); ?>-footer-builder">
	<div class="ast-builder-grid-row-container-inner">
		<?php
		if ( is_customize_preview() ) {
			divino_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Footer', $divino_footer_row );
		}

		/**
		 * divino Render before Site container of Footer.
		 */
		do_action( "divino_footer_{$divino_footer_row}_container_before" );
		?>
			<div class="ast-builder-footer-grid-columns site-<?php echo esc_attr( $divino_footer_row ); ?>-footer-inner-wrap ast-builder-grid-row">
			<?php for ( $divino_builder_zones = 1; $divino_builder_zones <= divino_Builder_Helper::$num_of_footer_columns; $divino_builder_zones++ ) { ?>
				<?php
				if ( $divino_builder_zones > $divino_footer_columns ) {
					break;
				}
				?>
				<div class="site-footer-<?php echo esc_attr( $divino_footer_row ); ?>-section-<?php echo absint( $divino_builder_zones ); ?> site-footer-section site-footer-section-<?php echo absint( $divino_builder_zones ); ?>">
					<?php do_action( 'divino_render_footer_column', $divino_footer_row, $divino_builder_zones ); ?>
				</div>
			<?php } ?>
			</div>
		<?php
		/**
		 * divino Render before Site container of Footer.
		 */
		do_action( "divino_footer_{$divino_footer_row}_container_after" );
		?>
	</div>

</div>
<?php } ?>
