<?php
/**
 * Template part for displaying posts.
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
				'article-blog',
				array(
					'id'    => 'post-' . get_the_id(),
					'class' => join( ' ', get_post_class() ),
				)
			)
		);
		?>
>
	<?php divino_entry_top(); ?>
	<?php divino_entry_content_blog(); ?>
	<?php divino_entry_bottom(); ?>
</article><!-- #post-## -->
<?php divino_entry_after(); ?>
