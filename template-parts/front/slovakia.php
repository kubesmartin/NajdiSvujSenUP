<?php
/**
 * Template part for the "Students from Slovakia" section of the front page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_text    = array();
$najdisvujsen_map     = 0;

foreach ( $najdisvujsen_section['blocks'] as $najdisvujsen_block ) {
	if ( ! $najdisvujsen_map && 'core/image' === $najdisvujsen_block['blockName'] && ! empty( $najdisvujsen_block['attrs']['id'] ) ) {
		$najdisvujsen_map = (int) $najdisvujsen_block['attrs']['id'];
		continue;
	}

	$najdisvujsen_text[] = $najdisvujsen_block;
}

$najdisvujsen_trains = array(
	array( 'Bratislava', __( '3 h', 'najdisvujsen' ) ),
	array( 'Žilina', __( '3 h', 'najdisvujsen' ) ),
	array( 'Košice', __( '6 h', 'najdisvujsen' ) ),
);

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => 'white',
		'type'    => 'slovakia',
		'heading' => false,
	)
);
?>
<div class="split">
	<div>
		<p class="eyebrow"><span aria-hidden="true">🇸🇰</span> <?php esc_html_e( 'Študuj v Olomouci', 'najdisvujsen' ); ?></p>
		<h2 class="section__title"><?php echo wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_text ); ?>
	</div>
	<div class="slovakia-media">
		<?php
		if ( $najdisvujsen_map ) {
			najdisvujsen_photo( $najdisvujsen_map, '(min-width: 900px) 560px, 100vw', 'photo--map', 'slovensko' );
		}
		?>
		<ul class="trains" aria-label="<?php esc_attr_e( 'Doba cesty vlakem do Olomouce', 'najdisvujsen' ); ?>">
			<?php foreach ( $najdisvujsen_trains as $najdisvujsen_train ) : ?>
				<li class="train">
					<?php echo wp_kses( najdisvujsen_icon( 'train-front' ), najdisvujsen_icon_kses() ); ?>
					<span class="train__city"><?php echo esc_html( $najdisvujsen_train[0] ); ?></span>
					<span class="train__time"><?php echo esc_html( $najdisvujsen_train[1] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php
najdisvujsen_section_close();
