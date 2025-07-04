<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package divino
 * @since 1.0.0
 */

?>
<?php divino_entry_before(); ?>
<article
<?php
		echo wp_kses_post(
			divino_attr(
				'article-page',
				array(
					'id'    => 'post-' . get_the_id(),
					'class' => join( ' ', get_post_class() ),
				)
			)
		);
		?>
>
	<?php divino_entry_top(); ?>

	<?php divino_entry_content_single_page(); ?>

	<?php
		divino_edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'divino' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
		?>

	<?php divino_entry_bottom(); ?>

</article><!-- #post-## -->

<?php divino_entry_after(); ?>
