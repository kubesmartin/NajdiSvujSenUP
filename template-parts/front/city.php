<?php
/**
 * Template part for the "University town" section of the front page.
 *
 * A short closing paragraph is set as a statement.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section   = $args['section'];
$najdisvujsen_blocks    = $najdisvujsen_section['blocks'];
$najdisvujsen_statement = '';
$najdisvujsen_last      = end( $najdisvujsen_blocks );

if ( $najdisvujsen_last && 'core/paragraph' === $najdisvujsen_last['blockName'] && mb_strlen( najdisvujsen_block_text( $najdisvujsen_last ) ) <= 160 ) {
	$najdisvujsen_statement = najdisvujsen_block_inner_html( array_pop( $najdisvujsen_blocks ) );
}

$najdisvujsen_stats = array(
	array( 21000, '+', __( 'studentů', 'najdisvujsen' ) ),
	array( 100000, '+', __( 'obyvatel', 'najdisvujsen' ) ),
	array( 39, '', __( 'studentských spolků na FF', 'najdisvujsen' ) ),
	array( 7, '', __( 'budov fakulty', 'najdisvujsen' ) ),
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
		<h2 class="section__title"><?php echo wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_blocks ); ?>
		<?php if ( $najdisvujsen_statement ) : ?>
			<p class="statement"><?php echo wp_kses( $najdisvujsen_statement, najdisvujsen_inline_kses() ); ?></p>
		<?php endif; ?>
	</div>
	<div class="city-media">
		<?php
		if ( $args['photo'] ) {
			najdisvujsen_photo( (int) $args['photo'], '(min-width: 900px) 560px, 100vw', 'photo--big', 'city' );
		}
		?>
		<ul class="city-stats">
			<?php foreach ( $najdisvujsen_stats as $najdisvujsen_stat ) : ?>
				<li class="stat">
					<span class="stat__num" data-count="<?php echo esc_attr( (string) $najdisvujsen_stat[0] ); ?>" data-suffix="<?php echo esc_attr( $najdisvujsen_stat[1] ); ?>"><?php echo esc_html( number_format_i18n( $najdisvujsen_stat[0] ) . $najdisvujsen_stat[1] ); ?></span>
					<span class="stat__label"><?php echo esc_html( $najdisvujsen_stat[2] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php
najdisvujsen_section_close();
