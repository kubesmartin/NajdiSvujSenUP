<?php
/**
 * Template part for posts in listings.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--summary' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="entry__thumbnail" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'medium_large' ); ?>
		</a>
	<?php endif; ?>

	<header class="entry__header">
		<?php the_title( '<h2 class="entry__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry__meta"><?php najdisvujsen_posted_on(); ?></div>
		<?php endif; ?>
	</header>

	<div class="entry__summary">
		<?php the_excerpt(); ?>
	</div>
</article>
