<?php
/**
 * Theme setup.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers theme supports, menus and image sizes.
 *
 * @since 0.1.0
 */
function najdisvujsen_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_post_type_support( 'page', 'excerpt' );

	remove_theme_support( 'core-block-patterns' );
	remove_theme_support( 'block-templates' );

	add_editor_style( array( 'assets/css/fonts.css', 'assets/css/editor.css' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Hlavní menu', 'najdisvujsen' ),
			'footer'  => __( 'Menu v patičce', 'najdisvujsen' ),
		)
	);
}
add_action( 'after_setup_theme', 'najdisvujsen_setup' );

/**
 * Removes front-end output the theme does not use.
 *
 * Emoji are rendered by the system fonts, so the emoji detection script and
 * styles are not needed.
 *
 * @since 0.3.0
 */
function najdisvujsen_cleanup_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'najdisvujsen_cleanup_head' );

/**
 * Removes the emoji CDN from resource hints.
 *
 * @since 0.3.0
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Filtered URLs.
 */
function najdisvujsen_resource_hints( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls = array_values(
			array_filter(
				$urls,
				static function ( $url ) {
					return ! str_contains( is_array( $url ) ? (string) ( $url['href'] ?? '' ) : (string) $url, 's.w.org/images/core/emoji' );
				}
			)
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'najdisvujsen_resource_hints', 10, 2 );
