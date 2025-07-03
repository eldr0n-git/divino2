<?php
/**
 * Footer Layout 4
 *
 * @package Astra
 * @since   Astra 1.0.12
 */

/**
 * Hide advanced footer markup if:
 *
 * - User is not logged in. [AND]
 * - All widgets are not active.
 */
if ( ! is_user_logged_in() ) {
	if (
		! is_active_sidebar( 'advanced-footer-widget-1' ) &&
		! is_active_sidebar( 'advanced-footer-widget-2' ) &&
		! is_active_sidebar( 'advanced-footer-widget-3' ) &&
		! is_active_sidebar( 'advanced-footer-widget-4' )
	) {
		return;
	}
}

$divino_footer_classes   = array();
$divino_footer_classes[] = 'footer-adv';
$divino_footer_classes[] = 'footer-adv-layout-4';
$divino_footer_classes   = implode( ' ', $divino_footer_classes );
?>

<div class="<?php echo esc_attr( $divino_footer_classes ); ?>">
	<div class="footer-adv-overlay">
		<div class="ast-container">
			<div class="ast-row">
				<div class="<?php echo wp_kses_post( divino_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-1" <?php echo wp_kses_post( apply_filters( 'divino_sidebar_data_attrs', '', 'advanced-footer-widget-1' ) ); ?>>
					<?php divino_get_footer_widget( 'advanced-footer-widget-1' ); ?>
				</div>
				<div class="<?php echo wp_kses_post( divino_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-2" <?php echo wp_kses_post( apply_filters( 'divino_sidebar_data_attrs', '', 'advanced-footer-widget-2' ) ); ?>>
					<?php divino_get_footer_widget( 'advanced-footer-widget-2' ); ?>
				</div>
				<div class="<?php echo wp_kses_post( divino_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-3" <?php echo wp_kses_post( apply_filters( 'divino_sidebar_data_attrs', '', 'advanced-footer-widget-3' ) ); ?>>
					<?php divino_get_footer_widget( 'advanced-footer-widget-3' ); ?>
				</div>
				<div class="<?php echo wp_kses_post( divino_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-4" <?php echo wp_kses_post( apply_filters( 'divino_sidebar_data_attrs', '', 'advanced-footer-widget-4' ) ); ?>>
					<?php divino_get_footer_widget( 'advanced-footer-widget-4' ); ?>
				</div>
			</div><!-- .ast-row -->
		</div><!-- .ast-container -->
	</div><!-- .footer-adv-overlay-->
</div><!-- .ast-theme-footer .footer-adv-layout-4 -->
