<?php
/**
 * The template for single posts and study program pages.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

get_header();

if ( is_singular( NAJDISVUJSEN_OBOR ) ) {
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/program/program' );
	endwhile;

	get_footer();
	return;
}
?>

<div class="container">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', 'single' );

		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Předchozí', 'najdisvujsen' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Další', 'najdisvujsen' ) . '</span> <span class="nav-title">%title</span>',
			)
		);
	endwhile;
	?>
</div>

<?php
get_footer();
