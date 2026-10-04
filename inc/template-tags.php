<?php
/**
 * Template helper functions.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints the site logo.
 *
 * @since 0.1.0
 */
function najdisvujsen_site_branding() {
	printf(
		'<a class="site-header__logo" href="%1$s" rel="home"><img src="%2$s" width="234" height="104" alt="%3$s" fetchpriority="high"></a>',
		esc_url( home_url( '/' ) ),
		esc_url( NAJDISVUJSEN_URI . '/assets/images/logo-ff.png' ),
		esc_attr__( 'Filozofická fakulta Univerzity Palackého v Olomouci – úvodní stránka', 'najdisvujsen' )
	);
}

/**
 * Prints the publication date of the current post.
 *
 * @since 0.1.0
 */
function najdisvujsen_posted_on() {
	printf(
		'<time class="entry-date" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Prints the "Podat přihlášku" button with the arrow icon.
 *
 * @since 0.3.0
 *
 * @param string $modifier Optional modifier class suffix, e.g. "dark" or "large".
 */
function najdisvujsen_apply_button( $modifier = '' ) {
	$classes = 'apply-btn';

	foreach ( array_filter( explode( ' ', $modifier ) ) as $suffix ) {
		$classes .= ' apply-btn--' . $suffix;
	}

	printf(
		'<a class="%1$s" href="%2$s"><span>%3$s</span><span class="apply-btn__icon">%4$s</span></a>',
		esc_attr( $classes ),
		esc_url( najdisvujsen_admission_url( 'najdisvujsen_application_url' ) ),
		esc_html__( 'Podat přihlášku', 'najdisvujsen' ),
		wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() )
	);
}

/**
 * Returns the social profile links of a page.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 * @return array[] Links with keys icon, label and url.
 */
function najdisvujsen_social_links( $post_id ) {
	$networks = array(
		'facebook'  => array( 'facebook', 'Facebook' ),
		'youtube'   => array( 'youtube', 'YouTube' ),
		'instagram' => array( 'instagram', 'Instagram' ),
		'tiktok'    => array( 'music-2', 'TikTok' ),
	);
	$links    = array();

	foreach ( $networks as $key => $network ) {
		$url = najdisvujsen_get_page_header_field( 'social_' . $key, $post_id );

		if ( $url ) {
			$links[] = array(
				'icon'  => $network[0],
				'label' => $network[1],
				'url'   => $url,
			);
		}
	}

	return $links;
}

/**
 * Prints round social profile buttons.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 */
function najdisvujsen_social_buttons( $post_id ) {
	$links = najdisvujsen_social_links( $post_id );

	if ( ! $links ) {
		return;
	}

	echo '<ul class="socials">';

	foreach ( $links as $link ) {
		printf(
			'<li><a class="icon-btn icon-btn--outline" href="%1$s" rel="noopener" aria-label="%2$s">%3$s</a></li>',
			esc_url( $link['url'] ),
			esc_attr( $link['label'] ),
			wp_kses( najdisvujsen_icon( $link['icon'] ), najdisvujsen_icon_kses() )
		);
	}

	echo '</ul>';
}

/**
 * Prints a photo that opens in the lightbox.
 *
 * @since 0.3.0
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $sizes         Value of the sizes attribute.
 * @param string $classes       Additional CSS classes.
 * @param string $gallery       Name of the lightbox gallery the photo belongs to.
 */
function najdisvujsen_photo( $attachment_id, $sizes, $classes = '', $gallery = '' ) {
	$full = wp_get_attachment_image_src( $attachment_id, 'full' );

	if ( ! $full ) {
		return;
	}

	$alt   = trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) );
	$image = wp_get_attachment_image(
		$attachment_id,
		'large',
		false,
		array(
			'alt'      => '',
			'sizes'    => $sizes,
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);

	printf(
		'<a class="photo%1$s" href="%2$s" data-lightbox="%3$s" aria-label="%4$s"><span class="photo__img">%5$s</span><span class="photo__zoom">%6$s</span>%7$s</a>',
		$classes ? ' ' . esc_attr( $classes ) : '',
		esc_url( $full[0] ),
		esc_attr( $gallery ),
		esc_attr( $alt ? sprintf( /* translators: %s: photo description. */ __( 'Zvětšit fotku: %s', 'najdisvujsen' ), $alt ) : __( 'Zvětšit fotku', 'najdisvujsen' ) ),
		$image, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by wp_get_attachment_image().
		wp_kses( najdisvujsen_icon( 'maximize-2' ), najdisvujsen_icon_kses() ),
		$alt ? '<span class="photo__label">' . esc_html( $alt ) . '</span>' : ''
	);
}

/**
 * Prints a collage of photos.
 *
 * @since 0.3.0
 *
 * @param int[]  $ids     Attachment IDs.
 * @param string $gallery Lightbox gallery name.
 * @param string $classes Additional CSS classes.
 */
function najdisvujsen_collage( $ids, $gallery, $classes = '' ) {
	$ids = array_values( array_filter( array_map( 'intval', $ids ) ) );

	if ( ! $ids ) {
		return;
	}

	printf( '<div class="collage collage--%1$d%2$s">', (int) min( count( $ids ), 4 ), $classes ? ' ' . esc_attr( $classes ) : '' );

	foreach ( $ids as $index => $id ) {
		$big = 0 === $index;
		najdisvujsen_photo( $id, $big ? '(min-width: 900px) 600px, 100vw' : '(min-width: 900px) 300px, 50vw', $big ? 'photo--big' : '', $gallery );
	}

	echo '</div>';
}

/**
 * Prints a row of bubbles that scrolls endlessly.
 *
 * The list is printed twice for a seamless loop; the copy is hidden from
 * assistive technology.
 *
 * @since 0.3.0
 *
 * @param string[] $items    Bubble labels; items starting with "emoji:" render as stickers.
 * @param array    $args {
 *     Optional arguments.
 *
 *     @type bool   $reverse     Scroll to the right.
 *     @type int    $duration    Duration of one loop in seconds.
 *     @type bool   $interactive Render bubbles as buttons selecting a career.
 *     @type string $label       Accessible label of the row.
 * }
 */
function najdisvujsen_marquee( $items, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'reverse'     => false,
			'duration'    => 60,
			'interactive' => false,
			'label'       => '',
		)
	);

	if ( ! $items ) {
		return;
	}

	$careers = $args['interactive'] ? najdisvujsen_career_map() : array();

	printf(
		'<div class="marquee%1$s"%2$s><div class="marquee__track" style="--duration:%3$ds">',
		$args['reverse'] ? ' marquee--reverse' : '',
		$args['label'] ? ' role="group" aria-label="' . esc_attr( $args['label'] ) . '"' : '',
		(int) $args['duration']
	);

	foreach ( array( false, true ) as $copy ) {
		printf( '<ul class="marquee__list"%s>', $copy ? ' aria-hidden="true"' : '' );

		foreach ( $items as $item ) {
			if ( str_starts_with( $item, 'emoji:' ) ) {
				printf( '<li><span class="sticker sticker--sm" aria-hidden="true">%s</span></li>', esc_html( substr( $item, 6 ) ) );
				continue;
			}

			if ( isset( $careers[ mb_strtolower( $item ) ] ) ) {
				printf(
					'<li><button type="button" class="bubble bubble--interactive" data-career="%1$s"%2$s>%3$s</button></li>',
					esc_attr( $item ),
					$copy ? ' tabindex="-1"' : '',
					esc_html( $item )
				);
				continue;
			}

			printf( '<li><span class="bubble">%s</span></li>', esc_html( $item ) );
		}

		echo '</ul>';
	}

	echo '</div></div>';
}

/**
 * Returns the URL of a page by its path, falling back to an empty string.
 *
 * @since 0.3.0
 *
 * @param string $path Page path.
 * @return string URL.
 */
function najdisvujsen_page_url( $path ) {
	$page = get_page_by_path( $path );

	return $page && 'publish' === $page->post_status ? (string) get_permalink( $page ) : '';
}

/**
 * Prints the opening markup of a content section with its heading.
 *
 * @since 0.3.0
 *
 * @param array $section Section from najdisvujsen_get_sections().
 * @param array $args {
 *     Optional arguments.
 *
 *     @type string $tone    Background tone: white, muted, night or blue.
 *     @type string $type    Section type used as a modifier class.
 *     @type string $eyebrow Small label above the heading.
 *     @type bool   $heading Whether to print the heading.
 * }
 */
function najdisvujsen_section_open( $section, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'tone'    => 'white',
			'type'    => 'generic',
			'eyebrow' => '',
			'heading' => true,
		)
	);

	printf(
		'<section%1$s class="section section--%2$s section--%3$s"><div class="container">',
		$section['anchor'] ? ' id="' . esc_attr( $section['anchor'] ) . '"' : '',
		esc_attr( $args['tone'] ),
		esc_attr( $args['type'] )
	);

	if ( $args['eyebrow'] ) {
		printf( '<p class="eyebrow">%s</p>', esc_html( $args['eyebrow'] ) );
	}

	if ( $args['heading'] && '' !== $section['title'] ) {
		printf( '<h2 class="section__title">%s</h2>', wp_kses( $section['title'], najdisvujsen_inline_kses() ) );
	}
}

/**
 * Prints the closing markup of a content section.
 *
 * @since 0.3.0
 */
function najdisvujsen_section_close() {
	echo '</div></section>';
}

/**
 * Prints blocks as flowing text.
 *
 * @since 0.3.0
 *
 * @param array[] $blocks  Parsed blocks.
 * @param string  $classes Additional CSS classes.
 */
function najdisvujsen_prose( $blocks, $classes = '' ) {
	$html = najdisvujsen_render_blocks( $blocks );

	if ( '' === trim( $html ) ) {
		return;
	}

	printf(
		'<div class="prose%1$s">%2$s</div>',
		$classes ? ' ' . esc_attr( $classes ) : '',
		$html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered post content.
	);
}

/**
 * Prints a list of items with check marks.
 *
 * @since 0.3.0
 *
 * @param string[] $items Item HTML.
 */
function najdisvujsen_checks( $items ) {
	if ( ! $items ) {
		return;
	}

	echo '<ul class="checks">';

	foreach ( $items as $item ) {
		printf(
			'<li><span class="checks__icon">%1$s</span><span>%2$s</span></li>',
			wp_kses( najdisvujsen_icon( 'check' ), najdisvujsen_icon_kses() ),
			wp_kses( $item, najdisvujsen_inline_kses() )
		);
	}

	echo '</ul>';
}

/**
 * Finds a photo whose file name contains a word, falling back to the first photo.
 *
 * @since 0.3.0
 *
 * @param int[]  $ids    Attachment IDs.
 * @param string $needle Word to look for in the file name.
 * @return int Attachment ID, or 0 when there are no photos.
 */
function najdisvujsen_find_photo( $ids, $needle ) {
	foreach ( $ids as $id ) {
		if ( str_contains( strtolower( wp_basename( (string) get_attached_file( $id ) ) ), $needle ) ) {
			return (int) $id;
		}
	}

	return $ids ? (int) reset( $ids ) : 0;
}
