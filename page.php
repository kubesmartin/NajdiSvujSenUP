<?php
/**
 * The template for single pages.
 *
 * Study program pages get their own layout; other pages show plain content.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( najdisvujsen_is_program() ) {
		get_template_part( 'template-parts/program/program' );
	} else {
		get_template_part( 'template-parts/content', 'page' );
	}
endwhile;

get_footer();
