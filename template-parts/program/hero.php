<?php
/**
 * Template part for the study program page header.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_id      = get_the_ID();
$najdisvujsen_name    = najdisvujsen_program_name( $najdisvujsen_id );
$najdisvujsen_length  = mb_strlen( $najdisvujsen_name );
$najdisvujsen_size    = $najdisvujsen_length <= 14 ? 's' : ( $najdisvujsen_length <= 32 ? 'm' : 'l' );
$najdisvujsen_careers = najdisvujsen_program_careers( $najdisvujsen_id );
$najdisvujsen_front   = (int) get_option( 'page_on_front' );
$najdisvujsen_back    = $najdisvujsen_front ? get_permalink( $najdisvujsen_front ) . '#programy' : home_url( '/' );
?>
<header class="program-hero">
	<?php
	if ( has_post_thumbnail() ) {
		the_post_thumbnail(
			'full',
			array(
				'class'         => 'program-hero__bg',
				'alt'           => '',
				'sizes'         => '100vw',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'decoding'      => 'async',
				'data-parallax' => '',
			)
		);
	}
	?>
	<div class="container container--wide program-hero__inner">
		<a class="back-link" href="<?php echo esc_url( $najdisvujsen_back ); ?>">
			<?php echo wp_kses( najdisvujsen_icon( 'arrow-left' ), najdisvujsen_icon_kses() ); ?>
			<?php esc_html_e( 'Všechny programy', 'najdisvujsen' ); ?>
		</a>

		<div class="program-hero__main">
			<h1 class="program-title program-title--<?php echo esc_attr( $najdisvujsen_size ); ?>">
				<span class="program-title__bubble"><?php echo esc_html( $najdisvujsen_name ); ?></span>
			</h1>

			<?php if ( '' !== najdisvujsen_excerpt( $najdisvujsen_id ) ) : ?>
				<p class="program-hero__lead"><?php echo esc_html( najdisvujsen_nbsp( najdisvujsen_excerpt( $najdisvujsen_id ) ) ); ?></p>
			<?php endif; ?>

			<div class="cta-row">
				<?php najdisvujsen_apply_button( 'large' ); ?>
				<a class="btn btn--outline btn--large" href="<?php echo esc_url( najdisvujsen_admission_url( 'najdisvujsen_admission_url' ) ); ?>"><?php esc_html_e( 'Přijímací řízení', 'najdisvujsen' ); ?></a>
			</div>

			<?php najdisvujsen_social_buttons( $najdisvujsen_id ); ?>
		</div>
	</div>

	<?php if ( $najdisvujsen_careers ) : ?>
		<div class="program-hero__marquee">
			<?php
			$najdisvujsen_items = array();
			$najdisvujsen_emoji = array( '💡', '🚀', '🎓', '🤝' );

			foreach ( $najdisvujsen_careers as $najdisvujsen_index => $najdisvujsen_career ) {
				if ( $najdisvujsen_index && 0 === $najdisvujsen_index % 3 ) {
					$najdisvujsen_items[] = 'emoji:' . $najdisvujsen_emoji[ ( $najdisvujsen_index / 3 - 1 ) % count( $najdisvujsen_emoji ) ];
				}
				$najdisvujsen_items[] = $najdisvujsen_career;
			}

			najdisvujsen_marquee(
				$najdisvujsen_items,
				array(
					'duration' => 60,
					'label'    => __( 'Kde se můžeš uplatnit', 'najdisvujsen' ),
				)
			);
			?>
		</div>
	<?php endif; ?>
</header>
