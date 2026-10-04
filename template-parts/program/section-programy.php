<?php
/**
 * Template part for the "What can you study" section of a program page.
 *
 * Lists become program cards with tags derived from their text. When the
 * cards cover both bachelor and master studies, they can be filtered by tabs.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_parts   = array();
$najdisvujsen_levels  = array();
$najdisvujsen_grids   = 0;

foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
	if ( 'core/list' !== $najdisvujsen_block['blockName'] ) {
		$najdisvujsen_last = end( $najdisvujsen_parts );

		if ( $najdisvujsen_last && 'prose' === $najdisvujsen_last['type'] ) {
			$najdisvujsen_parts[ key( $najdisvujsen_parts ) ]['blocks'][] = $najdisvujsen_block;
		} else {
			$najdisvujsen_parts[] = array(
				'type'   => 'prose',
				'blocks' => array( $najdisvujsen_block ),
			);
		}
		continue;
	}

	$najdisvujsen_cards = array();

	foreach ( najdisvujsen_list_items( $najdisvujsen_block ) as $najdisvujsen_item ) {
		$najdisvujsen_text = wp_strip_all_tags( $najdisvujsen_item );
		$najdisvujsen_info = najdisvujsen_program_tags( $najdisvujsen_text );
		$najdisvujsen_link = '';

		list( $najdisvujsen_title, $najdisvujsen_body ) = najdisvujsen_split_lead( $najdisvujsen_item );

		if ( mb_strlen( wp_strip_all_tags( $najdisvujsen_title ) ) > 90 ) {
			$najdisvujsen_title = '';
			$najdisvujsen_body  = $najdisvujsen_item;
		}

		// A titled card with a single link gets it as the card action.
		if ( $najdisvujsen_title && 1 === preg_match_all( '#<a\s[^>]*href="([^"]+)"#i', $najdisvujsen_item, $najdisvujsen_links ) ) {
			$najdisvujsen_link  = html_entity_decode( $najdisvujsen_links[1][0] );
			$najdisvujsen_title = preg_replace( '#</?a\b[^>]*>#i', '', $najdisvujsen_title );
			$najdisvujsen_body  = preg_replace( '#</?a\b[^>]*>#i', '', $najdisvujsen_body );
		}

		$najdisvujsen_title = wp_strip_all_tags( $najdisvujsen_title );

		$najdisvujsen_levels[] = $najdisvujsen_info['level'];
		$najdisvujsen_cards[]  = array(
			'title' => trim( $najdisvujsen_title ),
			'body'  => trim( (string) $najdisvujsen_body ),
			'tags'  => $najdisvujsen_info['tags'],
			'level' => $najdisvujsen_info['level'],
			'link'  => $najdisvujsen_link,
		);
	}

	if ( $najdisvujsen_cards ) {
		$najdisvujsen_parts[] = array(
			'type'  => 'cards',
			'cards' => $najdisvujsen_cards,
		);
		++$najdisvujsen_grids;
	}
}

$najdisvujsen_tabs = 1 === $najdisvujsen_grids
	&& ! in_array( '', $najdisvujsen_levels, true )
	&& in_array( 'bc', $najdisvujsen_levels, true )
	&& in_array( 'mgr', $najdisvujsen_levels, true );
$najdisvujsen_dept = najdisvujsen_get_page_header_field( 'department' );
$najdisvujsen_url  = najdisvujsen_get_page_header_field( 'department_url' );

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => $args['tone'],
		'type'    => 'programy',
		'heading' => false,
	)
);
?>
<div class="section__head">
	<h2 class="section__title"><?php echo wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() ); ?></h2>
	<?php if ( $najdisvujsen_tabs ) : ?>
		<div class="tabs" role="tablist" aria-label="<?php esc_attr_e( 'Stupeň studia', 'najdisvujsen' ); ?>" data-filter-tabs>
			<button type="button" class="tabs__tab" role="tab" aria-selected="true" data-value="bc"><?php esc_html_e( 'Bakalářské', 'najdisvujsen' ); ?></button>
			<button type="button" class="tabs__tab" role="tab" aria-selected="false" data-value="mgr"><?php esc_html_e( 'Magisterské', 'najdisvujsen' ); ?></button>
		</div>
	<?php endif; ?>
</div>

<?php foreach ( $najdisvujsen_parts as $najdisvujsen_part ) : ?>
	<?php
	if ( 'prose' === $najdisvujsen_part['type'] ) {
		najdisvujsen_prose( $najdisvujsen_part['blocks'] );
		continue;
	}
	?>
	<div class="programs-grid"<?php echo $najdisvujsen_tabs ? ' data-filter-items' : ''; ?>>
		<?php foreach ( $najdisvujsen_part['cards'] as $najdisvujsen_card ) : ?>
			<div class="program-card"<?php echo $najdisvujsen_tabs ? ' data-level="' . esc_attr( $najdisvujsen_card['level'] ) . '"' : ''; ?>>
				<?php if ( $najdisvujsen_card['tags'] ) : ?>
					<ul class="program-card__tags">
						<?php foreach ( $najdisvujsen_card['tags'] as $najdisvujsen_tag ) : ?>
							<li class="tag tag--<?php echo esc_attr( $najdisvujsen_tag['tone'] ); ?>"><?php echo esc_html( $najdisvujsen_tag['label'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $najdisvujsen_card['title'] ) : ?>
					<h3 class="program-card__title"><?php echo esc_html( $najdisvujsen_card['title'] ); ?></h3>
				<?php endif; ?>

				<?php if ( $najdisvujsen_card['body'] ) : ?>
					<p class="program-card__text<?php echo $najdisvujsen_card['title'] ? '' : ' program-card__text--main'; ?>"><?php echo wp_kses( $najdisvujsen_card['body'], najdisvujsen_inline_kses() ); ?></p>
				<?php endif; ?>

				<?php if ( $najdisvujsen_card['link'] ) : ?>
					<a class="program-card__link" href="<?php echo esc_url( $najdisvujsen_card['link'] ); ?>">
						<span><?php esc_html_e( 'Detail programu', 'najdisvujsen' ); ?></span>
						<span class="program-card__arrow"><?php echo wp_kses( najdisvujsen_icon( 'arrow-right' ), najdisvujsen_icon_kses() ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endforeach; ?>

<div class="info-cards">
	<?php if ( $najdisvujsen_dept && $najdisvujsen_url ) : ?>
		<a class="info-card info-card--blue" href="<?php echo esc_url( $najdisvujsen_url ); ?>">
			<span class="info-card__eyebrow"><?php esc_html_e( 'Katedra', 'najdisvujsen' ); ?></span>
			<span class="info-card__title"><?php echo esc_html( $najdisvujsen_dept ); ?></span>
			<span class="info-card__foot"><?php esc_html_e( 'Navštiv web katedry', 'najdisvujsen' ); ?><?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?></span>
		</a>
	<?php elseif ( $najdisvujsen_dept ) : ?>
		<div class="info-card info-card--blue">
			<span class="info-card__eyebrow"><?php esc_html_e( 'Katedra', 'najdisvujsen' ); ?></span>
			<span class="info-card__title"><?php echo esc_html( $najdisvujsen_dept ); ?></span>
		</div>
	<?php endif; ?>
	<a class="info-card" href="https://www.univerzitnimesto.cz/">
		<span class="info-card__eyebrow"><?php esc_html_e( 'Univerzita Palackého v Olomouci', 'najdisvujsen' ); ?></span>
		<span class="info-card__text"><?php esc_html_e( 'Druhá nejstarší vysoká škola v Česku v jediném skutečně univerzitním městě', 'najdisvujsen' ); ?></span>
		<span class="info-card__foot"><?php esc_html_e( 'Poznej UP', 'najdisvujsen' ); ?><?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?></span>
	</a>
</div>
<?php
najdisvujsen_collage( $najdisvujsen_section['photos'], 'programy', 'section__photos' );
najdisvujsen_section_close();
