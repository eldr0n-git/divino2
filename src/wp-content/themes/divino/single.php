<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

		<?php divino_primary_content_top(); ?>

		<?php divino_content_loop(); ?>

		<?php divino_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( divino_page_layout() === 'right-sidebar' ) { ?>

	<?php get_sidebar(); ?>

<?php } ?>

<?php get_footer(); ?>
