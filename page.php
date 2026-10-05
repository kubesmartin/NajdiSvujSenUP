<?php
/**
 * The template for single pages.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content', 'page' );
endwhile;

get_footer();
