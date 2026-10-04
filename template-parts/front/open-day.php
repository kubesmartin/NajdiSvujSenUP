<?php
/**
 * Template part for the open days section of the front page.
 *
 * Dates, times and links are read from the paragraph announcing the open
 * days, e.g. "… v pátek 27. 11. 2026 od 8-14 hodin …". When no date can be
 * recognised the paragraph is shown as written.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_dates   = array();
$najdisvujsen_links   = array();
$najdisvujsen_note    = '';
$najdisvujsen_text    = array();
$najdisvujsen_days    = array(
	'pondělí' => __( 'pondělí', 'najdisvujsen' ),
	'úterý'   => __( 'úterý', 'najdisvujsen' ),
	'středu'  => __( 'středa', 'najdisvujsen' ),
	'čtvrtek' => __( 'čtvrtek', 'najdisvujsen' ),
	'pátek'   => __( 'pátek', 'najdisvujsen' ),
	'sobotu'  => __( 'sobota', 'najdisvujsen' ),
	'neděli'  => __( 'neděle', 'najdisvujsen' ),
);

foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
	if ( ! $najdisvujsen_dates && 'core/paragraph' === $najdisvujsen_block['blockName'] ) {
		$najdisvujsen_html  = najdisvujsen_block_inner_html( $najdisvujsen_block );
		$najdisvujsen_plain = html_entity_decode( wp_strip_all_tags( $najdisvujsen_html ) );
		$najdisvujsen_plain = preg_replace( '/[\s\x{00A0}]+/u', ' ', $najdisvujsen_plain );

		if ( preg_match_all( '/\b(' . implode( '|', array_keys( $najdisvujsen_days ) ) . ')\s+(\d{1,2}\.\s?\d{1,2}\.\s?\d{4})\s+(od\s+[\d:.,\s–-]+\s*hod\w*)/iu', $najdisvujsen_plain, $najdisvujsen_found, PREG_SET_ORDER ) ) {
			foreach ( $najdisvujsen_found as $najdisvujsen_match ) {
				$najdisvujsen_dates[] = array(
					'day'  => $najdisvujsen_days[ mb_strtolower( $najdisvujsen_match[1] ) ] ?? $najdisvujsen_match[1],
					'date' => preg_replace( '/\.\s?/', '. ', trim( $najdisvujsen_match[2] ) ),
					'time' => trim( $najdisvujsen_match[3] ),
				);
			}

			preg_match_all( '#<a\s[^>]*href="([^"]+)"[^>]*>(.*?)</a>#is', $najdisvujsen_html, $najdisvujsen_anchors, PREG_SET_ORDER );

			foreach ( $najdisvujsen_anchors as $najdisvujsen_index => $najdisvujsen_anchor ) {
				$najdisvujsen_labels = array( __( 'Program DOD', 'najdisvujsen' ), __( 'Web fakulty – Uchazečům', 'najdisvujsen' ) );

				$najdisvujsen_links[] = array(
					'url'   => html_entity_decode( $najdisvujsen_anchor[1] ),
					'label' => $najdisvujsen_labels[ $najdisvujsen_index ] ?? wp_strip_all_tags( $najdisvujsen_anchor[2] ),
				);
			}

			if ( preg_match( '#<br\s*/?>\s*(?:<strong>)?([^<]+?)(?:</strong>)?\s*$#u', $najdisvujsen_html, $najdisvujsen_last ) ) {
				$najdisvujsen_note = trim( $najdisvujsen_last[1] );
			}
			continue;
		}
	}

	if ( 'core/gallery' === $najdisvujsen_block['blockName'] ) {
		continue;
	}

	// Highlight boxes read as plain text in this layout.
	$najdisvujsen_text = array_merge(
		$najdisvujsen_text,
		najdisvujsen_is_group_style( $najdisvujsen_block, 'highlight' ) ? $najdisvujsen_block['innerBlocks'] : array( $najdisvujsen_block )
	);
}

// The mosaic opens with an interior and closes with a wide outdoor shot.
$najdisvujsen_gallery = array_slice( $args['gallery'], 0, 4 );
$najdisvujsen_first   = najdisvujsen_find_photo( $najdisvujsen_gallery, 'aula' );
$najdisvujsen_wide    = najdisvujsen_find_photo( array_diff( $najdisvujsen_gallery, array( $najdisvujsen_first ) ), 'olomouc' );
$najdisvujsen_gallery = array_values(
	array_unique(
		array_filter( array_merge( array( $najdisvujsen_first ), array_diff( $najdisvujsen_gallery, array( $najdisvujsen_first, $najdisvujsen_wide ) ), array( $najdisvujsen_wide ) ) )
	)
);

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => 'night',
		'type'    => 'open-day',
		'heading' => false,
	)
);
?>
<div class="split split--top">
	<div>
		<h2 class="section__title"><?php echo wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_text ); ?>
	</div>
	<?php if ( $najdisvujsen_dates ) : ?>
		<div class="open-days">
			<ul class="open-days__list">
				<?php foreach ( $najdisvujsen_dates as $najdisvujsen_date ) : ?>
					<li class="date-pill">
						<span class="sticker pulse" aria-hidden="true"><?php echo wp_kses( najdisvujsen_icon( 'calendar-heart' ), najdisvujsen_icon_kses() ); ?></span>
						<span class="date-pill__date">
							<span class="date-pill__day"><?php echo esc_html( $najdisvujsen_date['day'] ); ?></span>
							<span class="date-pill__value"><?php echo esc_html( $najdisvujsen_date['date'] ); ?></span>
						</span>
						<span class="date-pill__time"><?php echo esc_html( $najdisvujsen_date['time'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $najdisvujsen_links ) : ?>
				<div class="cta-row">
					<?php foreach ( $najdisvujsen_links as $najdisvujsen_index => $najdisvujsen_link ) : ?>
						<a class="btn <?php echo 0 === $najdisvujsen_index ? 'btn--primary' : 'btn--outline'; ?>" href="<?php echo esc_url( $najdisvujsen_link['url'] ); ?>">
							<?php echo esc_html( $najdisvujsen_link['label'] ); ?>
							<?php
							if ( 0 === $najdisvujsen_index ) {
								echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() );
							}
							?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ( $najdisvujsen_note ) : ?>
				<p class="open-days__note"><?php echo esc_html( $najdisvujsen_note ); ?> <span aria-hidden="true">👋</span></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<?php if ( $najdisvujsen_gallery ) : ?>
	<div class="collage collage--4 collage--mosaic">
		<?php
		foreach ( $najdisvujsen_gallery as $najdisvujsen_index => $najdisvujsen_id ) {
			najdisvujsen_photo(
				$najdisvujsen_id,
				0 === $najdisvujsen_index ? '(min-width: 900px) 600px, 100vw' : '(min-width: 900px) 300px, 50vw',
				0 === $najdisvujsen_index ? 'photo--big' : ( 3 === $najdisvujsen_index ? 'photo--wide' : '' ),
				'dod'
			);
		}
		?>
	</div>
<?php endif; ?>
<?php
najdisvujsen_section_close();
