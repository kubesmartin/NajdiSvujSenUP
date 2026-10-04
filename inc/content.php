<?php
/**
 * Splitting page content into sections and rendering its parts.
 *
 * Pages are written as a sequence of level 2 headings with free content in
 * between. Templates render each such section with its own layout.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Splits post content into sections delimited by level 2 headings.
 *
 * Images with the "strip" block style are collected separately as section photos.
 *
 * @since 0.3.0
 *
 * @param int|WP_Post|null $post Post, defaults to the current post.
 * @return array[] List of sections with keys anchor, title, blocks and photos.
 *                 The first section holds blocks preceding the first heading.
 */
function najdisvujsen_get_sections( $post = null ) {
	static $cache = array();

	$post = get_post( $post );

	if ( ! $post ) {
		return array();
	}

	$key = $post->ID . ':' . md5( $post->post_content );

	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$sections = array(
		array(
			'anchor' => '',
			'title'  => '',
			'blocks' => array(),
			'photos' => array(),
		),
	);
	$current  = 0;

	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( empty( $block['blockName'] ) ) {
			continue;
		}

		if ( 'core/heading' === $block['blockName'] && 2 === (int) ( $block['attrs']['level'] ?? 2 ) ) {
			$sections[] = array(
				'anchor' => sanitize_title( $block['attrs']['anchor'] ?? '' ),
				'title'  => najdisvujsen_block_inner_html( $block ),
				'blocks' => array(),
				'photos' => array(),
			);
			++$current;
			continue;
		}

		if ( najdisvujsen_is_strip_image( $block ) ) {
			$sections[ $current ]['photos'][] = (int) $block['attrs']['id'];
			continue;
		}

		$sections[ $current ]['blocks'][] = $block;
	}

	$cache[ $key ] = $sections;

	return $sections;
}

/**
 * Checks whether a block is an image with the "strip" style.
 *
 * @since 0.3.0
 *
 * @param array $block Parsed block.
 * @return bool True for strip images with an attachment.
 */
function najdisvujsen_is_strip_image( $block ) {
	return 'core/image' === $block['blockName']
		&& ! empty( $block['attrs']['id'] )
		&& str_contains( (string) ( $block['attrs']['className'] ?? '' ), 'is-style-strip' );
}

/**
 * Renders blocks.
 *
 * Fragments are rendered without the_content so that plugins appending
 * markup to post content do not repeat it in every section.
 *
 * @since 0.3.0
 *
 * @param array[] $blocks Parsed blocks.
 * @return string HTML.
 */
function najdisvujsen_render_blocks( $blocks ) {
	$html = '';

	foreach ( $blocks as $block ) {
		$html .= render_block( $block );
	}

	if ( '' === $html ) {
		return '';
	}

	$html = najdisvujsen_nbsp( wp_filter_content_tags( do_shortcode( $html ) ) );

	/**
	 * Filters rendered section content.
	 *
	 * @since 0.3.0
	 *
	 * @param string  $html   Rendered HTML.
	 * @param array[] $blocks Parsed blocks.
	 */
	return (string) apply_filters( 'najdisvujsen_render_blocks', $html, $blocks );
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
 * Returns the inner HTML of a single-element block, e.g. a paragraph or a list item.
 *
 * @since 0.3.0
 *
 * @param array $block Parsed block.
 * @return string HTML without the wrapping element.
 */
function najdisvujsen_block_inner_html( $block ) {
	$html = trim( najdisvujsen_render_blocks( array( $block ) ) );

	if ( preg_match( '#^<([a-z0-9]+)\b[^>]*>(.*)</\1>$#is', $html, $matches ) ) {
		return trim( $matches[2] );
	}

	return $html;
}

/**
 * Returns the plain text of a block.
 *
 * @since 0.3.0
 *
 * @param array $block Parsed block.
 * @return string Text.
 */
function najdisvujsen_block_text( $block ) {
	return trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( serialize_block( $block ) ) ) );
}

/**
 * Returns the inner HTML of every item of a list block.
 *
 * @since 0.3.0
 *
 * @param array $block Parsed core/list block.
 * @return string[] Item HTML.
 */
function najdisvujsen_list_items( $block ) {
	$items = array();

	foreach ( $block['innerBlocks'] as $inner ) {
		if ( 'core/list-item' === $inner['blockName'] ) {
			$items[] = najdisvujsen_block_inner_html( $inner );
		}
	}

	return array_values( array_filter( $items, 'strlen' ) );
}

/**
 * Splits item HTML into a bold lead and the remaining text.
 *
 * "<strong>Title</strong><br>Text" yields array( 'Title', 'Text' ).
 *
 * @since 0.3.0
 *
 * @param string $html Item HTML.
 * @return array{0: string, 1: string} Title HTML (may be empty) and body HTML.
 */
function najdisvujsen_split_lead( $html ) {
	if ( preg_match( '#^\s*<strong>(.+?)</strong>\s*(?:<br\s*/?>|[:–-])\s*(.+)$#isu', $html, $matches ) ) {
		return array( trim( $matches[1] ), trim( $matches[2] ) );
	}

	return array( '', $html );
}

/**
 * Checks whether a block is a group with the given block style.
 *
 * @since 0.3.0
 *
 * @param array  $block Parsed block.
 * @param string $style Style name.
 * @return bool True if the block is a group with the style.
 */
function najdisvujsen_is_group_style( $block, $style ) {
	return 'core/group' === $block['blockName']
		&& str_contains( (string) ( $block['attrs']['className'] ?? '' ), 'is-style-' . $style );
}

/**
 * Returns the attachment IDs of all images inside a gallery block.
 *
 * @since 0.3.0
 *
 * @param array $block Parsed core/gallery block.
 * @return int[] Attachment IDs.
 */
function najdisvujsen_gallery_ids( $block ) {
	$ids = array();

	foreach ( $block['innerBlocks'] as $inner ) {
		if ( 'core/image' === $inner['blockName'] && ! empty( $inner['attrs']['id'] ) ) {
			$ids[] = (int) $inner['attrs']['id'];
		}
	}

	return $ids;
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
