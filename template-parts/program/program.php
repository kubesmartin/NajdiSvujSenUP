<?php
/**
 * Template part for a study program page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_sections = najdisvujsen_obor_sections( get_the_ID() );
$najdisvujsen_layouts  = array(
	'proc'      => 'white',
	'programy'  => 'muted',
	'olomouc'   => 'night',
	'uplatneni' => 'white',
	'zahranici' => 'muted',
	'lide'      => 'white',
);
$najdisvujsen_previous = 'night';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'program' ); ?>>
	<?php get_template_part( 'template-parts/program/hero' ); ?>

	<?php
	foreach ( $najdisvujsen_sections as $najdisvujsen_section ) {
		if ( 'generic' === $najdisvujsen_section['type'] ) {
			$najdisvujsen_tone = 'white' === $najdisvujsen_previous ? 'muted' : 'white';
		} else {
			$najdisvujsen_tone = $najdisvujsen_layouts[ $najdisvujsen_section['type'] ];
		}

		get_template_part(
			'template-parts/program/section',
			$najdisvujsen_section['type'],
			array(
				'section' => $najdisvujsen_section,
				'tone'    => $najdisvujsen_tone,
			)
		);

		$najdisvujsen_previous = $najdisvujsen_tone;
	}

	get_template_part( 'template-parts/program/related' );
	get_template_part(
		'template-parts/program/cta',
		null,
		array(
			'anchor' => in_array( 'prijimacky', wp_list_pluck( $najdisvujsen_sections, 'anchor' ), true ) ? 'studuj' : 'prijimacky',
		)
	);
	?>
</article>
