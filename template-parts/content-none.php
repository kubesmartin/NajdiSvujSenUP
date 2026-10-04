<?php
/**
 * Template part shown when no posts are found.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

?>
<section class="no-results">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nic nenalezeno', 'najdisvujsen' ); ?></h1>
	</header>

	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'Hledanému výrazu nic neodpovídá. Zkuste jiná slova.', 'najdisvujsen' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Zatím zde není žádný obsah.', 'najdisvujsen' ); ?></p>
	<?php endif; ?>
</section>
