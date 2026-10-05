<?php
/**
 * Template part for the "Why study" section of a program page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_data    = $najdisvujsen_section['data'];
$najdisvujsen_emoji   = najdisvujsen_get_data()['emoji'];
$najdisvujsen_number  = 0;

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => $args['tone'],
		'type'    => 'proc',
		'eyebrow' => __( 'Proč?', 'najdisvujsen' ),
	)
);
?>
<?php if ( '' !== $najdisvujsen_data['lead'] || $najdisvujsen_data['items'] ) : ?>
	<div class="why">
		<?php if ( '' !== $najdisvujsen_data['lead'] ) : ?>
			<div class="why__item why__item--lead">
				<span class="sticker sticker--white" aria-hidden="true"><?php echo esc_html( $najdisvujsen_emoji ? $najdisvujsen_emoji : '🎓' ); ?></span>
				<?php najdisvujsen_prose( $najdisvujsen_data['lead'], 'why__lead' ); ?>
			</div>
		<?php endif; ?>
		<?php foreach ( $najdisvujsen_data['items'] as $najdisvujsen_item ) : ?>
			<div class="why__item">
				<span class="why__num" aria-hidden="true"><?php echo esc_html( (string) ++$najdisvujsen_number ); ?></span>
				<div class="why__text">
					<?php if ( '' !== $najdisvujsen_item['title'] ) : ?>
						<h3 class="why__title"><?php echo esc_html( najdisvujsen_nbsp( $najdisvujsen_item['title'] ) ); ?></h3>
					<?php endif; ?>
					<?php najdisvujsen_prose( $najdisvujsen_item['text'], 'why__body' ); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
najdisvujsen_prose( $najdisvujsen_data['text'] );
najdisvujsen_collage( $najdisvujsen_section['photos'], 'proc', 'section__photos' );
najdisvujsen_section_close();
