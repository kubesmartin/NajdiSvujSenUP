<?php
/**
 * Footer and social networks: edit form and stored values.
 *
 * @package NajdiSvujSen
 * @since 0.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the social networks with their icons.
 *
 * @since 0.6.0
 *
 * @return array[] Networks keyed by name, with label and icon.
 */
function najdisvujsen_social_networks() {
	return array(
		'facebook'  => array(
			'label' => 'Facebook',
			'icon'  => 'facebook',
		),
		'instagram' => array(
			'label' => 'Instagram',
			'icon'  => 'instagram',
		),
		'youtube'   => array(
			'label' => 'YouTube',
			'icon'  => 'youtube',
		),
		'tiktok'    => array(
			'label' => 'TikTok',
			'icon'  => 'music-2',
		),
	);
}

/**
 * Returns the edit form definition of the footer.
 *
 * @since 0.6.0
 *
 * @return array[] Tabs.
 */
function najdisvujsen_footer_schema() {
	$networks = wp_list_pluck( najdisvujsen_social_networks(), 'label' );

	return array(
		'site'    => array(
			'label'  => __( 'Sociální sítě fakulty', 'najdisvujsen' ),
			'icon'   => 'share',
			'intro'  => __( 'Kulaté ikony sociálních sítí v úvodu titulní stránky. Prázdné se nezobrazí.', 'najdisvujsen' ),
			'fields' => array(
				'facebook'  => array(
					'type'    => 'url',
					'label'   => 'Facebook',
					'default' => 'https://www.facebook.com/ffup.cz/',
				),
				'instagram' => array(
					'type'    => 'url',
					'label'   => 'Instagram',
					'default' => 'https://www.instagram.com/ff_upol/',
				),
				'youtube'   => array(
					'type'    => 'url',
					'label'   => 'YouTube',
					'default' => 'https://www.youtube.com/playlist?list=PLIsod3TXTdoLg-Yut3M3WgyBEqMZjAqFb',
				),
				'tiktok'    => array(
					'type'    => 'url',
					'label'   => 'TikTok',
					'default' => 'https://www.tiktok.com/@ff_upol',
				),
			),
		),
		'socials' => array(
			'label'  => __( 'Sociální sítě v patičce', 'najdisvujsen' ),
			'icon'   => 'networking',
			'intro'  => __( 'Odkazy v patičce, seskupené podle sítě do karet (Facebook, Instagram…). Pořadí změníte přetažením.', 'najdisvujsen' ),
			'fields' => array(
				'links' => array(
					'type'    => 'repeater',
					'label'   => __( 'Odkazy', 'najdisvujsen' ),
					'add'     => __( 'Přidat odkaz', 'najdisvujsen' ),
					'item'    => __( 'Odkaz', 'najdisvujsen' ),
					'title'   => 'label',
					'badge'   => 'network',
					'fields'  => array(
						'network' => array(
							'type'     => 'radio',
							'label'    => __( 'Síť', 'najdisvujsen' ),
							'options'  => $networks,
							'required' => true,
						),
						'label'   => array(
							'type'        => 'text',
							'label'       => __( 'Text odkazu', 'najdisvujsen' ),
							'placeholder' => __( 'např. Fakulta na Facebooku', 'najdisvujsen' ),
							'required'    => true,
						),
						'url'     => array(
							'type'     => 'url',
							'label'    => __( 'Odkaz', 'najdisvujsen' ),
							'required' => true,
						),
					),
					'default' => array(
						array(
							'network' => 'facebook',
							'label'   => 'Univerzita na Facebooku',
							'url'     => 'https://www.facebook.com/univerzita.palackeho/',
						),
						array(
							'network' => 'facebook',
							'label'   => 'Fakulta na Facebooku',
							'url'     => 'https://www.facebook.com/ffup.cz/',
						),
						array(
							'network' => 'facebook',
							'label'   => 'Oficiální fórum studentů UP',
							'url'     => 'https://www.facebook.com/groups/164914413030/',
						),
						array(
							'network' => 'instagram',
							'label'   => 'Univerzita na Instagramu',
							'url'     => 'https://www.instagram.com/univerzita.palackeho/',
						),
						array(
							'network' => 'instagram',
							'label'   => 'Fakulta na Instagramu',
							'url'     => 'https://www.instagram.com/ff_upol/',
						),
						array(
							'network' => 'tiktok',
							'label'   => 'Fakulta na TikToku',
							'url'     => 'https://www.tiktok.com/@ff_upol',
						),
					),
				),
			),
		),
		'contact' => array(
			'label'  => __( 'Kontakt', 'najdisvujsen' ),
			'icon'   => 'location',
			'intro'  => __( 'Adresa fakulty pod logem v patičce.', 'najdisvujsen' ),
			'fields' => array(
				'name'     => array(
					'type'    => 'textarea',
					'label'   => __( 'Název (tučně)', 'najdisvujsen' ),
					'rows'    => 2,
					'default' => "Filozofická fakulta\nUniverzity Palackého v Olomouci",
				),
				'address'  => array(
					'type'    => 'textarea',
					'label'   => __( 'Adresa', 'najdisvujsen' ),
					'help'    => __( 'Každý řádek adresy na nový řádek.', 'najdisvujsen' ),
					'rows'    => 3,
					'default' => "Křížkovského 511/10\n771 48 Olomouc",
				),
				'web'      => array(
					'type'    => 'url',
					'label'   => __( 'Web fakulty', 'najdisvujsen' ),
					'default' => 'https://www.ff.upol.cz',
				),
				'web_text' => array(
					'type'    => 'text',
					'label'   => __( 'Text odkazu na web', 'najdisvujsen' ),
					'default' => 'www.ff.upol.cz',
				),
			),
		),
		'apps'    => array(
			'label'  => __( 'Mobilní aplikace', 'najdisvujsen' ),
			'icon'   => 'smartphone',
			'intro'  => __( 'Tlačítka obchodů s aplikacemi v patičce. Prázdný odkaz tlačítko skryje.', 'najdisvujsen' ),
			'fields' => array(
				'label'       => array(
					'type'    => 'text',
					'label'   => __( 'Nadpis', 'najdisvujsen' ),
					'default' => 'Mobilní aplikace UPlikace:',
				),
				'google_play' => array(
					'type'    => 'url',
					'label'   => 'Google Play',
					'default' => 'https://play.google.com/store/apps/details?id=cz.uplikace.app',
				),
				'app_store'   => array(
					'type'    => 'url',
					'label'   => 'App Store',
					'default' => 'https://apps.apple.com/cz/app/uplikace/id1386643214',
				),
			),
		),
		'legal'   => array(
			'label'  => __( 'Odkazy na konci stránky', 'najdisvujsen' ),
			'icon'   => 'admin-links',
			'intro'  => __( 'Drobné odkazy úplně dole, např. ochrana osobních údajů a cookies.', 'najdisvujsen' ),
			'fields' => array(
				'links' => array(
					'type'    => 'repeater',
					'label'   => __( 'Odkazy', 'najdisvujsen' ),
					'add'     => __( 'Přidat odkaz', 'najdisvujsen' ),
					'item'    => __( 'Odkaz', 'najdisvujsen' ),
					'title'   => 'label',
					'fields'  => array(
						'label' => array(
							'type'     => 'text',
							'label'    => __( 'Text odkazu', 'najdisvujsen' ),
							'required' => true,
						),
						'url'   => array(
							'type'     => 'url',
							'label'    => __( 'Odkaz', 'najdisvujsen' ),
							'help'     => __( 'Celý odkaz včetně https://.', 'najdisvujsen' ),
							'required' => true,
						),
					),
					'default' => array(
						array(
							'label' => 'Ochrana osobních údajů',
							'url'   => home_url( '/ochrana-osobnich-udaju-na-up/' ),
						),
						array(
							'label' => 'Zásady zpracování cookies',
							'url'   => home_url( '/zasady-cookies-eu/' ),
						),
					),
				),
			),
		),
	);
}

/**
 * Returns the footer values with defaults for anything not saved yet.
 *
 * @since 0.6.0
 *
 * @return array Values.
 */
function najdisvujsen_footer_data() {
	return najdisvujsen_fields_fill( najdisvujsen_schema_fields( najdisvujsen_footer_schema() ), get_option( 'najdisvujsen_footer', array() ) );
}

/**
 * Adds the footer settings screen.
 *
 * @since 0.6.0
 */
function najdisvujsen_footer_menu() {
	$hook = add_menu_page(
		__( 'Patička a sociální sítě', 'najdisvujsen' ),
		__( 'Patička a sítě', 'najdisvujsen' ),
		'edit_others_pages',
		'najdisvujsen-footer',
		'najdisvujsen_footer_page',
		'dashicons-share',
		58
	);

	add_action( 'load-' . $hook, 'najdisvujsen_footer_load' );
}
add_action( 'admin_menu', 'najdisvujsen_footer_menu' );

/**
 * Saves the footer settings and loads the form assets.
 *
 * @since 0.6.0
 */
function najdisvujsen_footer_load() {
	add_action( 'admin_enqueue_scripts', 'najdisvujsen_form_assets' );

	if ( ! isset( $_POST['najdisvujsen_footer_nonce'] ) ) {
		return;
	}

	check_admin_referer( 'najdisvujsen_footer', 'najdisvujsen_footer_nonce' );

	if ( ! current_user_can( 'edit_others_pages' ) ) {
		wp_die( esc_html__( 'Na úpravu patičky nemáte oprávnění.', 'najdisvujsen' ) );
	}

	$input  = isset( $_POST[ NAJDISVUJSEN_FORM ] ) ? wp_unslash( (array) $_POST[ NAJDISVUJSEN_FORM ] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized per field.
	$values = najdisvujsen_fields_sanitize( najdisvujsen_schema_fields( najdisvujsen_footer_schema() ), $input );

	update_option( 'najdisvujsen_footer', $values, false );
	wp_safe_redirect( add_query_arg( 'updated', '1', menu_page_url( 'najdisvujsen-footer', false ) ) );
	exit;
}

/**
 * Prints the footer settings screen.
 *
 * @since 0.6.0
 */
function najdisvujsen_footer_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Patička a sociální sítě', 'najdisvujsen' ); ?></h1>
		<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only shows a notice. ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Patička byla uložena.', 'najdisvujsen' ); ?></p></div>
		<?php endif; ?>
		<form method="post" class="nsj-settings-form">
			<?php
			wp_nonce_field( 'najdisvujsen_footer', 'najdisvujsen_footer_nonce' );
			najdisvujsen_form_tabs(
				najdisvujsen_footer_schema(),
				najdisvujsen_footer_data(),
				array(
					'id'        => 'footer',
					'permalink' => home_url( '/' ),
					'anchors'   => array(
						'site'    => '',
						'socials' => 'paticka',
						'contact' => 'paticka',
						'apps'    => 'paticka',
						'legal'   => 'paticka',
					),
				)
			);
			submit_button( __( 'Uložit patičku', 'najdisvujsen' ) );
			?>
		</form>
	</div>
	<?php
}
