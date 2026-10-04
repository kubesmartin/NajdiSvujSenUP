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
	<div class="container cta-band">
		<h2 class="section__title"><?php esc_html_e( 'Studuj na Filozofické fakultě UP!', 'najdisvujsen' ); ?></h2>
		<div class="cta-row">
			<?php najdisvujsen_apply_button( 'dark large' ); ?>
			<a class="btn btn--white btn--large" href="<?php echo esc_url( najdisvujsen_admission_url( 'najdisvujsen_admission_url' ) ); ?>"><?php esc_html_e( 'Přijímací řízení', 'najdisvujsen' ); ?></a>
		</div>
	</div>
</section>
