<?php
/**
 * Template part for the "Careers" section of a program page.
 *
 * Career areas are shown as bubbles switching between their descriptions.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_data    = $najdisvujsen_section['data'];

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => $args['tone'],
		'type'    => 'uplatneni',
		'eyebrow' => __( 'Uplatnění', 'najdisvujsen' ),
	)
);
?>
<div class="split split--top<?php echo $najdisvujsen_section['photos'] ? '' : ' split--single'; ?>">
	<div class="split__main">
		<?php najdisvujsen_prose( $najdisvujsen_data['text'], '', true ); ?>
		<?php if ( $najdisvujsen_data['areas'] ) : ?>
			<div class="jobs" data-tabs>
				<div class="jobs__list" role="tablist" aria-label="<?php esc_attr_e( 'Oblasti uplatnění', 'najdisvujsen' ); ?>">
					<?php foreach ( $najdisvujsen_data['areas'] as $najdisvujsen_index => $najdisvujsen_area ) : ?>
						<button type="button" class="bubble bubble--light bubble--interactive" role="tab" id="<?php echo esc_attr( 'jobs-tab-' . $najdisvujsen_index ); ?>" aria-controls="<?php echo esc_attr( 'jobs-' . $najdisvujsen_index ); ?>" aria-selected="<?php echo 0 === $najdisvujsen_index ? 'true' : 'false'; ?>"><?php echo esc_html( $najdisvujsen_area['title'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php foreach ( $najdisvujsen_data['areas'] as $najdisvujsen_index => $najdisvujsen_area ) : ?>
					<div class="jobs__panel<?php echo 0 === $najdisvujsen_index ? ' is-active' : ''; ?>" role="tabpanel" id="<?php echo esc_attr( 'jobs-' . $najdisvujsen_index ); ?>" aria-labelledby="<?php echo esc_attr( 'jobs-tab-' . $najdisvujsen_index ); ?>">
						<p class="jobs__title"><?php echo esc_html( $najdisvujsen_area['title'] ); ?></p>
						<?php najdisvujsen_prose( $najdisvujsen_area['text'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if ( $najdisvujsen_section['photos'] ) : ?>
		<div class="split__aside">
			<?php najdisvujsen_collage( $najdisvujsen_section['photos'], 'uplatneni', 'collage--stack' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
najdisvujsen_section_close();
