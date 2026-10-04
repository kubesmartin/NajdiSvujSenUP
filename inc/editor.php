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
 * Registers the block styles used for structured page content.
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
	register_block_style(
		'core/group',
		array(
			'name'  => 'profile',
			'label' => __( 'Medailon', 'najdisvujsen' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'program',
			'label' => __( 'Studijní program', 'najdisvujsen' ),
		)
	);
	register_block_style(
		'core/image',
		array(
			'name'  => 'strip',
			'label' => __( 'Fotopás', 'najdisvujsen' ),
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
		'najdisvujsen/profile',
		array(
			'title'      => __( 'Medailon vyučujícího', 'najdisvujsen' ),
			'categories' => array( 'najdisvujsen' ),
			'content'    => '<!-- wp:group {"className":"is-style-profile"} --><div class="wp-block-group is-style-profile"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img alt=""/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Jméno vyučujícího', 'najdisvujsen' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>' . esc_html__( 'Krátké představení.', 'najdisvujsen' ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'najdisvujsen/program',
		array(
			'title'       => __( 'Studijní program', 'najdisvujsen' ),
			'description' => __( 'Karta programu: název, štítky (stupeň studia jako první, dále délka, forma a typ studia) a popis.', 'najdisvujsen' ),
			'categories'  => array( 'najdisvujsen' ),
			'content'     => '<!-- wp:group {"className":"is-style-program"} --><div class="wp-block-group is-style-program"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . esc_html__( 'Název programu', 'najdisvujsen' ) . '</h3><!-- /wp:heading --><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>' . esc_html__( 'Bakalářské', 'najdisvujsen' ) . '</li><!-- /wp:list-item --><!-- wp:list-item --><li>' . esc_html__( 'Prezenční', 'najdisvujsen' ) . '</li><!-- /wp:list-item --></ul><!-- /wp:list --><!-- wp:paragraph --><p>' . esc_html__( 'Krátký popis programu.', 'najdisvujsen' ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		)
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
