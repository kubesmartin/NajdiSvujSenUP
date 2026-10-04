<?php
/**
 * Navigation menu walker printing plain links.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints menu items as a flat sequence of links without list markup.
 *
 * @since 0.3.0
 */
class Najdisvujsen_Flat_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the element output.
	 *
	 * @since 0.3.0
	 *
	 * @param string   $output            Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object       Menu item data object.
	 * @param int      $depth             Depth of menu item.
	 * @param stdClass $args              An object of wp_nav_menu() arguments.
	 * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$current = in_array( 'current-menu-item', (array) $data_object->classes, true );

		$output .= sprintf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( $data_object->url ),
			$current ? ' class="is-active" aria-current="page"' : '',
			esc_html( $data_object->title )
		);
	}

	/**
	 * Ends the element output.
	 *
	 * @since 0.3.0
	 *
	 * @param string   $output      Used to append additional content (passed by reference).
	 * @param WP_Post  $data_object Menu item data object.
	 * @param int      $depth       Depth of menu item.
	 * @param stdClass $args        An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
