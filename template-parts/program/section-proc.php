<?php
/**
 * Template part for the "Why study" section of a program page.
 *
 * Paragraphs and list items become numbered cards; a short first item is
 * highlighted as the lead.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_cards   = array();
$najdisvujsen_rest    = array();

foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
	if ( 'core/paragraph' === $najdisvujsen_block['blockName'] ) {
		$najdisvujsen_cards[] = najdisvujsen_block_inner_html( $najdisvujsen_block );
	} elseif ( 'core/list' === $najdisvujsen_block['blockName'] ) {
		$najdisvujsen_cards = array_merge( $najdisvujsen_cards, najdisvujsen_list_items( $najdisvujsen_block ) );
	} else {
		$najdisvujsen_rest[] = $najdisvujsen_block;
	}
}

// Skip items that only repeat the section heading.
$najdisvujsen_heading = mb_strtolower( trim( wp_strip_all_tags( $najdisvujsen_section['title'] ) ) );

$najdisvujsen_cards = array_values(
	array_filter(
		$najdisvujsen_cards,
		static function ( $card ) use ( $najdisvujsen_heading ) {
			$text = mb_strtolower( trim( wp_strip_all_tags( $card ) ) );

			return '' !== $text && $text !== $najdisvujsen_heading;
		}
	)
);

$najdisvujsen_lead  = count( $najdisvujsen_cards ) > 1 && mb_strlen( wp_strip_all_tags( $najdisvujsen_cards[0] ) ) <= 260;
$najdisvujsen_emoji = najdisvujsen_get_page_header_field( 'emoji' );

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => $args['tone'],
		'type'    => 'proc',
		'eyebrow' => __( 'Proč?', 'najdisvujsen' ),
	)
);
?>
<?php if ( $najdisvujsen_cards ) : ?>
	<div class="why">
		<?php
		foreach ( $najdisvujsen_cards as $najdisvujsen_index => $najdisvujsen_card ) :
			if ( 0 === $najdisvujsen_index && $najdisvujsen_lead ) :
				?>
				<div class="why__item why__item--lead">
					<span class="sticker sticker--white" aria-hidden="true"><?php echo esc_html( $najdisvujsen_emoji ? $najdisvujsen_emoji : '🎓' ); ?></span>
					<p><?php echo wp_kses( $najdisvujsen_card, najdisvujsen_inline_kses() ); ?></p>
				</div>
				<?php
				continue;
			endif;

			list( $najdisvujsen_title, $najdisvujsen_body ) = najdisvujsen_split_lead( $najdisvujsen_card );
			?>
			<div class="why__item">
				<span class="why__num" aria-hidden="true"><?php echo esc_html( (string) ( $najdisvujsen_lead ? $najdisvujsen_index : $najdisvujsen_index + 1 ) ); ?></span>
				<div class="why__text">
					<?php if ( $najdisvujsen_title ) : ?>
						<h3 class="why__title"><?php echo wp_kses( $najdisvujsen_title, najdisvujsen_inline_kses() ); ?></h3>
					<?php endif; ?>
					<p><?php echo wp_kses( $najdisvujsen_body, najdisvujsen_inline_kses() ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
najdisvujsen_prose( $najdisvujsen_rest );
najdisvujsen_collage( $najdisvujsen_section['photos'], 'proc', 'section__photos' );
najdisvujsen_section_close();
