<?php
/**
 * Site settings: links to the application and admission information.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the admission link settings with their labels and defaults.
 *
 * @since 0.3.0
 *
 * @return array[] Settings keyed by theme mod name.
 */
function najdisvujsen_admission_settings() {
	return array(
		'najdisvujsen_application_url' => array(
			'label'   => __( 'Odkaz na e-přihlášku', 'najdisvujsen' ),
			'help'    => __( 'Kam vedou všechna tlačítka „Podat přihlášku“.', 'najdisvujsen' ),
			'default' => 'https://prihlaska.upol.cz/prihlaska/info.xhtml',
		),
		'najdisvujsen_admission_url'   => array(
			'label'   => __( 'Odkaz na informace o přijímacím řízení', 'najdisvujsen' ),
			'help'    => __( 'Kam vedou tlačítka „Přijímací řízení“. Každý rok zkontrolujte, že odkaz vede na aktuální ročník.', 'najdisvujsen' ),
			'default' => 'https://www.ff.upol.cz/uchazecum/prijimaci-rizeni-202526/',
		),
	);
}

/**
 * Returns an admission link.
 *
 * @since 0.3.0
 *
 * @param string $name Setting name.
 * @return string URL.
 */
function najdisvujsen_admission_url( $name ) {
	$settings = najdisvujsen_admission_settings();

	return (string) get_theme_mod( $name, $settings[ $name ]['default'] ?? '' );
}

/**
 * Extracts a YouTube video ID from a link or an ID.
 *
 * @since 0.3.0
 *
 * @param string $value Link or ID.
 * @return string Video ID, or an empty string.
 */
function najdisvujsen_sanitize_video_id( $value ) {
	$value = trim( (string) $value );

	if ( preg_match( '#(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{11})#', $value, $matches ) ) {
		return $matches[1];
	}

	return preg_match( '/^[A-Za-z0-9_-]{11}$/', $value ) ? $value : '';
}

/**
 * Adds the site settings screen.
 *
 * @since 0.5.0
 */
function najdisvujsen_settings_menu() {
	add_menu_page(
		__( 'Odkazy na přihlášku a přijímačky', 'najdisvujsen' ),
		__( 'Odkazy na přihlášku a přijímačky', 'najdisvujsen' ),
		'edit_others_pages',
		'najdisvujsen-settings',
		'najdisvujsen_settings_page',
		'dashicons-admin-links',
		59
	);
}
add_action( 'admin_menu', 'najdisvujsen_settings_menu' );

/**
 * Saves the site settings.
 *
 * @since 0.5.0
 */
function najdisvujsen_settings_save() {
	if ( ! isset( $_POST['najdisvujsen_settings_nonce'] ) || ! current_user_can( 'edit_others_pages' ) ) {
		return;
	}

	check_admin_referer( 'najdisvujsen_settings', 'najdisvujsen_settings_nonce' );

	foreach ( array_keys( najdisvujsen_admission_settings() ) as $name ) {
		$value = isset( $_POST[ $name ] ) ? esc_url_raw( trim( wp_unslash( (string) $_POST[ $name ] ) ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized by esc_url_raw().

		if ( '' === $value ) {
			remove_theme_mod( $name );
		} else {
			set_theme_mod( $name, $value );
		}
	}

	wp_safe_redirect( add_query_arg( 'updated', '1', menu_page_url( 'najdisvujsen-settings', false ) ) );
	exit;
}
add_action( 'admin_init', 'najdisvujsen_settings_save' );

/**
 * Prints the site settings screen.
 *
 * @since 0.5.0
 */
function najdisvujsen_settings_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Odkazy na přihlášku a přijímačky', 'najdisvujsen' ); ?></h1>
		<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only shows a notice. ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Nastavení bylo uloženo.', 'najdisvujsen' ); ?></p></div>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'najdisvujsen_settings', 'najdisvujsen_settings_nonce' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( najdisvujsen_admission_settings() as $name => $setting ) : ?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $setting['label'] ); ?></label></th>
						<td>
							<input type="url" class="large-text" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( najdisvujsen_admission_url( $name ) ); ?>">
							<p class="description"><?php echo esc_html( $setting['help'] ); ?> <?php esc_html_e( 'Prázdné pole vrátí výchozí odkaz.', 'najdisvujsen' ); ?></p>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button( __( 'Uložit nastavení', 'najdisvujsen' ) ); ?>
		</form>
	</div>
	<?php
}
