<?php
/**
 * Template part for the announcement below the front page header.
 *
 * @package NajdiSvujSen
 * @since 0.7.0
 */

$najdisvujsen_data = najdisvujsen_announcement_data();

if ( 'shown' !== najdisvujsen_announcement_status( $najdisvujsen_data ) ) {
	return;
}

$najdisvujsen_link = '' !== $najdisvujsen_data['link_label'] && '' !== $najdisvujsen_data['link_url'];
?>
<section id="oznameni" class="announcement" aria-label="<?php esc_attr_e( 'Mimořádné oznámení', 'najdisvujsen' ); ?>">
	<div class="container container--wide">
		<div class="announcement__bubble">
			<span class="announcement__icon" aria-hidden="true"><?php echo wp_kses( najdisvujsen_icon( 'exclamation' ), najdisvujsen_icon_kses() ); ?></span>
			<div class="announcement__body">
				<?php if ( '' !== $najdisvujsen_data['title'] ) : ?>
					<p class="announcement__title"><?php echo esc_html( najdisvujsen_nbsp( $najdisvujsen_data['title'] ) ); ?></p>
				<?php endif; ?>
				<?php najdisvujsen_prose( $najdisvujsen_data['text'], 'announcement__text' ); ?>
			</div>
			<?php if ( $najdisvujsen_link ) : ?>
				<a class="btn announcement__link" href="<?php echo esc_url( $najdisvujsen_data['link_url'] ); ?>">
					<?php echo esc_html( $najdisvujsen_data['link_label'] ); ?>
					<?php echo wp_kses( najdisvujsen_icon( 'arrow-right' ), najdisvujsen_icon_kses() ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
