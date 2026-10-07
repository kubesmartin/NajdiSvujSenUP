<?php
/**
 * Announcement shown below the front page header.
 *
 * @package NajdiSvujSen
 * @since 0.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the edit form definition of the announcement.
 *
 * @since 0.7.0
 *
 * @return array[] Tabs.
 */
function najdisvujsen_announcement_schema() {
	return array(
		'zakladni' => array(
			'label'  => __( 'Mimořádné oznámení', 'najdisvujsen' ),
			'icon'   => 'warning',
			'intro'  => __( 'Žlutý pruh na titulní stránce hned pod úvodem. Pro důležité a časově omezené informace, např. změnu termínu přijímaček nebo uzavření budovy.', 'najdisvujsen' ),
			'fields' => array(
				'enabled'    => array(
					'type'  => 'toggle',
					'label' => __( 'Zobrazit oznámení na webu', 'najdisvujsen' ),
					'help'  => __( 'Vypnuté oznámení zůstane uložené pro příště.', 'najdisvujsen' ),
				),
				'title'      => array(
					'type'        => 'text',
					'label'       => __( 'Nadpis', 'najdisvujsen' ),
					'placeholder' => __( 'např. Změna termínu přijímací zkoušky', 'najdisvujsen' ),
					'help'        => __( 'Nepovinné. Krátce, zobrazí se tučně.', 'najdisvujsen' ),
					'max'         => 80,
				),
				'text'       => array(
					'type'     => 'rich',
					'label'    => __( 'Text oznámení', 'najdisvujsen' ),
					'help'     => __( 'Jedna až dvě věty. Tučné písmo a odkazy fungují.', 'najdisvujsen' ),
					'lite'     => true,
					'required' => true,
				),
				'link_label' => array(
					'type'        => 'text',
					'label'       => __( 'Text tlačítka', 'najdisvujsen' ),
					'placeholder' => __( 'např. Více informací', 'najdisvujsen' ),
					'help'        => __( 'Nepovinné. Tlačítko se zobrazí, jen když je vyplněný i odkaz.', 'najdisvujsen' ),
					'max'         => 30,
				),
				'link_url'   => array(
					'type'        => 'url',
					'label'       => __( 'Odkaz tlačítka', 'najdisvujsen' ),
					'placeholder' => 'https://',
				),
				'from'       => array(
					'type'  => 'date',
					'label' => __( 'Zobrazit od', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné. Prázdné = hned.', 'najdisvujsen' ),
				),
				'until'      => array(
					'type'  => 'date',
					'label' => __( 'Zobrazit do (včetně)', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné. Po tomto dni oznámení samo zmizí. Prázdné = dokud ho nevypnete.', 'najdisvujsen' ),
				),
			),
		),
	);
}

/**
 * Returns the stored announcement.
 *
 * @since 0.7.0
 *
 * @return array Values.
 */
function najdisvujsen_announcement_data() {
	return najdisvujsen_fields_fill( najdisvujsen_schema_fields( najdisvujsen_announcement_schema() ), get_option( 'najdisvujsen_announcement', array() ) );
}

/**
 * Returns why the announcement is or is not shown today.
 *
 * @since 0.7.0
 *
 * @param array $data Announcement values.
 * @return string One of shown, off, empty, scheduled or expired.
 */
function najdisvujsen_announcement_status( $data ) {
	$today = wp_date( 'Y-m-d' );

	if ( ! $data['enabled'] ) {
		return 'off';
	}

	if ( '' === trim( wp_strip_all_tags( $data['text'] ) ) ) {
		return 'empty';
	}

	if ( '' !== $data['from'] && $data['from'] > $today ) {
		return 'scheduled';
	}

	if ( '' !== $data['until'] && $data['until'] < $today ) {
		return 'expired';
	}

	return 'shown';
}

/**
 * Returns a sentence describing the announcement status for editors.
 *
 * @since 0.7.0
 *
 * @param array $data Announcement values.
 * @return string Status description.
 */
function najdisvujsen_announcement_status_text( $data ) {
	$format = static function ( $date ) {
		return wp_date( 'j. n. Y', strtotime( $date . ' 12:00:00' ) );
	};

	switch ( najdisvujsen_announcement_status( $data ) ) {
		case 'shown':
			return '' !== $data['until']
				/* translators: %s: last day. */
				? sprintf( __( 'Právě se zobrazuje, do %s.', 'najdisvujsen' ), $format( $data['until'] ) )
				: __( 'Právě se zobrazuje.', 'najdisvujsen' );
		case 'scheduled':
			/* translators: %s: first day. */
			return sprintf( __( 'Zobrazí se od %s.', 'najdisvujsen' ), $format( $data['from'] ) );
		case 'expired':
			/* translators: %s: last day. */
			return sprintf( __( 'Skončilo %s, na webu se nezobrazuje.', 'najdisvujsen' ), $format( $data['until'] ) );
		case 'empty':
			return __( 'Zapnuté, ale bez textu – na webu se nezobrazuje.', 'najdisvujsen' );
		default:
			return __( 'Vypnuto.', 'najdisvujsen' );
	}
}

/**
 * Adds the announcement screen.
 *
 * @since 0.7.0
 */
function najdisvujsen_announcement_menu() {
	$hook = add_menu_page(
		__( 'Mimořádné oznámení', 'najdisvujsen' ),
		__( 'Oznámení', 'najdisvujsen' ),
		'edit_others_pages',
		'najdisvujsen-announcement',
		'najdisvujsen_announcement_page',
		'dashicons-warning',
		4
	);

	add_action( 'load-' . $hook, 'najdisvujsen_announcement_load' );
}
add_action( 'admin_menu', 'najdisvujsen_announcement_menu' );

/**
 * Saves the announcement and loads the form assets.
 *
 * @since 0.7.0
 */
function najdisvujsen_announcement_load() {
	add_action( 'admin_enqueue_scripts', 'najdisvujsen_form_assets' );

	if ( ! isset( $_POST['najdisvujsen_announcement_nonce'] ) ) {
		return;
	}

	check_admin_referer( 'najdisvujsen_announcement', 'najdisvujsen_announcement_nonce' );

	if ( ! current_user_can( 'edit_others_pages' ) ) {
		wp_die( esc_html__( 'Na úpravu oznámení nemáte oprávnění.', 'najdisvujsen' ) );
	}

	$input  = isset( $_POST[ NAJDISVUJSEN_FORM ] ) ? wp_unslash( (array) $_POST[ NAJDISVUJSEN_FORM ] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized per field.
	$values = najdisvujsen_fields_sanitize( najdisvujsen_schema_fields( najdisvujsen_announcement_schema() ), $input );

	update_option( 'najdisvujsen_announcement', $values, false );
	wp_safe_redirect( add_query_arg( 'updated', '1', menu_page_url( 'najdisvujsen-announcement', false ) ) );
	exit;
}

/**
 * Prints the announcement screen.
 *
 * @since 0.7.0
 */
function najdisvujsen_announcement_page() {
	$data   = najdisvujsen_announcement_data();
	$status = najdisvujsen_announcement_status( $data );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Mimořádné oznámení', 'najdisvujsen' ); ?></h1>
		<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only shows a notice. ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Oznámení bylo uloženo.', 'najdisvujsen' ); ?></p></div>
		<?php endif; ?>
		<div class="notice inline <?php echo 'shown' === $status ? 'notice-info' : 'notice-warning'; ?>">
			<p>
				<strong><?php esc_html_e( 'Stav:', 'najdisvujsen' ); ?></strong>
				<?php echo esc_html( najdisvujsen_announcement_status_text( $data ) ); ?>
				<?php if ( 'shown' === $status ) : ?>
					<a href="<?php echo esc_url( home_url( '/#oznameni' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Zobrazit na webu', 'najdisvujsen' ); ?></a>
				<?php endif; ?>
			</p>
		</div>
		<form method="post" class="nsj-settings-form">
			<?php
			wp_nonce_field( 'najdisvujsen_announcement', 'najdisvujsen_announcement_nonce' );
			najdisvujsen_form_tabs(
				najdisvujsen_announcement_schema(),
				najdisvujsen_announcement_data(),
				array( 'id' => 'announcement' )
			);
			submit_button( __( 'Uložit oznámení', 'najdisvujsen' ) );
			?>
		</form>
	</div>
	<?php
}
