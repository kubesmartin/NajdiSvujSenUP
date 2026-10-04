<?php
/**
 * Theme functions and definitions.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NAJDISVUJSEN_VERSION', wp_get_theme( 'najdisvujsen' )->get( 'Version' ) );
define( 'NAJDISVUJSEN_DIR', get_template_directory() );
define( 'NAJDISVUJSEN_URI', get_template_directory_uri() );

require NAJDISVUJSEN_DIR . '/inc/setup.php';
require NAJDISVUJSEN_DIR . '/inc/enqueue.php';
require NAJDISVUJSEN_DIR . '/inc/editor.php';
require NAJDISVUJSEN_DIR . '/inc/template-tags.php';
