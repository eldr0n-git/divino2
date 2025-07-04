<?php
/**
 * Template for Primary Header
 *
 * The header layout 2 for divino Theme. ( No of sections - 1 [ Section 1 limit - 3 )
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

?>

<div class="main-header-bar-wrap">
	<div <?php echo wp_kses_post( divino_attr( 'main-header-bar' ) ); ?>>
		<?php divino_main_header_bar_top(); ?>
		<div class="ast-container">

			<div class="ast-flex main-header-container">
				<?php divino_masthead_content(); ?>
			</div><!-- Main Header Container -->
		</div><!-- ast-row -->
		<?php divino_main_header_bar_bottom(); ?>
	</div> <!-- Main Header Bar -->
</div> <!-- Main Header Bar Wrap -->
