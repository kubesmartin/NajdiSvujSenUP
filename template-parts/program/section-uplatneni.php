<?php
/**
 * Template part for the "Careers" section of a program page.
 *
 * A list whose items start with a bold label becomes a set of toggles; other
 * lists are shown with check marks.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];

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
		<?php
		foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block_index => $najdisvujsen_block ) {
			if ( 'core/list' !== $najdisvujsen_block['blockName'] ) {
				najdisvujsen_prose( array( $najdisvujsen_block ) );
				continue;
			}

			$najdisvujsen_items  = najdisvujsen_list_items( $najdisvujsen_block );
			$najdisvujsen_splits = array_map( 'najdisvujsen_split_lead', $najdisvujsen_items );
			$najdisvujsen_titled = count( array_filter( array_column( $najdisvujsen_splits, 0 ), 'strlen' ) );

			if ( count( $najdisvujsen_items ) < 3 || $najdisvujsen_titled < count( $najdisvujsen_items ) ) {
				najdisvujsen_checks( $najdisvujsen_items );
				continue;
			}

			$najdisvujsen_prefix = 'jobs-' . $najdisvujsen_block_index;
			?>
			<div class="jobs" data-tabs>
				<div class="jobs__list" role="tablist" aria-label="<?php esc_attr_e( 'Oblasti uplatnění', 'najdisvujsen' ); ?>">
					<?php foreach ( $najdisvujsen_splits as $najdisvujsen_index => $najdisvujsen_split ) : ?>
						<button type="button" class="bubble bubble--light bubble--interactive" role="tab" id="<?php echo esc_attr( $najdisvujsen_prefix . '-tab-' . $najdisvujsen_index ); ?>" aria-controls="<?php echo esc_attr( $najdisvujsen_prefix . '-' . $najdisvujsen_index ); ?>" aria-selected="<?php echo 0 === $najdisvujsen_index ? 'true' : 'false'; ?>"><?php echo esc_html( wp_strip_all_tags( $najdisvujsen_split[0] ) ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php foreach ( $najdisvujsen_splits as $najdisvujsen_index => $najdisvujsen_split ) : ?>
					<div class="jobs__panel<?php echo 0 === $najdisvujsen_index ? ' is-active' : ''; ?>" role="tabpanel" id="<?php echo esc_attr( $najdisvujsen_prefix . '-' . $najdisvujsen_index ); ?>" aria-labelledby="<?php echo esc_attr( $najdisvujsen_prefix . '-tab-' . $najdisvujsen_index ); ?>">
						<p class="jobs__title"><?php echo esc_html( wp_strip_all_tags( $najdisvujsen_split[0] ) ); ?></p>
						<p><?php echo wp_kses( $najdisvujsen_split[1], najdisvujsen_inline_kses() ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
		}
		?>
	</div>
	<?php if ( $najdisvujsen_section['photos'] ) : ?>
		<div class="split__aside">
			<?php najdisvujsen_collage( $najdisvujsen_section['photos'], 'uplatneni', 'collage--stack' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
najdisvujsen_section_close();
