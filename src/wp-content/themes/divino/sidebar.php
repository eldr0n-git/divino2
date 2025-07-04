<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package divino
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$divino_sidebar = apply_filters( 'divino_get_sidebar', 'sidebar-1' );

echo '<div ';
	echo wp_kses_post(
		divino_attr(
			'sidebar',
			array(
				'id'    => 'secondary',
				'class' => join( ' ', divino_get_secondary_class() ),
			)
		)
	);
	echo '>';
	?>

	<div class="sidebar-main" <?php /** @psalm-suppress TooManyArguments */ echo apply_filters( 'divino_sidebar_data_attrs', '', $divino_sidebar ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Generic.Commenting.DocComment.MissingShort ?>>
		<?php divino_sidebars_before(); ?>

		<?php

		if ( is_active_sidebar( $divino_sidebar ) ) {
				dynamic_sidebar( $divino_sidebar );
		}

		divino_sidebars_after();
		?>

	</div><!-- .sidebar-main -->
</div><!-- #secondary -->
