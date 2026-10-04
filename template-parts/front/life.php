<?php
/**
 * Template part for the photo gallery of student life on the front page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_photos = array_slice( $args['photos'], 0, 10 );

if ( count( $najdisvujsen_photos ) < 3 ) {
	return;
}
?>
<section id="zivot" class="section section--white section--life">
	<div class="container">
		<div class="section__head">
			<h2 class="section__title"><?php esc_html_e( 'Život na FF UP', 'najdisvujsen' ); ?></h2>
			<span class="sticker" aria-hidden="true">📸</span>
		</div>
		<div class="life life--<?php echo esc_attr( (string) count( $najdisvujsen_photos ) ); ?>">
			<?php
			foreach ( $najdisvujsen_photos as $najdisvujsen_index => $najdisvujsen_id ) {
				najdisvujsen_photo(
					$najdisvujsen_id,
					0 === $najdisvujsen_index ? '(min-width: 900px) 600px, 100vw' : '(min-width: 900px) 300px, 50vw',
					'life__item life__item--' . ( $najdisvujsen_index + 1 ),
					'life'
				);
			}
			?>
		</div>
	</div>
</section>
