<?php
/**
 * Template part for the application call to action.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_anchor = $args['anchor'] ?? 'prijimacky';
?>
<section id="<?php echo esc_attr( $najdisvujsen_anchor ); ?>" class="section section--blue section--cta">
	<img
		class="section__bg"
		src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/cta-2000.webp' ); ?>"
		srcset="<?php echo esc_attr( NAJDISVUJSEN_URI . '/assets/images/cta-1000.webp 1000w, ' . NAJDISVUJSEN_URI . '/assets/images/cta-2000.webp 2000w, ' . NAJDISVUJSEN_URI . '/assets/images/cta-3000.webp 3000w' ); ?>"
		sizes="(max-width: 699px) 1400px, 100vw"
		width="4000"
		height="1000"
		alt=""
		loading="lazy"
		decoding="async"
		data-parallax
	>
	<div class="container cta-band">
		<h2 class="section__title"><?php esc_html_e( 'Studuj na Filozofické fakultě UP!', 'najdisvujsen' ); ?></h2>
		<div class="cta-row">
			<?php najdisvujsen_apply_button( 'dark large' ); ?>
			<a class="btn btn--white btn--large" href="<?php echo esc_url( najdisvujsen_admission_url( 'najdisvujsen_admission_url' ) ); ?>"><?php esc_html_e( 'Přijímací řízení', 'najdisvujsen' ); ?></a>
		</div>
	</div>
</section>
