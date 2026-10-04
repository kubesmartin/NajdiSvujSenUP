<?php
/**
 * Template part for a program page section without a dedicated layout.
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

najdisvujsen_prose( $najdisvujsen_section['blocks'] );
najdisvujsen_collage( $najdisvujsen_section['photos'], 'section-' . ( $najdisvujsen_section['anchor'] ? $najdisvujsen_section['anchor'] : 'generic' ), 'section__photos' );
najdisvujsen_section_close();
