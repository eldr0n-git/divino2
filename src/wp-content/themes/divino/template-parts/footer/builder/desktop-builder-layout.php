<?php
/**
 * Template part for displaying the footer info.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package divino
 * @since 1.0.0
 */

?>
<footer
<?php
echo wp_kses_post(
	divino_attr(
		'footer',
		array(
			'id'    => 'colophon',
			'class' => join(
				' ',
				divino_get_footer_classes()
			),
		)
	)
);
?>
>
	<?php
		divino_footer_content_top();
	?>
		<?php
		/**
		 * divino Top footer
		 */
		do_action( 'divino_above_footer' );
		/**
		 * divino Middle footer
		 */
		do_action( 'divino_primary_footer' );
		/**
		 * divino Bottom footer
		 */
		do_action( 'divino_below_footer' );
		?>
	<?php
		divino_footer_content_bottom();
	?>
</footer><!-- #colophon -->
