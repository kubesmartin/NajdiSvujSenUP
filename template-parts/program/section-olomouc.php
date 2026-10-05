<?php
/**
 * Template part for the "Why Olomouc" section of a program page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section  = $args['section'];
$najdisvujsen_data     = $najdisvujsen_section['data'];
$najdisvujsen_stickers = array( '💡', '🚀', '✨' );

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone' => $args['tone'],
		'type' => 'olomouc',
	)
);
?>
<?php if ( $najdisvujsen_data['highlights'] ) : ?>
	<div class="highlights">
		<?php foreach ( $najdisvujsen_data['highlights'] as $najdisvujsen_index => $najdisvujsen_item ) : ?>
			<div class="highlights__item">
				<span class="sticker" aria-hidden="true"><?php echo esc_html( $najdisvujsen_stickers[ $najdisvujsen_index ] ?? '✨' ); ?></span>
				<?php najdisvujsen_prose( $najdisvujsen_item['text'], 'highlights__text' ); ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<div class="split split--top<?php echo $najdisvujsen_section['photos'] ? '' : ' split--single'; ?>">
	<div class="split__main">
		<?php najdisvujsen_prose( $najdisvujsen_data['text'], '', true ); ?>
	</div>
	<?php if ( $najdisvujsen_section['photos'] ) : ?>
		<div class="split__aside">
			<?php najdisvujsen_collage( $najdisvujsen_section['photos'], 'olomouc', 'collage--stack' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
najdisvujsen_section_close();
