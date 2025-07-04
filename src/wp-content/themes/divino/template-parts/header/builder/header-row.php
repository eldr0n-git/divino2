<?php
/**
 * Template part for displaying the a row of the header
 *
 * @package divino Builder
 */

$divino_header_row = get_query_var( 'row' );
if ( divino_wp_version_compare( '5.4.99', '>=' ) ) {
	$divino_header_row = wp_parse_args( $args, array( 'row' => '' ) );
	$divino_header_row = isset( $divino_header_row['row'] ) ? $divino_header_row['row'] : '';
}

if ( divino_Builder_Helper::is_row_empty( $divino_header_row, 'header', 'desktop' ) ) {

	$divino_customizer_editor_row = 'section-' . esc_attr( $divino_header_row ) . '-header-builder';

	$divino_row_label = 'primary' === $divino_header_row ? 'main' : $divino_header_row;

	?>
	<div class="ast-<?php echo esc_attr( $divino_row_label ); ?>-header-wrap <?php echo 'primary' === $divino_header_row ? 'main-header-bar-wrap' : ''; ?> ">
		<div class="<?php echo esc_attr( 'ast-' . $divino_header_row . '-header-bar ast-' . $divino_header_row . '-header' ); ?> <?php echo 'primary' === $divino_header_row ? 'main-header-bar' : ''; ?> site-header-focus-item" data-section="<?php echo esc_attr( $divino_customizer_editor_row ); ?>">
			<?php
			if ( is_customize_preview() ) {
				divino_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Header', $divino_header_row );
			}
			/**
			 * divino Render before Site Content.
			 */
			do_action( "divino_header_{$divino_header_row}_container_before" );
			?>
			<div class="site-<?php echo esc_attr( $divino_header_row ); ?>-header-wrap ast-builder-grid-row-container site-header-focus-item ast-container" data-section="<?php echo esc_attr( $divino_customizer_editor_row ); ?>">
				<div class="ast-builder-grid-row <?php echo divino_Builder_Helper::has_side_columns( $divino_header_row ) ? 'ast-builder-grid-row-has-sides' : 'ast-grid-center-col-layout-only ast-flex'; ?> <?php echo divino_Builder_Helper::has_center_column( $divino_header_row ) ? 'ast-grid-center-col-layout' : 'ast-builder-grid-row-no-center'; ?>">
					<?php if ( divino_Builder_Helper::has_side_columns( $divino_header_row ) ) { ?>
						<div class="site-header-<?php echo esc_attr( $divino_header_row ); ?>-section-left site-header-section ast-flex site-header-section-left">
							<?php
								/**
								 * divino Render Header Column
								 */
								do_action( 'divino_render_header_column', $divino_header_row, 'left' );
							if ( divino_Builder_Helper::has_center_column( $divino_header_row ) ) {
								?>
										<div class="site-header-<?php echo esc_attr( $divino_header_row ); ?>-section-left-center site-header-section ast-flex ast-grid-left-center-section">
									<?php
									/**
									 * divino Render Header Column
									 */
									do_action( 'divino_render_header_column', $divino_header_row, 'left_center' );
									?>
										</div>
									<?php
							}
							?>
						</div>
					<?php } ?>
						<?php if ( divino_Builder_Helper::has_center_column( $divino_header_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $divino_header_row ); ?>-section-center site-header-section ast-flex ast-grid-section-center">
								<?php
								/**
								 * divino Render Header Column
								 */
								do_action( 'divino_render_header_column', $divino_header_row, 'center' );
								?>
							</div>
						<?php } ?>
						<?php if ( divino_Builder_Helper::has_side_columns( $divino_header_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $divino_header_row ); ?>-section-right site-header-section ast-flex ast-grid-right-section">
								<?php
								if ( divino_Builder_Helper::has_center_column( $divino_header_row ) ) {
									?>
									<div class="site-header-<?php echo esc_attr( $divino_header_row ); ?>-section-right-center site-header-section ast-flex ast-grid-right-center-section">
										<?php
										/**
										 * divino Render Header Column
										 */
										do_action( 'divino_render_header_column', $divino_header_row, 'right_center' );
										?>
									</div>
									<?php
								}
								/**
								 * divino Render Header Column
								 */
								do_action( 'divino_render_header_column', $divino_header_row, 'right' );
								?>
							</div>
						<?php } ?>
						</div>
					</div>
					<?php
					/**
					 * divino Render after Site Content.
					 */
					do_action( "divino_header_{$divino_header_row}_container_after" );
					?>
			</div>
			</div>
	<?php
}
