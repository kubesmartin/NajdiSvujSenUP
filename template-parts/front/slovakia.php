<?php
/**
 * Template part for the "Students from Slovakia" section of the front page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_data    = $args['data'];
$najdisvujsen_section = array(
	'anchor' => 'slovensko',
	'title'  => $najdisvujsen_data['title'],
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
		<h2 class="section__title"><?php echo esc_html( $najdisvujsen_data['title'] ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_data['text'] ); ?>
	</div>
	<div class="slovakia-media">
		<?php
		if ( $najdisvujsen_data['map'] ) {
			najdisvujsen_photo( $najdisvujsen_data['map'], '(min-width: 900px) 560px, 100vw', 'photo--map', 'slovensko' );
		}
		?>
		<?php if ( $najdisvujsen_data['trains'] ) : ?>
		<ul class="trains" aria-label="<?php esc_attr_e( 'Doba cesty vlakem do Olomouce', 'najdisvujsen' ); ?>">
			<?php foreach ( $najdisvujsen_data['trains'] as $najdisvujsen_train ) : ?>
				<li class="train">
					<?php echo wp_kses( najdisvujsen_icon( 'train-front' ), najdisvujsen_icon_kses() ); ?>
					<span class="train__city"><?php echo esc_html( $najdisvujsen_train['city'] ); ?></span>
					<span class="train__time"><?php echo esc_html( $najdisvujsen_train['time'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</div>
<?php
najdisvujsen_section_close();
