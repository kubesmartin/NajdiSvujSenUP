<?php
/**
 * Template part for the "Why FF UP" section of the front page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_photo   = (int) $args['photo'];
$najdisvujsen_stats   = array(
	array( '1573', __( 'vzdělanost šíříme od 16. století', 'najdisvujsen' ), 'blue' ),
	array( '600+', __( 'kombinací studijních programů', 'najdisvujsen' ), 'night' ),
	array( '19', __( 'kateder na největší fakultě UP', 'najdisvujsen' ), 'line' ),
	array( '8', __( 'fakult tvoří Univerzitu Palackého', 'najdisvujsen' ), 'grey' ),
);
?>
<section id="univerzita" class="section section--white section--why">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'Proč FF UP?', 'najdisvujsen' ); ?></p>
		<h2 class="section__title">
			<?php
			echo $najdisvujsen_section && '' !== $najdisvujsen_section['title']
				? wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() )
				: esc_html__( 'Druhá nejstarší univerzita v Česku', 'najdisvujsen' );
			?>
		</h2>

		<div class="bento">
			<?php if ( $najdisvujsen_section && $najdisvujsen_section['blocks'] ) : ?>
				<?php najdisvujsen_prose( $najdisvujsen_section['blocks'], 'tile tile--text' ); ?>
			<?php endif; ?>

			<?php foreach ( $najdisvujsen_stats as $najdisvujsen_stat ) : ?>
				<p class="tile tile--<?php echo esc_attr( $najdisvujsen_stat[2] ); ?>">
					<span class="tile__num"><?php echo esc_html( $najdisvujsen_stat[0] ); ?></span>
					<span class="tile__label"><?php echo esc_html( $najdisvujsen_stat[1] ); ?></span>
				</p>
			<?php endforeach; ?>

			<?php
			if ( $najdisvujsen_photo ) {
				najdisvujsen_photo( $najdisvujsen_photo, '(min-width: 1100px) 600px, 100vw', 'tile tile--photo tile--wide', 'why' );
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
