<?php
/**
 * Customizer settings.
 *
 * @package NajdiSvujSen
 * @since 0.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the admission link settings with their defaults.
 *
 * @since 0.2.0
 *
 * @return array<string, array{label: string, default: string}> Settings keyed by theme mod name.
 */
function najdisvujsen_admission_settings() {
	return array(
		'najdisvujsen_application_url' => array(
			'label'   => __( 'Odkaz na e-přihlášku', 'najdisvujsen' ),
			'default' => 'https://prihlaska.upol.cz/prihlaska/info.xhtml',
		),
		'najdisvujsen_admission_url'   => array(
			'label'   => __( 'Odkaz na informace o přijímacím řízení', 'najdisvujsen' ),
			'default' => 'https://www.ff.upol.cz/uchazecum/prijimaci-rizeni-202526/',
		),
	);
}

/**
 * Returns an admission link.
 *
 * @since 0.2.0
 *
 * @param string $name Theme mod name.
 * @return string URL.
 */
function najdisvujsen_admission_url( $name ) {
	$settings = najdisvujsen_admission_settings();

	return (string) get_theme_mod( $name, $settings[ $name ]['default'] ?? '' );
}

/**
 * Returns the ID of the YouTube video shown on the front page.
 *
 * @since 0.3.0
 *
 * @return string Video ID, or an empty string to hide the video.
 */
function najdisvujsen_video_id() {
	return najdisvujsen_sanitize_video_id( (string) get_theme_mod( 'najdisvujsen_video_id', 'O1J0tznYfLQ' ) );
}

/**
 * Sanitizes a YouTube video ID, accepting a full video URL as well.
 *
 * @since 0.3.0
 *
 * @param string $value Video ID or URL.
 * @return string Video ID, or an empty string.
 */
function najdisvujsen_sanitize_video_id( $value ) {
	$value = trim( $value );

	if ( preg_match( '#(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{11})#', $value, $matches ) ) {
		return $matches[1];
	}

	return preg_match( '/^[A-Za-z0-9_-]{11}$/', $value ) ? $value : '';
}

/**
 * Registers Customizer settings.
 *
 * @since 0.2.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function najdisvujsen_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'najdisvujsen_admission',
		array(
			'title' => __( 'Přijímací řízení', 'najdisvujsen' ),
		)
	);

	$wp_customize->add_section(
		'najdisvujsen_front_page',
		array(
			'title' => __( 'Titulní stránka', 'najdisvujsen' ),
		)
	);

	$wp_customize->add_setting(
		'najdisvujsen_video_id',
		array(
			'default'           => 'O1J0tznYfLQ',
			'sanitize_callback' => 'najdisvujsen_sanitize_video_id',
		)
	);
	$wp_customize->add_control(
		'najdisvujsen_video_id',
		array(
			'label'       => __( 'Video na YouTube', 'najdisvujsen' ),
			'description' => __( 'Odkaz nebo ID videa. Prázdné pole video skryje.', 'najdisvujsen' ),
			'section'     => 'najdisvujsen_front_page',
			'type'        => 'text',
		)
	);

	foreach ( najdisvujsen_admission_settings() as $name => $setting ) {
		$wp_customize->add_setting(
			$name,
			array(
				'default'           => $setting['default'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$name,
			array(
				'label'   => $setting['label'],
				'section' => 'najdisvujsen_admission',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'najdisvujsen_customize_register' );
