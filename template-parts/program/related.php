<?php
/**
 * Template part for programs related to the current program.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_related = najdisvujsen_related_programs( get_the_ID() );

if ( ! $najdisvujsen_related ) {
	return;
}
?>
<nav class="container related" aria-labelledby="related-title">
	<p id="related-title" class="related__title"><?php esc_html_e( 'Mohlo by tě zajímat', 'najdisvujsen' ); ?></p>
	<ul class="related__list">
		<?php foreach ( $najdisvujsen_related as $najdisvujsen_program ) : ?>
			<li><a class="bubble bubble--light bubble--link" href="<?php echo esc_url( get_permalink( $najdisvujsen_program ) ); ?>"><?php echo esc_html( najdisvujsen_program_name( $najdisvujsen_program->ID ) ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
