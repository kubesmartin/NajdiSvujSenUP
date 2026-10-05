<?php
/**
 * Typography helpers for content output.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Binds one-letter prepositions and conjunctions to the following word.
 *
 * Czech typography does not allow these at the end of a line.
 *
 * @since 0.3.0
 *
 * @param string $html HTML.
 * @return string HTML with non-breaking spaces.
 */
function najdisvujsen_nbsp( $html ) {
	return (string) preg_replace_callback(
		'/(^|>)([^<]+)/u',
		static function ( $matches ) {
			return $matches[1] . preg_replace( '/(?<=^|[\s(„"])([AaIiKkOoSsUuVvZz])\s+(?=\S)/u', '$1' . "\u{00A0}", $matches[2] );
		},
		$html
	);
}

/**
 * Returns the allowed HTML for inline content fragments.
 *
 * @since 0.3.0
 *
 * @return array Allowed tags and attributes.
 */
function najdisvujsen_inline_kses() {
	return array(
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
		'strong' => array(),
		'em'     => array(),
		'br'     => array(),
		'sub'    => array(),
		'sup'    => array(),
	);
}
