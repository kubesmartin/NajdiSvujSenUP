<?php
/**
 * Template part for the "University town" section of the front page.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_data    = $args['data'];
$najdisvujsen_section = array(
	'anchor' => 'univerzitni-mesto',
	'title'  => $najdisvujsen_data['title'],
);

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => 'blue',
		'type'    => 'city',
		'heading' => false,
	)
);
?>
<div class="split">
	<div>
		<h2 class="section__title"><?php echo esc_html( $najdisvujsen_data['title'] ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_data['text'] ); ?>
		<?php if ( '' !== $najdisvujsen_data['statement'] ) : ?>
			<p class="statement"><?php echo esc_html( najdisvujsen_nbsp( $najdisvujsen_data['statement'] ) ); ?></p>
		<?php endif; ?>
	</div>
	<div class="city-media">
		<?php
		if ( $najdisvujsen_data['photo'] ) {
			najdisvujsen_photo( $najdisvujsen_data['photo'], '(min-width: 900px) 560px, 100vw', 'photo--big', 'city' );
		}
		?>
		<ul class="city-stats">
			<?php foreach ( $najdisvujsen_data['stats'] as $najdisvujsen_stat ) : ?>
				<?php $najdisvujsen_suffix = $najdisvujsen_stat['plus'] ? '+' : ''; ?>
				<li class="stat">
					<span class="stat__num" data-count="<?php echo esc_attr( (string) $najdisvujsen_stat['number'] ); ?>" data-suffix="<?php echo esc_attr( $najdisvujsen_suffix ); ?>"><?php echo esc_html( number_format_i18n( $najdisvujsen_stat['number'] ) . $najdisvujsen_suffix ); ?></span>
					<span class="stat__label"><?php echo esc_html( $najdisvujsen_stat['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php
najdisvujsen_section_close();
