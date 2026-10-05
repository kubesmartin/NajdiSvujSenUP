<?php
/**
 * Block editor restrictions.
 *
 * Design-related settings are locked in theme.json; this file limits
 * the available blocks and removes editor features that bypass the design.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Limits the block inserter to an approved set of blocks.
 *
 * @since 0.1.0
 *
 * @param bool|string[] $allowed_blocks Allowed block type slugs, or boolean to enable/disable all.
 * @return string[] Allowed block type slugs.
 */
function najdisvujsen_allowed_block_types( $allowed_blocks ) {
	unset( $allowed_blocks );

	return array(
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/image',
		'core/gallery',
		'core/file',
		'core/embed',
		'core/table',
		'core/buttons',
		'core/button',
		'core/separator',
		'core/group',
	);
}
add_filter( 'allowed_block_types_all', 'najdisvujsen_allowed_block_types' );

/**
 * Disables editor features that allow arbitrary markup or styling.
 *
 * @since 0.1.0
 *
 * @param array $settings Block editor settings.
 * @return array Filtered settings.
 */
function najdisvujsen_block_editor_settings( $settings ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		$settings['codeEditingEnabled'] = false;
	}

	$settings['enableOpenverseMediaCategory'] = false;

	return $settings;
}
add_filter( 'block_editor_settings_all', 'najdisvujsen_block_editor_settings' );

add_filter( 'should_load_remote_block_patterns', '__return_false' );
remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );

/**
 * Registers block styles of regular pages.
 *
 * @since 0.2.0
 */
function najdisvujsen_register_block_styles() {
	register_block_style(
		'core/group',
		array(
			'name'  => 'highlight',
			'label' => __( 'Zvýrazněný box', 'najdisvujsen' ),
		)
	);
}
add_action( 'init', 'najdisvujsen_register_block_styles' );

/**
 * Registers block patterns for recurring page content.
 *
 * @since 0.2.0
 */
function najdisvujsen_register_block_patterns() {
	register_block_pattern_category(
		'najdisvujsen',
		array( 'label' => __( 'Najdi svůj sen', 'najdisvujsen' ) )
	);

	register_block_pattern(
		'najdisvujsen/highlight',
		array(
			'title'      => __( 'Zvýrazněný box', 'najdisvujsen' ),
			'categories' => array( 'najdisvujsen' ),
			'content'    => '<!-- wp:group {"className":"is-style-highlight"} --><div class="wp-block-group is-style-highlight"><!-- wp:paragraph --><p>' . esc_html__( 'Naše rada:', 'najdisvujsen' ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'najdisvujsen_register_block_patterns' );
