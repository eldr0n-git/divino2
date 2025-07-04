<?php
/**
 * Template for Small Footer Layout 1
 *
 * @package     divino
 * @link        https://wpdivino.com/
 * @since       divino 1.0.0
 */

$divino_footer_section_1 = divino_get_small_footer( 'footer-sml-section-1' );
$divino_footer_section_2 = divino_get_small_footer( 'footer-sml-section-2' );

?>

<div class="ast-small-footer footer-sml-layout-1">
	<div class="ast-footer-overlay">
		<div class="ast-container">
			<div class="ast-small-footer-wrap" >
				<?php if ( $divino_footer_section_1 ) { ?>
					<div class="ast-small-footer-section ast-small-footer-section-1" >
						<?php
							echo $divino_footer_section_1; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
				<?php } ?>

				<?php if ( $divino_footer_section_2 ) { ?>
					<div class="ast-small-footer-section ast-small-footer-section-2" >
						<?php
							echo $divino_footer_section_2; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>
				<?php } ?>

			</div><!-- .ast-row .ast-small-footer-wrap -->
		</div><!-- .ast-container -->
	</div><!-- .ast-footer-overlay -->
</div><!-- .ast-small-footer-->
