<?php
/**
 * Template part for the "Who will teach you" section of a program page.
 *
 * Person cards with an optional introduction. The list always ends with the
 * same line.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_people  = $najdisvujsen_section['data']['people'];

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone' => $args['tone'],
		'type' => 'lide',
	)
);

najdisvujsen_prose( $najdisvujsen_section['data']['text'], 'people__intro' );
?>
<?php if ( $najdisvujsen_people ) : ?>
	<div class="people">
		<?php foreach ( $najdisvujsen_people as $najdisvujsen_person ) : ?>
			<article class="person">
				<div class="person__photo">
					<?php
					if ( $najdisvujsen_person['photo'] ) {
						echo wp_get_attachment_image(
							$najdisvujsen_person['photo'],
							'medium_large',
							false,
							array(
								'alt'      => $najdisvujsen_person['name'],
								'sizes'    => '(min-width: 1100px) 260px, (min-width: 600px) 30vw, 45vw',
								'loading'  => 'lazy',
								'decoding' => 'async',
							)
						);
					} else {
						$najdisvujsen_initials = '';

						foreach ( preg_split( '/\s+/u', $najdisvujsen_person['name'] ) as $najdisvujsen_word ) {
							$najdisvujsen_initials .= mb_substr( $najdisvujsen_word, 0, 1 );
						}

						printf( '<span aria-hidden="true">%s</span>', esc_html( mb_substr( $najdisvujsen_initials, 0, 2 ) ) );
					}
					?>
				</div>
				<?php if ( $najdisvujsen_person['name'] ) : ?>
					<h3 class="person__name"><?php echo esc_html( $najdisvujsen_person['name'] ); ?></h3>
				<?php endif; ?>
				<?php najdisvujsen_prose( $najdisvujsen_person['text'], 'person__bio' ); ?>
			</article>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<p class="people__more"><?php esc_html_e( 'A mnoho dalších expertů a expertek!', 'najdisvujsen' ); ?></p>
<?php
najdisvujsen_collage( $najdisvujsen_section['photos'], 'lide', 'section__photos' );
najdisvujsen_section_close();
