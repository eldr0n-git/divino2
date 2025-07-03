<?php
/**
 * The header for Astra Theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php divino_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php divino_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if ( apply_filters( 'divino_header_profile_gmpg_link', true ) ) {
	?>
	<link rel="profile" href="https://gmpg.org/xfn/11"> 
	<?php
}
?>
<?php wp_head(); ?>
<?php divino_head_bottom(); ?>
</head>

<body <?php divino_schema_body(); ?> <?php body_class(); ?>>
<?php divino_body_top(); ?>
<?php wp_body_open(); ?>

<a
	class="skip-link screen-reader-text"
	href="#content"
	title="<?php echo esc_attr( divino_default_strings( 'string-header-skip-link', false ) ); ?>">
		<?php echo esc_html( divino_default_strings( 'string-header-skip-link', false ) ); ?>
</a>

<div
<?php
	echo wp_kses_post(
		divino_attr(
			'site',
			array(
				'id'    => 'page',
				'class' => 'hfeed site',
			)
		)
	);
	?>
>
	<?php
	divino_header_before();

	divino_header();

	divino_header_after();

	divino_content_before();
	?>
	<div id="content" class="site-content">
		<div class="ast-container">
		<?php divino_content_top(); ?>
