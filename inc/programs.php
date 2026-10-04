<?php
/**
 * Study program pages and the program catalogue.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checks whether a page is a study program page.
 *
 * Program pages are regular pages with a department filled in.
 *
 * @since 0.3.0
 *
 * @param int|WP_Post|null $post Post, defaults to the current post.
 * @return bool True for program pages.
 */
function najdisvujsen_is_program( $post = null ) {
	$post = get_post( $post );

	return $post
		&& 'page' === $post->post_type
		&& (int) get_option( 'page_on_front' ) !== $post->ID
		&& '' !== najdisvujsen_get_page_header_field( 'department', $post->ID );
}

/**
 * Returns the short program name used in the page header.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 * @return string Name.
 */
function najdisvujsen_program_name( $post_id ) {
	$name = najdisvujsen_get_page_header_field( 'hero_title', $post_id );

	return '' !== $name ? $name : get_the_title( $post_id );
}

/**
 * Returns the careers listed for a program.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 * @return string[] Career names.
 */
function najdisvujsen_program_careers( $post_id ) {
	$careers = preg_split( '/\R/u', najdisvujsen_get_page_header_field( 'careers', $post_id ) );

	return array_values( array_filter( array_map( 'trim', $careers ), 'strlen' ) );
}

/**
 * Returns all published program pages.
 *
 * @since 0.3.0
 *
 * @return WP_Post[] Program pages keyed by slug.
 */
function najdisvujsen_get_programs() {
	static $programs = null;

	if ( null !== $programs ) {
		return $programs;
	}

	$programs = array();
	$pages    = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'meta_key'       => '_najdisvujsen_department', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Small set of pages.
		)
	);

	foreach ( $pages as $page ) {
		if ( najdisvujsen_is_program( $page ) ) {
			$programs[ $page->post_name ] = $page;
		}
	}

	return $programs;
}

/**
 * Returns the slug of a program page an URL points to.
 *
 * @since 0.3.0
 *
 * @param string $url URL.
 * @return string Slug, or an empty string for other URLs.
 */
function najdisvujsen_program_slug_from_url( $url ) {
	$home = wp_parse_url( home_url( '/' ) );
	$link = wp_parse_url( $url );

	if ( empty( $link['path'] ) || ( ! empty( $link['host'] ) && ( $home['host'] ?? '' ) !== $link['host'] ) ) {
		return '';
	}

	$path = trim( substr( $link['path'], strlen( rtrim( $home['path'] ?? '', '/' ) ) ), '/' );

	return isset( najdisvujsen_get_programs()[ $path ] ) ? $path : '';
}

/**
 * Returns the program catalogue listed on the front page.
 *
 * The catalogue is read from the front page content: a level 3 heading per
 * study level, a level 4 heading per category and a list of program links.
 *
 * @since 0.3.0
 *
 * @return array[] Levels (bc, mgr) with categories of programs (name, url, slug).
 */
function najdisvujsen_get_catalogue() {
	static $catalogue = null;

	if ( null !== $catalogue ) {
		return $catalogue;
	}

	$catalogue = array();
	$front     = (int) get_option( 'page_on_front' );

	if ( ! $front ) {
		return $catalogue;
	}

	foreach ( najdisvujsen_get_sections( $front ) as $section ) {
		if ( 'programy' === $section['anchor'] ) {
			$catalogue = najdisvujsen_parse_catalogue( $section['blocks'] )['levels'];
			break;
		}
	}

	return $catalogue;
}

/**
 * Parses the program catalogue out of the blocks of the programs section.
 *
 * @since 0.3.0
 *
 * @param array[] $blocks Parsed blocks of the section.
 * @return array{intro: array[], levels: array[], after: array[]} Blocks before the catalogue,
 *                                                                 catalogue levels and blocks after it.
 */
function najdisvujsen_parse_catalogue( $blocks ) {
	$result   = array(
		'intro'  => array(),
		'levels' => array(),
		'after'  => array(),
	);
	$level    = '';
	$category = '';

	foreach ( $blocks as $block ) {
		$heading = 'core/heading' === $block['blockName'] ? (int) ( $block['attrs']['level'] ?? 2 ) : 0;

		if ( 3 === $heading ) {
			$text  = najdisvujsen_block_text( $block );
			$level = preg_match( '/magist|navazuj/iu', $text ) ? 'mgr' : 'bc';

			$result['levels'][ $level ] = array(
				'title'      => $text,
				'categories' => array(),
			);
			$category                   = '';
			continue;
		}

		if ( $level && 4 === $heading ) {
			$category = najdisvujsen_block_text( $block );
			$result['levels'][ $level ]['categories'][ $category ] = array();
			continue;
		}

		if ( $level && 'core/list' === $block['blockName'] ) {
			if ( '' === $category ) {
				$category = __( 'Studijní programy', 'najdisvujsen' );
			}

			foreach ( $block['innerBlocks'] as $item ) {
				$html = najdisvujsen_block_inner_html( $item );
				$name = trim( wp_strip_all_tags( $html ) );

				if ( '' === $name ) {
					continue;
				}

				$url = preg_match( '/href="([^"]+)"/', $html, $matches ) ? html_entity_decode( $matches[1] ) : '';

				$result['levels'][ $level ]['categories'][ $category ][] = array(
					'name' => html_entity_decode( $name ),
					'url'  => $url,
					'slug' => $url ? najdisvujsen_program_slug_from_url( $url ) : '',
				);
			}
			continue;
		}

		if ( $result['levels'] ) {
			$result['after'][] = $block;
		} else {
			$result['intro'][] = $block;
		}
	}

	return $result;
}

/**
 * Returns the careers offered in the "Who do you want to be?" picker.
 *
 * @since 0.3.0
 *
 * @return string[] Career names.
 */
function najdisvujsen_picker_careers() {
	$careers = array(
		__( 'Psycholožka', 'najdisvujsen' ),
		__( 'Terapeut', 'najdisvujsen' ),
		__( 'Personalista', 'najdisvujsen' ),
		__( 'Lektorka', 'najdisvujsen' ),
		__( 'Tlumočník', 'najdisvujsen' ),
		__( 'Překladatelka', 'najdisvujsen' ),
		__( 'Novinářka', 'najdisvujsen' ),
		__( 'Redaktorka', 'najdisvujsen' ),
		__( 'Editorka', 'najdisvujsen' ),
		__( 'Copywriter', 'najdisvujsen' ),
		__( 'Moderátor', 'najdisvujsen' ),
		__( 'Diplomat', 'najdisvujsen' ),
		__( 'Analytik', 'najdisvujsen' ),
		__( 'Datová specialistka', 'najdisvujsen' ),
		__( 'AI specialista', 'najdisvujsen' ),
		__( 'Historik', 'najdisvujsen' ),
		__( 'Archeoložka', 'najdisvujsen' ),
		__( 'Archivář', 'najdisvujsen' ),
		__( 'Památkář', 'najdisvujsen' ),
		__( 'Kurátorka', 'najdisvujsen' ),
		__( 'Dramaturg', 'najdisvujsen' ),
		__( 'Režisér', 'najdisvujsen' ),
		__( 'Filozof', 'najdisvujsen' ),
		__( 'Sociolog', 'najdisvujsen' ),
		__( 'Ekonom', 'najdisvujsen' ),
		__( 'Manažerka', 'najdisvujsen' ),
	);

	/**
	 * Filters the careers offered in the program picker on the front page.
	 *
	 * @since 0.3.0
	 *
	 * @param string[] $careers Career names.
	 */
	return (array) apply_filters( 'najdisvujsen_picker_careers', $careers );
}

/**
 * Returns the profession bubbles running through the front page hero.
 *
 * Arrays are rows; a row item starting with "emoji:" renders as a sticker.
 *
 * @since 0.3.0
 *
 * @return array[] Rows of bubbles.
 */
function najdisvujsen_hero_bubbles() {
	$rows = array(
		array( 'Archeoložka', 'Překladatelka', 'emoji:🧠', 'Datová specialistka', 'Psycholožka', 'Filozof', 'emoji:💡', 'Lektorka', 'Historik', 'emoji:🎬', 'Moderátor', 'Analytik', 'Vědkyně', 'emoji:🚀', 'Editorka', 'Tlumočník' ),
		array( 'Redaktorka', 'Památkář', 'emoji:📚', 'Personalista', 'Kurátorka', 'Dramaturg', 'emoji:🎨', 'AI specialista', 'Novinářka', 'emoji:🌍', 'Terapeut', 'Sociolog', 'Diplomat', 'Archivář', 'emoji:⭐', 'Manažerka', 'Copywriter', 'Režisér', 'Ekonom' ),
	);

	/**
	 * Filters the profession bubbles in the front page hero.
	 *
	 * @since 0.3.0
	 *
	 * @param array[] $rows Rows of bubbles.
	 */
	return (array) apply_filters( 'najdisvujsen_hero_bubbles', $rows );
}

/**
 * Maps careers to the slugs of programs leading to them.
 *
 * @since 0.3.0
 *
 * @return array<string, string[]> Program slugs keyed by lowercase career name.
 */
function najdisvujsen_career_map() {
	$map = array();

	foreach ( najdisvujsen_get_programs() as $slug => $program ) {
		foreach ( najdisvujsen_program_careers( $program->ID ) as $career ) {
			$map[ mb_strtolower( $career ) ][] = $slug;
		}
	}

	return $map;
}

/**
 * Returns programs related to a program page.
 *
 * Programs of the same department come first, then programs from the same
 * category of the front page catalogue.
 *
 * @since 0.3.0
 *
 * @param int $post_id Program page ID.
 * @param int $limit   Maximum number of programs.
 * @return WP_Post[] Related program pages.
 */
function najdisvujsen_related_programs( $post_id, $limit = 4 ) {
	$programs   = najdisvujsen_get_programs();
	$self       = get_post( $post_id );
	$host       = static function ( $id ) {
		return (string) wp_parse_url( najdisvujsen_get_page_header_field( 'department_url', $id ), PHP_URL_HOST );
	};
	$self_host  = $host( $post_id );
	$self_name  = najdisvujsen_program_name( $post_id );
	$candidates = array();

	foreach ( $programs as $slug => $program ) {
		if ( $self_host && $host( $program->ID ) === $self_host ) {
			$candidates[] = $slug;
		}
	}

	foreach ( najdisvujsen_get_catalogue() as $level ) {
		foreach ( $level['categories'] as $items ) {
			$slugs = wp_list_pluck( $items, 'slug' );

			if ( $self && in_array( $self->post_name, $slugs, true ) ) {
				$candidates = array_merge( $candidates, array_filter( $slugs ) );
			}
		}
	}

	$related = array();
	$names   = array( $self_name );

	foreach ( array_unique( $candidates ) as $slug ) {
		if ( ! isset( $programs[ $slug ] ) || $programs[ $slug ]->ID === $post_id ) {
			continue;
		}

		$name = najdisvujsen_program_name( $programs[ $slug ]->ID );

		if ( in_array( $name, $names, true ) ) {
			continue;
		}

		$names[]   = $name;
		$related[] = $programs[ $slug ];

		if ( count( $related ) >= $limit ) {
			break;
		}
	}

	return $related;
}

/**
 * Reads a study program card from a group with the "Studijní program" style.
 *
 * The card is a level 3 heading with the program name (optionally linked),
 * a list of tags (study level first) and paragraphs describing the program.
 *
 * @since 0.4.0
 *
 * @param array $block Parsed core/group block.
 * @return array{title: string, link: string, level: string, tags: array[], body: array[]} Card data.
 */
function najdisvujsen_program_card( $block ) {
	$card = array(
		'title' => '',
		'link'  => '',
		'level' => '',
		'tags'  => array(),
		'body'  => array(),
	);

	foreach ( $block['innerBlocks'] as $inner ) {
		if ( 'core/heading' === $inner['blockName'] && '' === $card['title'] ) {
			$card['title'] = najdisvujsen_block_text( $inner );

			if ( preg_match( '#<a\s[^>]*href="([^"]+)"#i', najdisvujsen_block_inner_html( $inner ), $matches ) ) {
				$card['link'] = html_entity_decode( $matches[1] );
			}
		} elseif ( 'core/list' === $inner['blockName'] && ! $card['tags'] ) {
			foreach ( najdisvujsen_list_items( $inner ) as $item ) {
				$label = trim( wp_strip_all_tags( $item ) );
				$level = najdisvujsen_program_level( $label );

				if ( $level && ! $card['level'] ) {
					$card['level'] = $level;
					$tone          = 'level';
				} elseif ( preg_match( '/^\d+\s/u', $label ) ) {
					$tone = 'blue';
				} elseif ( preg_match( '/^prezenční/iu', $label ) ) {
					$tone = 'solid';
				} elseif ( preg_match( '/^kombinovan/iu', $label ) ) {
					$tone = 'grey';
				} else {
					$tone = 'outline';
				}

				$card['tags'][] = array(
					'label' => $label,
					'tone'  => $tone,
				);
			}
		} else {
			$card['body'][] = $inner;
		}
	}

	return $card;
}

/**
 * Returns the study level key for a level tag label.
 *
 * @since 0.4.0
 *
 * @param string $label Tag label, e.g. "Bakalářské".
 * @return string Level key (bc, mgr or phd), or an empty string.
 */
function najdisvujsen_program_level( $label ) {
	if ( preg_match( '/^bakalář/iu', $label ) ) {
		return 'bc';
	}

	if ( preg_match( '/^(navazující\s+)?magister/iu', $label ) ) {
		return 'mgr';
	}

	if ( preg_match( '/^doktor/iu', $label ) ) {
		return 'phd';
	}

	return '';
}

/**
 * Returns the labels of study levels.
 *
 * @since 0.4.0
 *
 * @return string[] Labels keyed by level.
 */
function najdisvujsen_program_levels() {
	return array(
		'bc'  => __( 'Bakalářské', 'najdisvujsen' ),
		'mgr' => __( 'Magisterské', 'najdisvujsen' ),
		'phd' => __( 'Doktorské', 'najdisvujsen' ),
	);
}

/**
 * Checks whether a block is an "…and many others" line closing a list of people.
 *
 * The teachers section prints its own closing line instead.
 *
 * @since 0.4.0
 *
 * @param array $block Parsed block.
 * @return bool True for a short "and many others" paragraph.
 */
function najdisvujsen_is_more_people_line( $block ) {
	if ( 'core/paragraph' !== $block['blockName'] ) {
		return false;
	}

	$text = najdisvujsen_block_text( $block );

	return mb_strlen( $text ) < 80 && 1 === preg_match( '/^(…|\.\.\.)?\s*(a\s+)?(mnoz[íi]|řada|mnoho)\s+dalš/iu', $text );
}

/**
 * Returns the label of a section anchor for the in-page navigation.
 *
 * @since 0.3.0
 *
 * @param string $anchor Section anchor.
 * @return string Label, or an empty string for anchors without one.
 */
function najdisvujsen_section_label( $anchor ) {
	$labels = array(
		'proc'        => __( 'Proč?', 'najdisvujsen' ),
		'programy'    => __( 'Programy', 'najdisvujsen' ),
		'olomouc'     => __( 'Olomouc', 'najdisvujsen' ),
		'uplatneni'   => __( 'Uplatnění', 'najdisvujsen' ),
		'zahranici'   => __( 'Zahraničí', 'najdisvujsen' ),
		'zajimavosti' => __( 'Zajímavosti', 'najdisvujsen' ),
		'prijimacky'  => __( 'Přijímačky', 'najdisvujsen' ),
		'lide'        => __( 'Lidé', 'najdisvujsen' ),
	);

	return $labels[ $anchor ] ?? '';
}

/**
 * Returns the in-page navigation of the current page.
 *
 * @since 0.3.0
 *
 * @return array<string, string> Labels keyed by anchor.
 */
function najdisvujsen_page_nav() {
	if ( is_front_page() ) {
		$nav     = array();
		$labels  = array(
			'univerzita' => __( 'Proč u nás', 'najdisvujsen' ),
			'programy'   => __( 'Programy', 'najdisvujsen' ),
			'dod'        => __( 'DOD', 'najdisvujsen' ),
			'slovensko'  => __( 'Ze Slovenska', 'najdisvujsen' ),
		);
		$present = wp_list_pluck( najdisvujsen_get_sections( get_queried_object_id() ), 'anchor' );

		foreach ( $labels as $anchor => $label ) {
			if ( 'univerzita' === $anchor || in_array( $anchor, $present, true ) ) {
				$nav[ $anchor ] = $label;
			}
		}

		return $nav;
	}

	if ( ! is_page() || ! najdisvujsen_is_program( get_queried_object_id() ) ) {
		return array();
	}

	$nav = array();

	foreach ( najdisvujsen_get_sections( get_queried_object_id() ) as $section ) {
		$label = najdisvujsen_section_label( $section['anchor'] );

		if ( $label && ! isset( $nav[ $section['anchor'] ] ) ) {
			$nav[ $section['anchor'] ] = $label;
		}
	}

	if ( ! isset( $nav['prijimacky'] ) ) {
		$nav['prijimacky'] = najdisvujsen_section_label( 'prijimacky' );
	}

	return $nav;
}
