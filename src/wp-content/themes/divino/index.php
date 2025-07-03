<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>
<?php if ( divino_page_layout() === 'left-sidebar' ) { ?>

	<?php get_sidebar(); ?>

<?php } ?>
	<div id="primary" <?php divino_primary_class(); ?>>
		<?php
		divino_primary_content_top();

		divino_content_loop();

		divino_pagination();

		divino_primary_content_bottom();
		?>
	</div><!-- #primary -->
<?php
if ( divino_page_layout() === 'right-sidebar' ) {

	get_sidebar();

}

get_footer();
