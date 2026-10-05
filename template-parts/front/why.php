<?php
/**
 * Template part for the "Why FF UP" section of the front page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_data  = $args['data'];
$najdisvujsen_tones = array( 'blue', 'night', 'line', 'grey' );
?>
<section id="univerzita" class="section section--white section--why">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Proč FF UP?', 'najdisvujsen' ); ?></p>
		<h2 class="section__title">
			<?php
			echo esc_html( '' !== $najdisvujsen_data['title'] ? $najdisvujsen_data['title'] : __( 'Druhá nejstarší univerzita v Česku', 'najdisvujsen' ) );
			?>
		</h2>

		<div class="bento">
			<?php najdisvujsen_prose( $najdisvujsen_data['text'], 'tile tile--text' ); ?>

			<?php foreach ( $najdisvujsen_data['stats'] as $najdisvujsen_index => $najdisvujsen_stat ) : ?>
				<p class="tile tile--<?php echo esc_attr( $najdisvujsen_tones[ $najdisvujsen_index % 4 ] ); ?>">
					<span class="tile__num"><?php echo esc_html( $najdisvujsen_stat['number'] ); ?></span>
					<span class="tile__label"><?php echo esc_html( $najdisvujsen_stat['label'] ); ?></span>
				</p>
			<?php endforeach; ?>

			<?php
			if ( $najdisvujsen_data['photo'] ) {
				najdisvujsen_photo( $najdisvujsen_data['photo'], '(min-width: 1100px) 600px, 100vw', 'tile tile--photo tile--wide', 'why' );
			}
			?>

			<a class="tile tile--wide apply-tile" href="<?php echo esc_url( najdisvujsen_admission_url( 'najdisvujsen_application_url' ) ); ?>">
				<span class="apply-tile__arrow"><?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?></span>
				<span class="apply-tile__title"><?php esc_html_e( 'Podat přihlášku', 'najdisvujsen' ); ?></span>
				<span class="apply-tile__sub"><?php echo esc_html( (string) wp_parse_url( najdisvujsen_admission_url( 'najdisvujsen_application_url' ), PHP_URL_HOST ) ); ?></span>
			</a>
		</div>
	</div>
</section>
