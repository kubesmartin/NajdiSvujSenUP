<?php
/**
 * Page header fields: short title, department and social profiles.
 *
 * @package NajdiSvujSen
 * @since 0.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the page header field definitions.
 *
 * @since 0.2.0
 *
 * @return array<string, array{label: string, type: string}> Fields keyed by meta suffix.
 */
function najdisvujsen_page_header_fields() {
	return array(
		'hero_title'       => array(
			'label' => __( 'Krátký název v záhlaví', 'najdisvujsen' ),
			'type'  => 'text',
		),
		'department'       => array(
			'label' => __( 'Katedra', 'najdisvujsen' ),
			'type'  => 'text',
		),
		'department_url'   => array(
			'label' => __( 'Web katedry', 'najdisvujsen' ),
			'type'  => 'url',
		),
		'social_facebook'  => array(
			'label' => __( 'Facebook', 'najdisvujsen' ),
			'type'  => 'url',
		),
		'social_instagram' => array(
			'label' => __( 'Instagram', 'najdisvujsen' ),
			'type'  => 'url',
		),
		'social_youtube'   => array(
			'label' => __( 'YouTube', 'najdisvujsen' ),
			'type'  => 'url',
		),
		'social_tiktok'    => array(
			'label' => __( 'TikTok', 'najdisvujsen' ),
			'type'  => 'url',
		),
		'careers'          => array(
			'label' => __( 'Profese v záhlaví oboru (jedna na řádek)', 'najdisvujsen' ),
			'type'  => 'textarea',
		),
		'emoji'            => array(
			'label' => __( 'Emoji k úvodu sekce „Proč?“', 'najdisvujsen' ),
			'type'  => 'text',
		),
	);
}

/**
 * Returns a page header field value.
 *
 * @since 0.2.0
 *
 * @param string   $key     Field key.
 * @param int|null $post_id Post ID, defaults to the current post.
 * @return string Field value.
 */
function najdisvujsen_get_page_header_field( $key, $post_id = null ) {
	return (string) get_post_meta( $post_id ? $post_id : get_the_ID(), '_najdisvujsen_' . $key, true );
}

/**
 * Registers the page header meta box.
 *
 * @since 0.2.0
 */
function najdisvujsen_add_page_header_meta_box() {
	add_meta_box(
		'najdisvujsen-page-header',
		__( 'Záhlaví stránky', 'najdisvujsen' ),
		'najdisvujsen_render_page_header_meta_box',
		'page',
		'side'
	);
}
add_action( 'add_meta_boxes', 'najdisvujsen_add_page_header_meta_box' );

/**
 * Renders the page header meta box.
 *
 * @since 0.2.0
 *
 * @param WP_Post $post Current post.
 */
function najdisvujsen_render_page_header_meta_box( $post ) {
	wp_nonce_field( 'najdisvujsen_page_header', 'najdisvujsen_page_header_nonce' );

	foreach ( najdisvujsen_page_header_fields() as $key => $field ) {
		$id = 'najdisvujsen-' . str_replace( '_', '-', $key );

		if ( 'textarea' === $field['type'] ) {
			printf(
				'<p><label for="%1$s">%2$s</label><textarea id="%1$s" name="najdisvujsen_page_header[%3$s]" rows="8" class="widefat">%4$s</textarea></p>',
				esc_attr( $id ),
				esc_html( $field['label'] ),
				esc_attr( $key ),
				esc_textarea( najdisvujsen_get_page_header_field( $key, $post->ID ) )
			);
			continue;
		}

		printf(
			'<p><label for="%1$s">%2$s</label><input type="%3$s" id="%1$s" name="najdisvujsen_page_header[%4$s]" value="%5$s" class="widefat"></p>',
			esc_attr( $id ),
			esc_html( $field['label'] ),
			esc_attr( $field['type'] ),
			esc_attr( $key ),
			esc_attr( najdisvujsen_get_page_header_field( $key, $post->ID ) )
		);
	}

	echo '<p class="description">' . esc_html__( 'Fotografie v záhlaví se nastavuje jako náhledový obrázek, perex jako stručný výpis (excerpt) stránky.', 'najdisvujsen' ) . '</p>';
}

/**
 * Saves the page header fields.
 *
 * @since 0.2.0
 *
 * @param int $post_id Post ID.
 */
function najdisvujsen_save_page_header( $post_id ) {
	if ( ! isset( $_POST['najdisvujsen_page_header_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['najdisvujsen_page_header_nonce'] ), 'najdisvujsen_page_header' )
		|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		|| ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	$values = isset( $_POST['najdisvujsen_page_header'] ) ? wp_unslash( (array) $_POST['najdisvujsen_page_header'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized per field below.

	foreach ( najdisvujsen_page_header_fields() as $key => $field ) {
		$value = isset( $values[ $key ] ) ? (string) $values[ $key ] : '';

		if ( 'url' === $field['type'] ) {
			$value = esc_url_raw( trim( $value ) );
		} elseif ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $value );
		} else {
			$value = sanitize_text_field( $value );
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, '_najdisvujsen_' . $key );
		} else {
			update_post_meta( $post_id, '_najdisvujsen_' . $key, $value );
		}
	}
}
add_action( 'save_post_page', 'najdisvujsen_save_page_header' );
