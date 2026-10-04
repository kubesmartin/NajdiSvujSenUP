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
