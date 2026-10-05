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
		<?php $najdisvujsen_video = najdisvujsen_sanitize_video_id( $najdisvujsen_data['video'] ); ?>
		<?php if ( $najdisvujsen_video ) : ?>
			<div class="city-video" data-city-video="<?php echo esc_attr( $najdisvujsen_video ); ?>">
				<picture>
					<source type="image/webp" srcset="<?php echo esc_url( 'https://i.ytimg.com/vi_webp/' . $najdisvujsen_video . '/maxresdefault.webp' ); ?>">
					<img class="city-video__poster" src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $najdisvujsen_video . '/maxresdefault.jpg' ); ?>" width="1280" height="720" alt="" loading="lazy" decoding="async">
				</picture>
				<a class="video__play" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $najdisvujsen_video ); ?>" data-city-video-play>
					<?php echo wp_kses( najdisvujsen_icon( 'play' ), najdisvujsen_icon_kses() ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Přehrát video o Olomouci', 'najdisvujsen' ); ?></span>
				</a>
			</div>
		<?php elseif ( $najdisvujsen_data['photo'] ) : ?>
			<?php najdisvujsen_photo( $najdisvujsen_data['photo'], '(min-width: 900px) 560px, 100vw', 'photo--big', 'city' ); ?>
		<?php endif; ?>
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
