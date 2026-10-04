<?php
/**
 * Template part for pages.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry__header">
		<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>
	</header>

	<div class="entry__content">
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
</article>
