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
		<h2 class="section__title"><?php echo wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() ); ?></h2>
		<?php
		foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
			if ( 'core/list' === $najdisvujsen_block['blockName'] ) {
				najdisvujsen_checks( najdisvujsen_list_items( $najdisvujsen_block ) );
			} else {
				najdisvujsen_prose( array( $najdisvujsen_block ), 'prose--lead' );
			}
		}
		?>
	</div>
</div>
<?php
najdisvujsen_collage( $najdisvujsen_section['photos'], 'zahranici', 'section__photos' );
najdisvujsen_section_close();
