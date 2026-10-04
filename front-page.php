<?php
/**
 * The template for the front page.
 *
 * Sections of the front page content are matched by their anchors and
 * rendered with dedicated layouts; unknown sections follow as plain text.
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

	$najdisvujsen_sections = array();
	$najdisvujsen_strips   = array();
	$najdisvujsen_gallery  = array();

	foreach ( najdisvujsen_get_sections() as $najdisvujsen_index => $najdisvujsen_section ) {
		$najdisvujsen_key = $najdisvujsen_section['anchor'];

		if ( '' === $najdisvujsen_key ) {
			$najdisvujsen_key = 0 === $najdisvujsen_index ? 'univerzita' : 'section-' . $najdisvujsen_index;
		}

		$najdisvujsen_strips = array_merge( $najdisvujsen_strips, $najdisvujsen_section['photos'] );

		foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
			if ( 'core/gallery' === $najdisvujsen_block['blockName'] ) {
				$najdisvujsen_gallery = array_merge( $najdisvujsen_gallery, najdisvujsen_gallery_ids( $najdisvujsen_block ) );
			}
		}

		$najdisvujsen_sections[ $najdisvujsen_key ] = $najdisvujsen_section;
	}

	$najdisvujsen_photos = array_values( array_unique( array_merge( $najdisvujsen_gallery, $najdisvujsen_strips ) ) );
	$najdisvujsen_parts  = array(
		'univerzita'        => 'why',
		'univerzitni-mesto' => 'city',
		'programy'          => 'programs',
		'dod'               => 'open-day',
		'slovensko'         => 'slovakia',
	);

	get_template_part( 'template-parts/front/hero' );

	get_template_part(
		'template-parts/front/why',
		null,
		array(
			'section' => $najdisvujsen_sections['univerzita'] ?? null,
			'photo'   => najdisvujsen_find_photo( $najdisvujsen_photos, 'aula' ),
		)
	);

	get_template_part( 'template-parts/front/video' );

	if ( isset( $najdisvujsen_sections['univerzitni-mesto'] ) ) {
		get_template_part(
			'template-parts/front/city',
			null,
			array(
				'section' => $najdisvujsen_sections['univerzitni-mesto'],
				'photo'   => najdisvujsen_find_photo( $najdisvujsen_photos, 'olomouc' ),
			)
		);
	}

	if ( isset( $najdisvujsen_sections['programy'] ) ) {
		get_template_part( 'template-parts/front/programs', null, array( 'section' => $najdisvujsen_sections['programy'] ) );
	}

	if ( isset( $najdisvujsen_sections['dod'] ) ) {
		get_template_part(
			'template-parts/front/open-day',
			null,
			array(
				'section' => $najdisvujsen_sections['dod'],
				'gallery' => $najdisvujsen_gallery,
			)
		);
	}

	get_template_part( 'template-parts/front/life', null, array( 'photos' => array_values( array_unique( array_merge( $najdisvujsen_strips, $najdisvujsen_gallery ) ) ) ) );

	if ( isset( $najdisvujsen_sections['slovensko'] ) ) {
		get_template_part( 'template-parts/front/slovakia', null, array( 'section' => $najdisvujsen_sections['slovensko'] ) );
	}

	$najdisvujsen_previous = 'white';

	foreach ( $najdisvujsen_sections as $najdisvujsen_key => $najdisvujsen_section ) {
		if ( isset( $najdisvujsen_parts[ $najdisvujsen_key ] ) || ( '' === $najdisvujsen_section['title'] && ! $najdisvujsen_section['blocks'] ) ) {
			continue;
		}

		$najdisvujsen_previous = 'white' === $najdisvujsen_previous ? 'muted' : 'white';

		get_template_part(
			'template-parts/program/section',
			'generic',
			array(
				'section' => $najdisvujsen_section,
				'tone'    => $najdisvujsen_previous,
			)
		);
	}
endwhile;

get_footer();
