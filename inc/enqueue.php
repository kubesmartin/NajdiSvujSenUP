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
		'najdisvujsen-fonts',
		NAJDISVUJSEN_URI . '/assets/css/fonts.css',
		array(),
		NAJDISVUJSEN_VERSION
	);

	wp_enqueue_style(
		'najdisvujsen-main',
		NAJDISVUJSEN_URI . '/assets/css/main.css',
		array( 'najdisvujsen-fonts' ),
		NAJDISVUJSEN_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'najdisvujsen_enqueue_assets' );

/**
 * Preloads the font files used above the fold.
 *
 * @since 0.1.0
 *
 * @param array $resources Resources to preload.
 * @return array Filtered resources.
 */
function najdisvujsen_preload_fonts( $resources ) {
	foreach ( array( 'medium', 'bold' ) as $style ) {
		$resources[] = array(
			'href'        => NAJDISVUJSEN_URI . '/assets/fonts/dederon-sans-std-' . $style . '.woff2',
			'as'          => 'font',
			'type'        => 'font/woff2',
			'crossorigin' => 'anonymous',
		);
	}

	return $resources;
}
add_filter( 'wp_preload_resources', 'najdisvujsen_preload_fonts' );
