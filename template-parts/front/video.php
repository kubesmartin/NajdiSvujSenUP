<?php
/**
 * Template part for the campaign video on the front page.
 *
 * Only the poster is loaded with the page; the player is embedded by the
 * script once the section scrolls into view.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_video = najdisvujsen_sanitize_video_id( $args['data']['youtube'] );

if ( ! $najdisvujsen_video ) {
	return;
}
?>
<section class="video" data-video="<?php echo esc_attr( $najdisvujsen_video ); ?>" aria-label="<?php esc_attr_e( 'Video Najdi svůj sen na FF UP', 'najdisvujsen' ); ?>">
	<picture>
		<source type="image/webp" srcset="<?php echo esc_url( 'https://i.ytimg.com/vi_webp/' . $najdisvujsen_video . '/maxresdefault.webp' ); ?>">
		<img class="video__poster" src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $najdisvujsen_video . '/maxresdefault.jpg' ); ?>" width="1280" height="720" alt="" loading="lazy" decoding="async">
	</picture>
	<a class="video__play" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $najdisvujsen_video ); ?>" data-video-play>
		<?php echo wp_kses( najdisvujsen_icon( 'play' ), najdisvujsen_icon_kses() ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Přehrát video', 'najdisvujsen' ); ?></span>
	</a>
	<div class="video__controls" hidden>
		<button type="button" class="video__btn" data-video-toggle aria-label="<?php esc_attr_e( 'Pozastavit video', 'najdisvujsen' ); ?>">
			<?php echo wp_kses( najdisvujsen_icon( 'pause', 'video__icon-pause' ), najdisvujsen_icon_kses() ); ?>
			<?php echo wp_kses( najdisvujsen_icon( 'play', 'video__icon-play' ), najdisvujsen_icon_kses() ); ?>
		</button>
		<button type="button" class="video__btn video__btn--wide" data-video-sound>
			<?php echo wp_kses( najdisvujsen_icon( 'volume-x', 'video__icon-muted' ), najdisvujsen_icon_kses() ); ?>
			<?php echo wp_kses( najdisvujsen_icon( 'volume-2', 'video__icon-sound' ), najdisvujsen_icon_kses() ); ?>
			<span data-video-sound-label><?php esc_html_e( 'Zapnout zvuk', 'najdisvujsen' ); ?></span>
		</button>
	</div>
</section>
