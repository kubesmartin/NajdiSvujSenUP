<?php
/**
 * The footer template.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

$najdisvujsen_footer   = najdisvujsen_footer_data();
$najdisvujsen_networks = najdisvujsen_social_networks();
$najdisvujsen_cards    = array();

foreach ( $najdisvujsen_footer['socials']['links'] as $najdisvujsen_link ) {
	if ( isset( $najdisvujsen_networks[ $najdisvujsen_link['network'] ] ) ) {
		$najdisvujsen_cards[ $najdisvujsen_link['network'] ][] = $najdisvujsen_link;
	}
}
?>
</main>

<footer class="site-footer" id="paticka">
	<div class="container site-footer__inner">
		<?php if ( $najdisvujsen_cards ) : ?>
		<div class="social-cards">
			<?php foreach ( $najdisvujsen_cards as $najdisvujsen_network => $najdisvujsen_links ) : ?>
				<div class="social-card">
					<p class="social-card__head">
						<span class="social-card__icon"><?php echo wp_kses( najdisvujsen_icon( $najdisvujsen_networks[ $najdisvujsen_network ]['icon'] ), najdisvujsen_icon_kses() ); ?></span>
						<span class="social-card__name"><?php echo esc_html( $najdisvujsen_networks[ $najdisvujsen_network ]['label'] ); ?></span>
					</p>
					<ul class="social-card__links">
						<?php foreach ( $najdisvujsen_links as $najdisvujsen_link ) : ?>
							<li>
								<a href="<?php echo esc_url( $najdisvujsen_link['url'] ); ?>" rel="noopener">
									<span><?php echo esc_html( $najdisvujsen_link['label'] ); ?></span>
									<?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="site-footer__info">
			<img class="site-footer__logo" src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/logo-ff-up-white.png' ); ?>" width="317" height="120" alt="<?php esc_attr_e( 'Filozofická fakulta Univerzity Palackého v Olomouci', 'najdisvujsen' ); ?>" loading="lazy" decoding="async">

			<address class="site-footer__address">
				<strong><?php echo wp_kses( nl2br( esc_html( $najdisvujsen_footer['contact']['name'] ), false ), array( 'br' => array() ) ); ?></strong><br>
				<?php echo wp_kses( nl2br( esc_html( najdisvujsen_nbsp( $najdisvujsen_footer['contact']['address'] ) ), false ), array( 'br' => array() ) ); ?><br>
				<?php if ( $najdisvujsen_footer['contact']['web'] ) : ?>
					<a class="footer-link" href="<?php echo esc_url( $najdisvujsen_footer['contact']['web'] ); ?>"><?php echo esc_html( $najdisvujsen_footer['contact']['web_text'] ? $najdisvujsen_footer['contact']['web_text'] : $najdisvujsen_footer['contact']['web'] ); ?></a>
				<?php endif; ?>
			</address>

			<?php if ( $najdisvujsen_footer['apps']['google_play'] || $najdisvujsen_footer['apps']['app_store'] ) : ?>
				<div class="site-footer__apps">
					<p><?php echo esc_html( $najdisvujsen_footer['apps']['label'] ); ?></p>
					<?php if ( $najdisvujsen_footer['apps']['google_play'] ) : ?>
						<a href="<?php echo esc_url( $najdisvujsen_footer['apps']['google_play'] ); ?>" rel="noopener"><img src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/google-play.webp' ); ?>" width="400" height="134" alt="<?php esc_attr_e( 'Stáhnout na Google Play', 'najdisvujsen' ); ?>" loading="lazy" decoding="async"></a>
					<?php endif; ?>
					<?php if ( $najdisvujsen_footer['apps']['app_store'] ) : ?>
						<a href="<?php echo esc_url( $najdisvujsen_footer['apps']['app_store'] ); ?>" rel="noopener"><img src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/app-store.webp' ); ?>" width="398" height="118" alt="<?php esc_attr_e( 'Stáhnout v App Store', 'najdisvujsen' ); ?>" loading="lazy" decoding="async"></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $najdisvujsen_footer['legal']['links'] || has_nav_menu( 'footer' ) ) : ?>
			<nav class="site-footer__legal" aria-label="<?php esc_attr_e( 'Právní informace', 'najdisvujsen' ); ?>">
				<?php foreach ( $najdisvujsen_footer['legal']['links'] as $najdisvujsen_link ) : ?>
					<a class="footer-link" href="<?php echo esc_url( $najdisvujsen_link['url'] ); ?>"><?php echo esc_html( $najdisvujsen_link['label'] ); ?></a>
				<?php endforeach; ?>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'walker'         => new Najdisvujsen_Flat_Nav_Walker(),
						)
					);
				}
				?>
			</nav>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
