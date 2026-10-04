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
 * Enqueues theme styles and scripts.
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

	// Font faces are small; inlining them saves a render-blocking request.
	$fonts = NAJDISVUJSEN_DIR . '/assets/css/fonts.css';

	if ( is_readable( $fonts ) ) {
		wp_add_inline_style(
			'najdisvujsen-main',
			str_replace( '../fonts/', NAJDISVUJSEN_URI . '/assets/fonts/', (string) file_get_contents( $fonts ) ) // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
		);
	}

	wp_enqueue_script(
		'najdisvujsen-main',
		NAJDISVUJSEN_URI . '/assets/js/main.js',
		array(),
		NAJDISVUJSEN_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'najdisvujsen-main',
		'najdisvujsenL10n',
		array(
			'openMenu'   => __( 'Otevřít menu', 'najdisvujsen' ),
			'closeMenu'  => __( 'Zavřít menu', 'najdisvujsen' ),
			/* translators: %d: number of programs found. */
			'found'      => __( 'Nalezeno programů: %d', 'najdisvujsen' ),
			'notFound'   => __( 'V této úrovni studia nic – zkus druhou záložku.', 'najdisvujsen' ),
			/* translators: %s: career name. */
			'landed'     => __( 'Co třeba %s? Tyhle obory tě tam dovedou 👇', 'najdisvujsen' ),
			'spinning'   => __( 'Losuju…', 'najdisvujsen' ),
			'pickForMe'  => __( 'Vyber za mě', 'najdisvujsen' ),
			'playVideo'  => __( 'Přehrát video', 'najdisvujsen' ),
			'pauseVideo' => __( 'Pozastavit video', 'najdisvujsen' ),
			'soundOn'    => __( 'Zapnout zvuk', 'najdisvujsen' ),
			'soundOff'   => __( 'Vypnout zvuk', 'najdisvujsen' ),
			'close'      => __( 'Zavřít', 'najdisvujsen' ),
			'previous'   => __( 'Předchozí', 'najdisvujsen' ),
			'next'       => __( 'Další', 'najdisvujsen' ),
		)
	);

	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'najdisvujsen_enqueue_assets' );

/**
 * Marks the document as scripted before the first paint.
 *
 * Styles use the "js" class to hide content that only makes sense with
 * JavaScript, such as inactive tabs.
 *
 * @since 0.3.0
 */
function najdisvujsen_print_js_class() {
	wp_print_inline_script_tag( 'document.documentElement.classList.add("js");' );
}
add_action( 'wp_head', 'najdisvujsen_print_js_class', 1 );

/**
 * Preloads the font file used above the fold.
 *
 * Headings, buttons and bubbles use the bold cut; body text uses system fonts.
 *
 * @since 0.1.0
 *
 * @param array $resources Resources to preload.
 * @return array Filtered resources.
 */
function najdisvujsen_preload_fonts( $resources ) {
	$resources[] = array(
		'href'        => NAJDISVUJSEN_URI . '/assets/fonts/dederon-sans-std-bold.woff2',
		'as'          => 'font',
		'type'        => 'font/woff2',
		'crossorigin' => 'anonymous',
	);

	return $resources;
}
add_filter( 'wp_preload_resources', 'najdisvujsen_preload_fonts' );

add_filter( 'should_load_separate_core_block_assets', '__return_true' );
