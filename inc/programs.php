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
 * Checks whether a post is a study program page.
 *
 * @since 0.3.0
 *
 * @param int|WP_Post|null $post Post, defaults to the current post.
 * @return bool True for program pages.
 */
function najdisvujsen_is_program( $post = null ) {
	$post = get_post( $post );

	return $post && NAJDISVUJSEN_OBOR === $post->post_type;
}

/**
 * Returns the short name of a study program shown in its header.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 * @return string Name.
 */
function najdisvujsen_program_name( $post_id ) {
	$name = najdisvujsen_get_data( $post_id )['hero_title'] ?? '';

	return '' !== $name ? $name : get_the_title( $post_id );
}

/**
 * Returns careers of a study program.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 * @return string[] Careers.
 */
function najdisvujsen_program_careers( $post_id ) {
	return (array) ( najdisvujsen_get_data( $post_id )['careers'] ?? array() );
}

/**
 * Returns published study program pages keyed by slug.
 *
 * @since 0.3.0
 *
 * @return WP_Post[] Program pages.
 */
function najdisvujsen_get_programs() {
	static $programs = null;

	if ( null !== $programs ) {
		return $programs;
	}

	$programs = array();
	$posts    = get_posts(
		array(
			'post_type'      => NAJDISVUJSEN_OBOR,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	foreach ( $posts as $post ) {
		$programs[ $post->post_name ] = $post;
	}

	return $programs;
}

/**
 * Returns the default heading of a program page section.
 *
 * @since 0.5.0
 *
 * @param string $key Section key.
 * @return string Heading.
 */
function najdisvujsen_section_default_title( $key ) {
	$schema = najdisvujsen_obor_schema();

	return $schema[ $key ]['fields']['title']['placeholder'] ?? '';
}

/**
 * Returns the sections of a study program page in display order.
 *
 * Empty sections are left out. Extra sections follow the section chosen
 * as their position.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 * @return array[] Sections with keys key, type, anchor, title, nav, data and photos.
 */
function najdisvujsen_obor_sections( $post_id ) {
	$data     = najdisvujsen_get_data( $post_id );
	$extras   = array();
	$sections = array();
	$labels   = array(
		'proc'      => __( 'Proč?', 'najdisvujsen' ),
		'programy'  => __( 'Programy', 'najdisvujsen' ),
		'olomouc'   => __( 'Olomouc', 'najdisvujsen' ),
		'uplatneni' => __( 'Uplatnění', 'najdisvujsen' ),
		'zahranici' => __( 'Zahraničí', 'najdisvujsen' ),
		'lide'      => __( 'Lidé', 'najdisvujsen' ),
	);
	$used     = array_keys( $labels );

	foreach ( $data['extra']['sections'] as $index => $extra ) {
		$anchor = sanitize_title( '' !== $extra['nav'] ? $extra['nav'] : $extra['title'] );

		if ( '' === $anchor || in_array( $anchor, $used, true ) ) {
			$anchor = 'sekce-' . ( $index + 1 );
		}

		$used[]                         = $anchor;
		$extras[ $extra['position'] ][] = array(
			'key'    => 'extra',
			'type'   => 'generic',
			'anchor' => $anchor,
			'title'  => $extra['title'],
			'nav'    => $extra['nav'],
			'data'   => $extra,
			'photos' => $extra['photos'],
		);
	}

	foreach ( $labels as $key => $label ) {
		$section = $data[ $key ];
		$content = $section;

		unset( $content['title'] );

		if ( najdisvujsen_has_content( $content ) ) {
			$sections[] = array(
				'key'    => $key,
				'type'   => $key,
				'anchor' => $key,
				'title'  => '' !== $section['title'] ? $section['title'] : najdisvujsen_section_default_title( $key ),
				'nav'    => $label,
				'data'   => $section,
				'photos' => $section['photos'] ?? array(),
			);
		}

		foreach ( $extras[ $key ] ?? array() as $extra ) {
			if ( najdisvujsen_has_content( array( $extra['data']['text'], $extra['data']['photos'] ) ) ) {
				$sections[] = $extra;
			}
		}
	}

	return $sections;
}

/**
 * Checks whether a value holds any text, item or photo.
 *
 * @since 0.5.0
 *
 * @param mixed $value Value.
 * @return bool True if not empty.
 */
function najdisvujsen_has_content( $value ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $item ) {
			if ( najdisvujsen_has_content( $item ) ) {
				return true;
			}
		}

		return false;
	}

	if ( is_bool( $value ) ) {
		return false;
	}

	return '' !== trim( wp_strip_all_tags( (string) $value ) ) && '0' !== (string) $value;
}

/**
 * Returns the catalogue entries of a study program page.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 * @return array[] Entries with keys name, level, category, url and slug.
 */
function najdisvujsen_obor_catalogue_items( $post_id ) {
	$data  = najdisvujsen_get_data( $post_id );
	$items = array();
	$seen  = array();

	foreach ( $data['programy']['programs'] as $card ) {
		$name = '' !== $card['catalogue_name'] ? $card['catalogue_name'] : $card['name'];
		$key  = $card['level'] . '|' . mb_strtolower( $name );

		if ( ! $card['catalogue'] || ! in_array( $card['level'], array( 'bc', 'mgr' ), true ) || '' === $name || isset( $seen[ $key ] ) ) {
			continue;
		}

		$seen[ $key ] = true;
		$items[]      = array(
			'name'     => $name,
			'level'    => $card['level'],
			'category' => $data['category'],
			'url'      => (string) get_permalink( $post_id ),
			'slug'     => get_post_field( 'post_name', $post_id ),
			'forms'    => najdisvujsen_program_forms( $data['programy']['programs'], $card ),
		);
	}

	return $items;
}

/**
 * Returns the study forms of a program from all its cards.
 *
 * A program may have a card per form; cards of the same level and name are
 * combined. Programs without a stated form are full-time, the default at the faculty.
 *
 * @since 0.6.0
 *
 * @param array[] $cards Program cards of the page.
 * @param array   $card  Card shown in the catalogue.
 * @return string[] Form keys.
 */
function najdisvujsen_program_forms( $cards, $card ) {
	$names = array_filter( array( mb_strtolower( $card['name'] ), mb_strtolower( $card['catalogue_name'] ) ) );
	$forms = array();

	foreach ( $cards as $other ) {
		if ( $other['level'] === $card['level'] && array_intersect( $names, array( mb_strtolower( $other['name'] ), mb_strtolower( $other['catalogue_name'] ) ) ) ) {
			$forms = array_merge( $forms, $other['forms'] );
		}
	}

	return $forms ? array_values( array_unique( $forms ) ) : array( 'prezencni' );
}

/**
 * Returns a key that sorts Czech text alphabetically.
 *
 * Č, Ř, Š, Ž and CH are letters of their own; other accents do not change the order.
 *
 * @since 0.5.0
 *
 * @param string $text Text.
 * @return string Sort key.
 */
function najdisvujsen_czech_sort_key( $text ) {
	$text = mb_strtolower( $text );
	$text = str_replace( array( 'ch', 'č', 'ř', 'š', 'ž' ), array( 'h~', 'c~', 'r~', 's~', 'z~' ), $text );

	return remove_accents( $text );
}

/**
 * Returns the program catalogue of the front page built from program pages.
 *
 * @since 0.3.0
 *
 * @return array[] Levels (bc, mgr) with title and categories of entries.
 */
function najdisvujsen_get_catalogue() {
	static $catalogue = null;

	if ( null !== $catalogue ) {
		return $catalogue;
	}

	$front   = (int) get_option( 'page_on_front' );
	$data    = $front ? najdisvujsen_get_data( $front ) : array();
	$entries = array();

	foreach ( najdisvujsen_get_programs() as $program ) {
		$entries = array_merge( $entries, najdisvujsen_obor_catalogue_items( $program->ID ) );
	}

	foreach ( $data['katalog']['extra'] ?? array() as $extra ) {
		$entries[] = array(
			'name'     => $extra['name'],
			'level'    => $extra['level'],
			'category' => $extra['category'],
			'url'      => $extra['url'],
			'slug'     => najdisvujsen_program_slug_from_url( $extra['url'] ),
			'forms'    => $extra['forms'] ? $extra['forms'] : array( 'prezencni' ),
		);
	}

	usort(
		$entries,
		static function ( $a, $b ) {
			return strcmp( najdisvujsen_czech_sort_key( $a['name'] ), najdisvujsen_czech_sort_key( $b['name'] ) );
		}
	);

	$titles    = array(
		'bc'  => __( 'Bakalářské studijní programy', 'najdisvujsen' ),
		'mgr' => __( 'Magisterské navazující studijní programy', 'najdisvujsen' ),
	);
	$catalogue = array();

	foreach ( $titles as $level => $title ) {
		$categories = array();

		foreach ( najdisvujsen_program_categories() as $category => $label ) {
			$items = array_values(
				array_filter(
					$entries,
					static function ( $entry ) use ( $level, $category ) {
						return $entry['level'] === $level && $entry['category'] === $category;
					}
				)
			);

			if ( $items ) {
				$categories[ $label ] = $items;
			}
		}

		if ( $categories ) {
			$custom              = $data['katalog']['levels'][ $level ] ?? '';
			$catalogue[ $level ] = array(
				'title'      => '' !== $custom ? $custom : $title,
				'categories' => $categories,
			);
		}
	}

	return $catalogue;
}

/**
 * Returns the slug of a program page the URL points to.
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
 * Returns the careers offered in the "Who do you want to be" picker.
 *
 * @since 0.3.0
 *
 * @return string[] Careers.
 */
function najdisvujsen_picker_careers() {
	$front = (int) get_option( 'page_on_front' );

	return (array) apply_filters( 'najdisvujsen_picker_careers', $front ? najdisvujsen_get_data( $front )['katalog']['careers'] : array() );
}

/**
 * Returns the two rows of bubbles in the front page header.
 *
 * @since 0.3.0
 *
 * @return array[] Rows of careers; single emoji characters are shown as stickers.
 */
function najdisvujsen_hero_bubbles() {
	$front = (int) get_option( 'page_on_front' );
	$rows  = array();

	if ( $front ) {
		foreach ( najdisvujsen_get_data( $front )['uvod']['bubbles'] as $items ) {
			$rows[] = array_map(
				static function ( $item ) {
					return preg_match( '/^[^\p{L}\p{N}\s]{1,4}$/u', $item ) ? 'emoji:' . $item : $item;
				},
				$items
			);
		}
	}

	return (array) apply_filters( 'najdisvujsen_hero_bubbles', $rows );
}

/**
 * Maps careers to the program pages that lead to them.
 *
 * @since 0.3.0
 *
 * @return array[] Program slugs keyed by lowercase career.
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
 * Returns related program pages: same department first, then the same catalogue category.
 *
 * @since 0.3.0
 *
 * @param int $post_id Post ID.
 * @param int $limit   Maximum number of pages.
 * @return WP_Post[] Program pages.
 */
function najdisvujsen_related_programs( $post_id, $limit = 4 ) {
	$programs   = najdisvujsen_get_programs();
	$host       = static function ( $id ) {
		return (string) wp_parse_url( najdisvujsen_get_data( $id )['department_url'], PHP_URL_HOST );
	};
	$self_host  = $host( $post_id );
	$self_slug  = (string) get_post_field( 'post_name', $post_id );
	$candidates = array();

	foreach ( $programs as $slug => $program ) {
		if ( $self_host && $host( $program->ID ) === $self_host ) {
			$candidates[] = $slug;
		}
	}

	foreach ( najdisvujsen_get_catalogue() as $level ) {
		foreach ( $level['categories'] as $items ) {
			$slugs = wp_list_pluck( $items, 'slug' );

			if ( in_array( $self_slug, $slugs, true ) ) {
				$candidates = array_merge( $candidates, array_filter( $slugs ) );
			}
		}
	}

	$related = array();
	$names   = array( najdisvujsen_program_name( $post_id ) );

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
 * Returns the labels of study levels used as tabs on program pages.
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
 * Returns the tags of a program card.
 *
 * @since 0.5.0
 *
 * @param array $card Program card values.
 * @return array[] Tags with keys label and tone.
 */
function najdisvujsen_program_tags( $card ) {
	$tags   = array();
	$levels = najdisvujsen_program_level_options();
	$forms  = najdisvujsen_program_form_options();
	$types  = najdisvujsen_program_type_options();

	if ( isset( $levels[ $card['level'] ] ) ) {
		$tags[] = array(
			'label' => $levels[ $card['level'] ],
			'tone'  => 'level',
		);
	}

	foreach ( $card['forms'] as $form ) {
		$tags[] = array(
			'label' => $forms[ $form ],
			'tone'  => 'prezencni' === $form ? 'solid' : 'grey',
		);
	}

	$card_types = $card['types'];

	// A program open as both major and minor gets one tag.
	if ( in_array( 'maior', $card_types, true ) && in_array( 'minor', $card_types, true ) ) {
		$card_types     = array_diff( $card_types, array( 'minor' ) );
		$types['maior'] = __( 'Maior / Minor', 'najdisvujsen' );
	}

	foreach ( $card_types as $type ) {
		$tags[] = array(
			'label' => $types[ $type ],
			'tone'  => 'outline',
		);
	}

	return $tags;
}

/**
 * Returns the in-page navigation of the front page or a program page.
 *
 * @since 0.3.0
 *
 * @return string[] Labels keyed by link target: a section anchor (#…) or a URL.
 */
function najdisvujsen_page_nav() {
	if ( is_front_page() ) {
		$data = najdisvujsen_get_data( get_queried_object_id() );
		$nav  = array();

		foreach ( $data['menu']['items'] ?? array() as $item ) {
			$href = 'url' === $item['target'] ? $item['url'] : '#' . $item['target'];

			if ( '' !== $item['label'] && '' !== $href && '#' !== $href ) {
				$nav[ $href ] = $item['label'];
			}
		}

		if ( $nav ) {
			return $nav;
		}

		$nav = array( '#univerzita' => __( 'Proč u nás', 'najdisvujsen' ) );

		if ( $data && najdisvujsen_get_catalogue() ) {
			$nav['#programy'] = __( 'Programy', 'najdisvujsen' );
		}

		if ( $data && ( najdisvujsen_open_days( $data['dod']['dates'] ) || '' !== $data['dod']['text'] ) ) {
			$nav['#dod'] = __( 'DOD', 'najdisvujsen' );
		}

		if ( $data && najdisvujsen_has_content( array( $data['slovensko']['text'], $data['slovensko']['trains'] ) ) ) {
			$nav['#slovensko'] = __( 'Ze Slovenska', 'najdisvujsen' );
		}

		return $nav;
	}

	if ( ! is_singular( NAJDISVUJSEN_OBOR ) ) {
		return array();
	}

	$nav = array();

	foreach ( najdisvujsen_obor_sections( get_queried_object_id() ) as $section ) {
		if ( '' !== $section['nav'] && ! isset( $nav[ '#' . $section['anchor'] ] ) ) {
			$nav[ '#' . $section['anchor'] ] = $section['nav'];
		}
	}

	if ( ! isset( $nav['#prijimacky'] ) ) {
		$nav['#prijimacky'] = __( 'Přijímačky', 'najdisvujsen' );
	}

	return $nav;
}

/**
 * Returns upcoming open day dates.
 *
 * @since 0.5.0
 *
 * @param array[] $dates Dates with keys date (Y-m-d) and time.
 * @return array[] Dates from today on, with keys day, date and time for display.
 */
function najdisvujsen_open_days( $dates ) {
	$today = wp_date( 'Y-m-d' );
	$days  = array(
		__( 'neděle', 'najdisvujsen' ),
		__( 'pondělí', 'najdisvujsen' ),
		__( 'úterý', 'najdisvujsen' ),
		__( 'středa', 'najdisvujsen' ),
		__( 'čtvrtek', 'najdisvujsen' ),
		__( 'pátek', 'najdisvujsen' ),
		__( 'sobota', 'najdisvujsen' ),
	);
	$items = array();

	foreach ( $dates as $date ) {
		if ( '' === $date['date'] || $date['date'] < $today ) {
			continue;
		}

		$time    = strtotime( $date['date'] . ' 12:00:00' );
		$items[] = array(
			'day'  => $days[ (int) gmdate( 'w', $time ) ],
			'date' => gmdate( 'j. n. Y', $time ),
			'time' => $date['time'],
		);
	}

	return $items;
}
