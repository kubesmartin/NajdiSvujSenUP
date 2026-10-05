<?php
/**
 * Template part for an extra section of a program page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone' => $args['tone'],
		'type' => 'generic',
	)
);

najdisvujsen_prose( $najdisvujsen_section['data']['text'] );
najdisvujsen_collage( $najdisvujsen_section['photos'], 'section-' . $najdisvujsen_section['anchor'], 'section__photos' );
najdisvujsen_section_close();
