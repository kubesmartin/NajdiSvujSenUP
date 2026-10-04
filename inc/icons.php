<?php
/**
 * SVG icon sprite.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the markup of an icon from the sprite.
 *
 * @since 0.3.0
 *
 * @param string $name    Icon name, e.g. "arrow-up-right".
 * @param string $classes Additional CSS classes.
 * @return string SVG markup.
 */
function najdisvujsen_icon( $name, $classes = '' ) {
	return sprintf(
		'<svg class="icon%1$s" aria-hidden="true" focusable="false"><use href="#icon-%2$s"></use></svg>',
		$classes ? ' ' . esc_attr( $classes ) : '',
		esc_attr( $name )
	);
}

/**
 * Returns the allowed HTML for icon markup, for use with wp_kses().
 *
 * @since 0.3.0
 *
 * @return array Allowed tags and attributes.
 */
function najdisvujsen_icon_kses() {
	return array(
		'svg' => array(
			'class'       => true,
			'aria-hidden' => true,
			'focusable'   => true,
		),
		'use' => array(
			'href' => true,
		),
	);
}

/**
 * Prints the icon sprite at the top of the body.
 *
 * @since 0.3.0
 */
function najdisvujsen_print_icon_sprite() {
	$file = NAJDISVUJSEN_DIR . '/assets/images/icons.svg';

	if ( ! is_readable( $file ) ) {
		return;
	}

	$sprite = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local theme file.
	$sprite = str_replace( '<svg ', '<svg class="icon-sprite" aria-hidden="true" ', $sprite );

	echo $sprite; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG shipped with the theme.
}
add_action( 'wp_body_open', 'najdisvujsen_print_icon_sprite' );
