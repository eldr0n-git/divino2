<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Divino
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php divino_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php
	divino_content_after();

	divino_footer_before();

	divino_footer();

	divino_footer_after();
?>
	</div><!-- #page -->
<?php
	divino_body_bottom();
	wp_footer();
?>
	</body>
</html>
