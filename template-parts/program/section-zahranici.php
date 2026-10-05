<?php
/**
 * Template part for the "Study abroad" section of a program page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => $args['tone'],
		'type'    => 'zahranici',
		'heading' => false,
	)
);
?>
<div class="abroad">
	<div class="abroad__stickers" aria-hidden="true">
		<span class="sticker sticker--xl pulse">🌍</span>
		<span class="sticker sticker--lg sticker--outline">✈️</span>
	</div>
	<div class="abroad__text">
		<h2 class="section__title"><?php echo esc_html( $najdisvujsen_section['title'] ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_section['data']['text'], 'prose--lead', true ); ?>
	</div>
</div>
<?php
najdisvujsen_collage( $najdisvujsen_section['photos'], 'zahranici', 'section__photos' );
najdisvujsen_section_close();
