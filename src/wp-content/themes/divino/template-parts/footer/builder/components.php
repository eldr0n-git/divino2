<?php
/**
 * Template part for displaying the footer component.
 *
 * @package Astra
 */

$divino_component_slug = get_query_var( 'type' );
if ( divino_wp_version_compare( '5.4.99', '>=' ) ) {
	$divino_component_slug = wp_parse_args( $args, array( 'type' => '' ) );
	$divino_component_slug = isset( $divino_component_slug['type'] ) ? $divino_component_slug['type'] : '';
}

switch ( $divino_component_slug ) {

	case 'copyright':
		?>
			<div class="ast-builder-layout-element ast-flex site-footer-focus-item ast-footer-copyright" data-section="section-footer-builder">
				<?php do_action( 'divino_footer_copyright' ); ?>
			</div>
		<?php
		break;

	case 'social-icons-1':
		?>
			<div class="ast-builder-layout-element ast-flex site-footer-focus-item" data-section="section-fb-social-icons-1">
				<?php do_action( 'divino_footer_social_1' ); ?>
			</div>
		<?php
		break;

	case 'widget-1':
		?>
		<aside
		<?php
		echo wp_kses_post(
			divino_attr(
				'footer-widget-area-inner',
				array(
					'class'        => 'footer-widget-area widget-area site-footer-focus-item',
					'data-section' => 'sidebar-widgets-footer-widget-1',
					'aria-label'   => 'Footer Widget 1',
				)
			)
		);
		?>
				>
			<?php
			divino_markup_open( 'footer-widget-div' );
			divino_get_sidebar( 'footer-widget-1' );
			divino_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php
		break;

	case 'widget-2':
		?>
		<aside
		<?php
		echo wp_kses_post(
			divino_attr(
				'footer-widget-area-inner',
				array(
					'class'        => 'footer-widget-area widget-area site-footer-focus-item',
					'data-section' => 'sidebar-widgets-footer-widget-2',
					'aria-label'   => 'Footer Widget 2',
				)
			)
		);
		?>
		>
			<?php
			divino_markup_open( 'footer-widget-div' );
			divino_get_sidebar( 'footer-widget-2' );
			divino_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php
		break;

	case 'widget-3':
		?>
		<aside
		<?php
		echo wp_kses_post(
			divino_attr(
				'footer-widget-area-inner',
				array(
					'class'        => 'footer-widget-area widget-area site-footer-focus-item',
					'data-section' => 'sidebar-widgets-footer-widget-3',
					'aria-label'   => 'Footer Widget 3',
				)
			)
		);
		?>
		>
			<?php
			divino_markup_open( 'footer-widget-div' );
			divino_get_sidebar( 'footer-widget-3' );
			divino_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php
		break;

	case 'widget-4':
		?>
		<aside
		<?php
		echo wp_kses_post(
			divino_attr(
				'footer-widget-area-inner',
				array(
					'class'        => 'footer-widget-area widget-area site-footer-focus-item',
					'data-section' => 'sidebar-widgets-footer-widget-4',
					'aria-label'   => 'Footer Widget 4',
				)
			)
		);
		?>
		>
			<?php
			divino_markup_open( 'footer-widget-div' );
			divino_get_sidebar( 'footer-widget-4' );
			divino_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php
		break;

	case 'html-1':
		?>
		<div class="footer-widget-area widget-area site-footer-focus-item ast-footer-html-1" data-section="section-fb-html-1">
			<?php do_action( 'divino_footer_html_1' ); ?>
		</div>
		<?php
		break;

	case 'html-2':
		?>
			<div class="footer-widget-area widget-area site-footer-focus-item ast-footer-html-2" data-section="section-fb-html-2">
				<?php do_action( 'divino_footer_html_2' ); ?>
			</div>
			<?php
		break;

	case 'menu':
		?>
			<div class="footer-widget-area widget-area site-footer-focus-item" data-section="section-footer-menu">
				<?php do_action( 'divino_footer_menu' ); ?>
			</div>
			<?php
		break;

	case 'divider-1':
		$divino_fb_divider_layout_class = divino_get_option( 'footer-divider-1-layout' );
		?>
		<div class="footer-widget-area widget-area ast-flex site-footer-focus-item ast-footer-divider-element ast-footer-divider-1 ast-fb-divider-layout-<?php echo esc_attr( $divino_fb_divider_layout_class ); ?>" data-section="section-fb-divider-1">
			<?php do_action( 'divino_footer_divider_1' ); ?>
		</div>
		<?php
		break;

	default:
		do_action( 'divino_render_footer_components', $divino_component_slug );
		break;

}
?>
