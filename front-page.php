<?php
/**
 * The template for the front page.
 *
 * Sections are filled from the front page form (Titulní stránka in the admin).
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! is_page() ) {
	get_template_part( 'index' );
	return;
}

get_header();

while ( have_posts() ) :
	the_post();

	$najdisvujsen_data = najdisvujsen_get_data();

	get_template_part( 'template-parts/front/hero', null, array( 'data' => $najdisvujsen_data['uvod'] ) );
	get_template_part( 'template-parts/front/announcement' );
	get_template_part( 'template-parts/front/why', null, array( 'data' => $najdisvujsen_data['proc'] ) );
	get_template_part( 'template-parts/front/video', null, array( 'data' => $najdisvujsen_data['video'] ) );

	if ( najdisvujsen_has_content( array( $najdisvujsen_data['mesto']['text'], $najdisvujsen_data['mesto']['statement'] ) ) ) {
		get_template_part( 'template-parts/front/city', null, array( 'data' => $najdisvujsen_data['mesto'] ) );
	}

	get_template_part( 'template-parts/front/programs', null, array( 'data' => $najdisvujsen_data['katalog'] ) );

	if ( najdisvujsen_has_content( array( $najdisvujsen_data['dod']['text'], najdisvujsen_open_days( $najdisvujsen_data['dod']['dates'] ) ) ) ) {
		get_template_part( 'template-parts/front/open-day', null, array( 'data' => $najdisvujsen_data['dod'] ) );
	}

	get_template_part( 'template-parts/front/life', null, array( 'photos' => $najdisvujsen_data['zivot']['photos'] ) );

	if ( najdisvujsen_has_content( array( $najdisvujsen_data['slovensko']['text'], $najdisvujsen_data['slovensko']['trains'] ) ) ) {
		get_template_part( 'template-parts/front/slovakia', null, array( 'data' => $najdisvujsen_data['slovensko'] ) );
	}
endwhile;

get_footer();
