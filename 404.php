<?php
/**
 * The template for 404 pages.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

get_header();
?>

<div class="container">
	<section class="error-404">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Stránka nenalezena', 'najdisvujsen' ); ?></h1>
		</header>
		<p><?php esc_html_e( 'Požadovaná stránka neexistuje. Zkuste vyhledávání.', 'najdisvujsen' ); ?></p>
		<?php get_search_form(); ?>
	</section>
</div>

<?php
get_footer();
