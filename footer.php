<?php
/**
 * The footer template.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

$najdisvujsen_platforms = array(
	array(
		'icon'  => 'facebook',
		'name'  => 'Facebook',
		'links' => array(
			array( __( 'Univerzita na Facebooku', 'najdisvujsen' ), 'https://www.facebook.com/univerzita.palackeho/' ),
			array( __( 'Fakulta na Facebooku', 'najdisvujsen' ), 'https://www.facebook.com/ffup.cz/' ),
			array( __( 'Oficiální fórum studentů UP', 'najdisvujsen' ), 'https://www.facebook.com/groups/164914413030/' ),
		),
	),
	array(
		'icon'  => 'instagram',
		'name'  => 'Instagram',
		'links' => array(
			array( __( 'Univerzita na Instagramu', 'najdisvujsen' ), 'https://www.instagram.com/univerzita.palackeho/' ),
			array( __( 'Fakulta na Instagramu', 'najdisvujsen' ), 'https://www.instagram.com/ff_upol/' ),
		),
	),
	array(
		'icon'  => 'music-2',
		'name'  => 'TikTok',
		'links' => array(
			array( __( 'Fakulta na TikToku', 'najdisvujsen' ), 'https://www.tiktok.com/@ff_upol' ),
		),
	),
);
$najdisvujsen_legal     = array_filter(
	array(
		__( 'Ochrana osobních údajů', 'najdisvujsen' )    => najdisvujsen_page_url( 'ochrana-osobnich-udaju-na-up' ),
		__( 'Zásady zpracování cookies', 'najdisvujsen' ) => najdisvujsen_page_url( 'zasady-cookies-eu' ),
	)
);
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__inner">
		<div class="social-cards">
			<?php foreach ( $najdisvujsen_platforms as $najdisvujsen_platform ) : ?>
				<div class="social-card">
					<p class="social-card__head">
						<span class="social-card__icon"><?php echo wp_kses( najdisvujsen_icon( $najdisvujsen_platform['icon'] ), najdisvujsen_icon_kses() ); ?></span>
						<span class="social-card__name"><?php echo esc_html( $najdisvujsen_platform['name'] ); ?></span>
					</p>
					<ul class="social-card__links">
						<?php foreach ( $najdisvujsen_platform['links'] as $najdisvujsen_link ) : ?>
							<li>
								<a href="<?php echo esc_url( $najdisvujsen_link[1] ); ?>" rel="noopener">
									<span><?php echo esc_html( $najdisvujsen_link[0] ); ?></span>
									<?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="site-footer__info">
			<img class="site-footer__logo" src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/logo-ff-up-white.png' ); ?>" width="317" height="120" alt="<?php esc_attr_e( 'Filozofická fakulta Univerzity Palackého v Olomouci', 'najdisvujsen' ); ?>" loading="lazy" decoding="async">

			<address class="site-footer__address">
				<strong><?php esc_html_e( 'Filozofická fakulta', 'najdisvujsen' ); ?><br><?php esc_html_e( 'Univerzity Palackého v Olomouci', 'najdisvujsen' ); ?></strong><br>
				Křížkovského 511/10<br>
				771&nbsp;48 Olomouc<br>
				<a class="footer-link" href="https://www.ff.upol.cz">www.ff.upol.cz</a>
			</address>

			<div class="site-footer__apps">
				<p><?php esc_html_e( 'Mobilní aplikace UPlikace:', 'najdisvujsen' ); ?></p>
				<a href="https://play.google.com/store/apps/details?id=cz.uplikace.app" rel="noopener"><img src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/google-play.webp' ); ?>" width="400" height="134" alt="<?php esc_attr_e( 'Stáhnout na Google Play', 'najdisvujsen' ); ?>" loading="lazy" decoding="async"></a>
				<a href="https://apps.apple.com/cz/app/uplikace/id1386643214" rel="noopener"><img src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/app-store.webp' ); ?>" width="398" height="118" alt="<?php esc_attr_e( 'Stáhnout v App Store', 'najdisvujsen' ); ?>" loading="lazy" decoding="async"></a>
			</div>
		</div>

		<?php if ( $najdisvujsen_legal || has_nav_menu( 'footer' ) ) : ?>
			<nav class="site-footer__legal" aria-label="<?php esc_attr_e( 'Právní informace', 'najdisvujsen' ); ?>">
				<?php foreach ( $najdisvujsen_legal as $najdisvujsen_title => $najdisvujsen_url ) : ?>
					<a class="footer-link" href="<?php echo esc_url( $najdisvujsen_url ); ?>"><?php echo esc_html( $najdisvujsen_title ); ?></a>
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
