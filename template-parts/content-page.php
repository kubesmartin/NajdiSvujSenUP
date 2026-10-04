<?php
/**
 * Template part for pages.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php get_template_part( 'template-parts/page-hero' ); ?>

	<div class="container">
		<div class="prose entry__content">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<nav class="page-links">',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</div>
</article>
