<?php
/**
 * Template part for displaying a row of the mobile header
 *
 * @package divino Builder
 */

$divino_row = get_query_var( 'row' );
if ( divino_wp_version_compare( '5.4.99', '>=' ) ) {
	$divino_row = wp_parse_args( $args, array( 'row' => '' ) );
	$divino_row = isset( $divino_row['row'] ) ? $divino_row['row'] : '';
}

if ( divino_Builder_Helper::is_row_empty( $divino_row, 'header', 'mobile' ) ) {

	$divino_customizer_editor_row        = 'section-' . esc_attr( $divino_row ) . '-header-builder';
	$divino_is_transparent_header_enable = divino_get_option( 'transparent-header-enable' );

	if ( 'primary' === $divino_row && $divino_is_transparent_header_enable ) {
		$divino_customizer_editor_row = 'section-transparent-header';
	}

	$divino_row_label = 'primary' === $divino_row ? 'main' : $divino_row;
	?>
	<div class="ast-<?php echo esc_attr( $divino_row_label ); ?>-header-wrap <?php echo 'primary' === $divino_row ? 'main-header-bar-wrap' : ''; ?>" >
		<div class="<?php echo esc_attr( 'ast-' . $divino_row . '-header-bar ast-' . $divino_row . '-header ' ); ?><?php echo 'primary' === $divino_row ? 'main-header-bar ' : ''; ?>site-<?php echo esc_attr( $divino_row ); ?>-header-wrap site-header-focus-item ast-builder-grid-row-layout-default ast-builder-grid-row-tablet-layout-default ast-builder-grid-row-mobile-layout-default" data-section="<?php echo esc_attr( $divino_customizer_editor_row ); ?>">
				<?php
				if ( is_customize_preview() ) {
					divino_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Header', $divino_row );
				}
				/**
				 * divino Render before Site Content.
				 */
				do_action( "divino_header_{$divino_row}_container_before" );
				?>
					<div class="ast-builder-grid-row <?php echo divino_Builder_Helper::has_mobile_side_columns( $divino_row ) ? 'ast-builder-grid-row-has-sides' : 'ast-grid-center-col-layout-only ast-flex'; ?> <?php echo divino_Builder_Helper::has_mobile_center_column( $divino_row ) ? 'ast-grid-center-col-layout' : 'ast-builder-grid-row-no-center'; ?>">
						<?php if ( divino_Builder_Helper::has_mobile_side_columns( $divino_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $divino_row ); ?>-section-left site-header-section ast-flex site-header-section-left">
								<?php
								/**
								 * divino Render Header Column
								 */
								do_action( 'divino_render_mobile_header_column', $divino_row, 'left' );

								if ( divino_Builder_Helper::has_mobile_center_column( $divino_row ) ) {
									/**
									 * divino Render Header Column
									 */
									do_action( 'divino_render_mobile_header_column', $divino_row, 'left_center' );
								}
								?>
							</div>
						<?php } ?>
						<?php if ( divino_Builder_Helper::has_mobile_center_column( $divino_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $divino_row ); ?>-section-center site-header-section ast-flex ast-grid-section-center">
								<?php
								/**
								 * divino Render Header Column
								 */
								do_action( 'divino_render_mobile_header_column', $divino_row, 'center' );
								?>
							</div>
						<?php } ?>
						<?php if ( divino_Builder_Helper::has_mobile_side_columns( $divino_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $divino_row ); ?>-section-right site-header-section ast-flex ast-grid-right-section">
								<?php
								if ( divino_Builder_Helper::has_mobile_center_column( $divino_row ) ) {
									/**
									 * divino Render Header Column
									 */
									do_action( 'divino_render_mobile_header_column', $divino_row, 'right_center' );
								}
								/**
								 * divino Render Header Column
								 */
								do_action( 'divino_render_mobile_header_column', $divino_row, 'right' );
								?>
							</div>
						<?php } ?>
					</div>
				<?php
				/**
				 * divino Render after Site Content.
				 */
				do_action( "divino_header_{$divino_row}_container_after" );
				?>
		</div>
	</div>
	<?php
}
