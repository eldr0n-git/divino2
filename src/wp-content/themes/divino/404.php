<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Divino
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

		<?php divino_primary_content_top(); ?>

		<?php divino_404_content_template(); ?>		

		<?php divino_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( divino_page_layout() === 'right-sidebar' ) { ?>

	<?php get_sidebar(); ?>

<?php } ?>

<?php get_footer(); ?>
