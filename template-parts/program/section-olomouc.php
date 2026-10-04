<?php
/**
 * Template part for the "Why Olomouc" section of a program page.
 *
 * The first three points of a long list are highlighted as cards, the rest
 * is listed with check marks next to the section photos.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section    = $args['section'];
$najdisvujsen_highlights = array();
$najdisvujsen_parts      = array();

foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
	if ( 'core/list' === $najdisvujsen_block['blockName'] ) {
		$najdisvujsen_items = najdisvujsen_list_items( $najdisvujsen_block );

		if ( ! $najdisvujsen_highlights && ! $najdisvujsen_parts && count( $najdisvujsen_items ) >= 6 ) {
			$najdisvujsen_highlights = array_slice( $najdisvujsen_items, 0, 3 );
			$najdisvujsen_items      = array_slice( $najdisvujsen_items, 3 );
		}

		$najdisvujsen_parts[] = array(
			'type'  => 'checks',
			'items' => $najdisvujsen_items,
		);
		continue;
	}

	$najdisvujsen_parts[] = array(
		'type'   => 'prose',
		'blocks' => array( $najdisvujsen_block ),
	);
}

$najdisvujsen_stickers = array( '💡', '🚀', '✨' );

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone' => $args['tone'],
		'type' => 'olomouc',
	)
);
?>
<?php if ( $najdisvujsen_highlights ) : ?>
	<div class="highlights">
		<?php foreach ( $najdisvujsen_highlights as $najdisvujsen_index => $najdisvujsen_item ) : ?>
			<div class="highlights__item">
				<span class="sticker" aria-hidden="true"><?php echo esc_html( $najdisvujsen_stickers[ $najdisvujsen_index ] ); ?></span>
				<p><?php echo wp_kses( $najdisvujsen_item, najdisvujsen_inline_kses() ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="split split--top<?php echo $najdisvujsen_section['photos'] ? '' : ' split--single'; ?>">
	<div class="split__main">
		<?php
		foreach ( $najdisvujsen_parts as $najdisvujsen_part ) {
			if ( 'checks' === $najdisvujsen_part['type'] ) {
				najdisvujsen_checks( $najdisvujsen_part['items'] );
			} else {
				najdisvujsen_prose( $najdisvujsen_part['blocks'] );
			}
		}
		?>
	</div>
	<?php if ( $najdisvujsen_section['photos'] ) : ?>
		<div class="split__aside">
			<?php najdisvujsen_collage( $najdisvujsen_section['photos'], 'olomouc', 'collage--stack' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
najdisvujsen_section_close();
