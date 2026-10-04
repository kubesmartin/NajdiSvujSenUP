<?php
/**
 * Front-end assets.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueues theme styles.
 *
 * @since 0.1.0
 */
function najdisvujsen_enqueue_assets() {
	wp_enqueue_style(
		'najdisvujsen-main',
		NAJDISVUJSEN_URI . '/assets/css/main.css',
		array(),
		NAJDISVUJSEN_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'najdisvujsen_enqueue_assets' );
