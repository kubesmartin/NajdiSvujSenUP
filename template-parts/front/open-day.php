<?php
/**
 * Template part for the open days section of the front page.
 *
 * Past dates are left out.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_data    = $args['data'];
$najdisvujsen_dates   = najdisvujsen_open_days( $najdisvujsen_data['dates'] );
$najdisvujsen_gallery = $najdisvujsen_data['photos'];
$najdisvujsen_section = array(
	'anchor' => 'dod',
	'title'  => $najdisvujsen_data['title'],
);

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => 'night',
		'type'    => 'open-day',
		'heading' => false,
	)
);
?>
<div class="split split--top">
	<div>
		<h2 class="section__title"><?php echo esc_html( $najdisvujsen_data['title'] ); ?></h2>
		<?php najdisvujsen_prose( $najdisvujsen_data['text'] ); ?>
	</div>
	<?php if ( $najdisvujsen_dates ) : ?>
		<div class="open-days">
			<ul class="open-days__list">
				<?php foreach ( $najdisvujsen_dates as $najdisvujsen_date ) : ?>
					<li class="date-pill">
						<span class="sticker pulse" aria-hidden="true"><?php echo wp_kses( najdisvujsen_icon( 'calendar-heart' ), najdisvujsen_icon_kses() ); ?></span>
						<span class="date-pill__date">
							<span class="date-pill__day"><?php echo esc_html( $najdisvujsen_date['day'] ); ?></span>
							<span class="date-pill__value"><?php echo esc_html( $najdisvujsen_date['date'] ); ?></span>
						</span>
						<span class="date-pill__time"><?php echo esc_html( $najdisvujsen_date['time'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $najdisvujsen_data['links'] ) : ?>
				<div class="cta-row">
					<?php foreach ( $najdisvujsen_data['links'] as $najdisvujsen_index => $najdisvujsen_link ) : ?>
						<a class="btn <?php echo 0 === $najdisvujsen_index ? 'btn--primary' : 'btn--outline'; ?>" href="<?php echo esc_url( $najdisvujsen_link['url'] ); ?>">
							<?php echo esc_html( $najdisvujsen_link['label'] ); ?>
							<?php
							if ( 0 === $najdisvujsen_index ) {
								echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() );
							}
							?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ( '' !== $najdisvujsen_data['note'] ) : ?>
				<p class="open-days__note"><?php echo esc_html( $najdisvujsen_data['note'] ); ?> <span aria-hidden="true">👋</span></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<?php if ( $najdisvujsen_gallery ) : ?>
	<div class="collage collage--4 collage--mosaic">
		<?php
		foreach ( $najdisvujsen_gallery as $najdisvujsen_index => $najdisvujsen_id ) {
			najdisvujsen_photo(
				$najdisvujsen_id,
				0 === $najdisvujsen_index ? '(min-width: 900px) 600px, 100vw' : '(min-width: 900px) 300px, 50vw',
				0 === $najdisvujsen_index ? 'photo--big' : ( 3 === $najdisvujsen_index ? 'photo--wide' : '' ),
				'dod'
			);
		}
		?>
	</div>
<?php endif; ?>
<?php
najdisvujsen_section_close();
