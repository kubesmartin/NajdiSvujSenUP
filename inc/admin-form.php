<?php
/**
 * Edit form of structured content: study program pages and the front page.
 *
 * Replaces the block editor with a form of tabs and fields built from the
 * field definitions, saves the values as post meta and keeps a readable copy
 * in the post content for revisions and search.
 *
 * @package NajdiSvujSen
 * @since 0.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const NAJDISVUJSEN_FORM = 'najdisvujsen_form';

/**
 * Turns off the block editor for structured content.
 *
 * @since 0.5.0
 *
 * @param bool    $enabled Whether to use the block editor.
 * @param WP_Post $post    Post.
 * @return bool Whether to use the block editor.
 */
function najdisvujsen_form_block_editor( $enabled, $post ) {
	return najdisvujsen_structured_type( $post ) ? false : $enabled;
}
add_filter( 'use_block_editor_for_post', 'najdisvujsen_form_block_editor', 10, 2 );

/**
 * Returns the post edited on the current admin screen, if it has structured content.
 *
 * @since 0.5.0
 *
 * @return WP_Post|null Post.
 */
function najdisvujsen_form_screen_post() {
	global $post;

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'post' !== $screen->base || ! $post instanceof WP_Post || ! najdisvujsen_structured_type( $post ) ) {
		return null;
	}

	return $post;
}

/**
 * Prepares the edit screen: removes boxes replaced by the form.
 *
 * @since 0.5.0
 */
function najdisvujsen_form_load_screen() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only selects the screen layout.
	$type    = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only selects the screen layout.

	if ( ( $post_id && najdisvujsen_structured_type( $post_id ) && 'page' === get_post_type( $post_id ) ) ) {
		remove_post_type_support( 'page', 'editor' );
		remove_post_type_support( 'page', 'page-attributes' );
	}

	if ( ( $post_id && najdisvujsen_structured_type( $post_id ) ) || NAJDISVUJSEN_OBOR === $type ) {
		add_action( 'add_meta_boxes', 'najdisvujsen_form_remove_boxes', 99 );
		add_action( 'admin_enqueue_scripts', 'najdisvujsen_form_assets' );
	}
}
add_action( 'load-post.php', 'najdisvujsen_form_load_screen' );
add_action( 'load-post-new.php', 'najdisvujsen_form_load_screen' );

/**
 * Removes meta boxes whose fields are part of the form.
 *
 * @since 0.5.0
 */
function najdisvujsen_form_remove_boxes() {
	foreach ( array( NAJDISVUJSEN_OBOR, 'page' ) as $screen ) {
		remove_meta_box( 'postexcerpt', $screen, 'normal' );
		remove_meta_box( 'postimagediv', $screen, 'side' );
		remove_meta_box( 'najdisvujsen-page-header', $screen, 'side' );
		remove_meta_box( 'pageparentdiv', $screen, 'side' );

		if ( najdisvujsen_is_manager() ) {
			remove_meta_box( 'slugdiv', $screen, 'normal' );
		}
	}
}

/**
 * Enqueues scripts and styles of the edit form.
 *
 * @since 0.5.0
 */
function najdisvujsen_form_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_media();
	wp_enqueue_editor();
	wp_enqueue_style( 'najdisvujsen-admin', NAJDISVUJSEN_URI . '/assets/admin/form.css', array(), $version );
	wp_enqueue_script( 'najdisvujsen-admin', NAJDISVUJSEN_URI . '/assets/admin/form.js', array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), $version, true );
	wp_localize_script(
		'najdisvujsen-admin',
		'najdisvujsenForm',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'previewNonce' => wp_create_nonce( 'najdisvujsen_preview' ),
			'isManager'    => najdisvujsen_is_manager(),
			'i18n'         => array(
				'chooseImage'   => __( 'Vybrat fotografii', 'najdisvujsen' ),
				'chooseImages'  => __( 'Vybrat fotografie', 'najdisvujsen' ),
				'useImage'      => __( 'Použít fotografii', 'najdisvujsen' ),
				'useImages'     => __( 'Přidat vybrané', 'najdisvujsen' ),
				'remove'        => __( 'Odebrat', 'najdisvujsen' ),
				'confirmRemove' => __( 'Opravdu odstranit? Změna se projeví po uložení.', 'najdisvujsen' ),
				'required'      => __( 'Vyplňte prosím povinná pole:', 'najdisvujsen' ),
				'unsaved'       => __( 'Máte neuložené změny. Opravdu chcete stránku opustit?', 'najdisvujsen' ),
				'previewError'  => __( 'Náhled se nepodařilo připravit. Zkuste to prosím znovu.', 'najdisvujsen' ),
				'empty'         => __( '(bez názvu)', 'najdisvujsen' ),
				'chars'         => __( 'znaků', 'najdisvujsen' ),
				'tooLong'       => __( 'Text je delší, než se vejde.', 'najdisvujsen' ),
				'maxReached'    => __( 'Víc položek se sem nevejde.', 'najdisvujsen' ),
				'highlight'     => __( 'Zvýrazněný box', 'najdisvujsen' ),
				'subheading'    => __( 'Podnadpis', 'najdisvujsen' ),
				'paragraph'     => __( 'Odstavec', 'najdisvujsen' ),
			),
		)
	);
}

/**
 * Sets the title placeholder of study program pages.
 *
 * @since 0.5.0
 *
 * @param string  $text Placeholder.
 * @param WP_Post $post Post.
 * @return string Placeholder.
 */
function najdisvujsen_form_title_placeholder( $text, $post ) {
	return NAJDISVUJSEN_OBOR === $post->post_type ? __( 'Název oboru, např. Historie', 'najdisvujsen' ) : $text;
}
add_filter( 'enter_title_here', 'najdisvujsen_form_title_placeholder', 10, 2 );

/**
 * Prints the edit form below the title.
 *
 * @since 0.5.0
 *
 * @param WP_Post $post Edited post.
 */
function najdisvujsen_form_render( $post ) {
	$type = najdisvujsen_structured_type( $post );

	if ( ! $type ) {
		return;
	}

	$data      = najdisvujsen_fields_fill( najdisvujsen_schema_fields( $type['schema'] ), get_post_meta( $post->ID, $type['key'], true ) );
	$is_front  = '_najdisvujsen_front' === $type['key'];
	$permalink = 'publish' === $post->post_status ? get_permalink( $post ) : '';
	$anchors   = array(
		'proc'      => $is_front ? 'univerzita' : 'proc',
		'katalog'   => 'programy',
		'mesto'     => 'univerzitni-mesto',
		'dod'       => 'dod',
		'zivot'     => 'zivot',
		'slovensko' => 'slovensko',
	);

	wp_nonce_field( 'najdisvujsen_form_' . $post->ID, 'najdisvujsen_form_nonce' );

	printf(
		'<p class="nsj-title-help">%1$s <a href="%2$s">%3$s</a></p>',
		$is_front ? '' : esc_html__( 'Název oboru nahoře se zobrazuje v seznamu oborů a v záložce prohlížeče. Na webu se v bublině ukáže „Krátký název v záhlaví“.', 'najdisvujsen' ),
		esc_url( najdisvujsen_help_url() ),
		esc_html__( 'Návod k úpravám', 'najdisvujsen' )
	);
	?>
	<div class="nsj-form" data-post="<?php echo esc_attr( (string) $post->ID ); ?>" data-permalink="<?php echo esc_url( $permalink ); ?>">
		<div class="nsj-errors notice notice-error inline" hidden role="alert"></div>
		<nav class="nsj-tabs" aria-label="<?php esc_attr_e( 'Části stránky', 'najdisvujsen' ); ?>">
			<?php
			$najdisvujsen_index = 0;

			foreach ( $type['schema'] as $tab => $definition ) :
				++$najdisvujsen_index;
				?>
				<button type="button" class="nsj-tab" data-tab="<?php echo esc_attr( $tab ); ?>" aria-controls="<?php echo esc_attr( 'nsj-panel-' . $tab ); ?>">
					<span class="nsj-tab__num"><?php echo esc_html( (string) $najdisvujsen_index ); ?></span>
					<span class="dashicons dashicons-<?php echo esc_attr( $definition['icon'] ); ?>" aria-hidden="true"></span>
					<span class="nsj-tab__label"><?php echo esc_html( $definition['label'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</nav>
		<div class="nsj-panels">
			<?php foreach ( $type['schema'] as $tab => $definition ) : ?>
				<section class="nsj-panel" id="<?php echo esc_attr( 'nsj-panel-' . $tab ); ?>" data-panel="<?php echo esc_attr( $tab ); ?>" hidden>
					<header class="nsj-panel__head">
						<h2 class="nsj-panel__title"><?php echo esc_html( $definition['label'] ); ?></h2>
						<?php
						$najdisvujsen_anchor = $anchors[ $tab ] ?? ( in_array( $tab, array( 'zakladni', 'uvod', 'extra', 'video' ), true ) ? '' : $tab );

						if ( $permalink ) :
							?>
							<a class="nsj-panel__view" href="<?php echo esc_url( $permalink . ( $najdisvujsen_anchor ? '#' . $najdisvujsen_anchor : '' ) ); ?>" target="_blank" rel="noopener">
								<?php esc_html_e( 'Zobrazit na webu', 'najdisvujsen' ); ?>
								<span class="dashicons dashicons-external" aria-hidden="true"></span>
							</a>
						<?php endif; ?>
					</header>
					<?php if ( ! empty( $definition['intro'] ) ) : ?>
						<p class="nsj-panel__intro"><?php echo esc_html( $definition['intro'] ); ?></p>
					<?php endif; ?>
					<?php
					foreach ( $definition['fields'] as $key => $field ) {
						$najdisvujsen_path  = 'zakladni' === $tab ? array( $key ) : array( $tab, $key );
						$najdisvujsen_value = 'zakladni' === $tab ? ( $data[ $key ] ?? null ) : ( $data[ $tab ][ $key ] ?? null );

						najdisvujsen_form_field( $field, $najdisvujsen_path, $najdisvujsen_value, $post );
					}
					?>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}
add_action( 'edit_form_after_title', 'najdisvujsen_form_render' );

/**
 * Builds the input name of a field from its data path.
 *
 * @since 0.5.0
 *
 * @param string[] $path Data path.
 * @return string Input name.
 */
function najdisvujsen_form_name( $path ) {
	return NAJDISVUJSEN_FORM . '[' . implode( '][', $path ) . ']';
}

/**
 * Builds the element ID of a field from its data path.
 *
 * @since 0.5.0
 *
 * @param string[] $path Data path.
 * @return string Element ID.
 */
function najdisvujsen_form_id( $path ) {
	return 'nsj-' . implode( '-', $path );
}

/**
 * Prints one form field.
 *
 * @since 0.5.0
 *
 * @param array    $field Field definition.
 * @param string[] $path  Data path.
 * @param mixed    $value Current value.
 * @param WP_Post  $post  Edited post.
 */
function najdisvujsen_form_field( $field, $path, $value, $post ) {
	$type     = $field['type'];
	$id       = najdisvujsen_form_id( $path );
	$name     = najdisvujsen_form_name( $path );
	$required = ! empty( $field['required'] );
	$classes  = 'nsj-field nsj-field--' . $type . ( ! empty( $field['size'] ) ? ' nsj-field--' . $field['size'] : '' );

	if ( 'group' === $type ) {
		?>
		<fieldset class="<?php echo esc_attr( $classes ); ?>">
			<legend class="nsj-field__label"><?php echo esc_html( $field['label'] ?? '' ); ?></legend>
			<?php if ( ! empty( $field['help'] ) ) : ?>
				<p class="nsj-field__help"><?php echo esc_html( $field['help'] ); ?></p>
			<?php endif; ?>
			<div class="nsj-group">
				<?php
				foreach ( $field['fields'] as $key => $sub ) {
					najdisvujsen_form_field( $sub, array_merge( $path, array( $key ) ), is_array( $value ) ? ( $value[ $key ] ?? null ) : null, $post );
				}
				?>
			</div>
		</fieldset>
		<?php
		return;
	}

	if ( 'repeater' === $type ) {
		najdisvujsen_form_repeater( $field, $path, is_array( $value ) ? $value : array(), $post );
		return;
	}

	$label_for = in_array( $type, array( 'radio', 'checkboxes', 'gallery', 'image', 'thumbnail', 'list', 'toggle' ), true ) ? '' : $id;
	?>
	<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $required ? ' data-required' : ''; ?> data-label="<?php echo esc_attr( $field['label'] ); ?>">
		<?php if ( 'toggle' !== $type ) : ?>
			<?php if ( $label_for ) : ?>
				<label class="nsj-field__label" for="<?php echo esc_attr( $label_for ); ?>">
			<?php else : ?>
				<span class="nsj-field__label" id="<?php echo esc_attr( $id . '-label' ); ?>">
			<?php endif; ?>
				<?php echo esc_html( $field['label'] ); ?>
				<?php if ( $required ) : ?>
					<span class="nsj-required"><?php esc_html_e( 'povinné', 'najdisvujsen' ); ?></span>
				<?php endif; ?>
			<?php echo $label_for ? '</label>' : '</span>'; ?>
		<?php endif; ?>
		<?php najdisvujsen_form_control( $field, $id, $name, $value, $post ); ?>
		<?php if ( ! empty( $field['help'] ) && 'toggle' !== $type ) : ?>
			<p class="nsj-field__help"><?php echo esc_html( $field['help'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Prints the input of a field.
 *
 * @since 0.5.0
 *
 * @param array   $field Field definition.
 * @param string  $id    Element ID.
 * @param string  $name  Input name.
 * @param mixed   $value Current value.
 * @param WP_Post $post  Edited post.
 */
function najdisvujsen_form_control( $field, $id, $name, $value, $post ) {
	$max         = isset( $field['max'] ) ? (int) $field['max'] : 0;
	$placeholder = $field['placeholder'] ?? '';
	$required    = ! empty( $field['required'] ) ? ' data-required-input' : '';

	switch ( $field['type'] ) {
		case 'excerpt':
			printf(
				'<textarea id="%1$s" name="excerpt" rows="3" class="nsj-input" data-max="%2$d"%3$s>%4$s</textarea>',
				esc_attr( $id ),
				(int) $max,
				$required, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.
				esc_textarea( $post->post_excerpt )
			);
			break;

		case 'thumbnail':
			najdisvujsen_form_image( $id, '_thumbnail_id', (int) get_post_thumbnail_id( $post ), true );
			break;

		case 'image':
			najdisvujsen_form_image( $id, $name, (int) $value, false );
			break;

		case 'gallery':
			najdisvujsen_form_gallery( $id, $name, (array) $value, $field['max'] ?? 0 );
			break;

		case 'textarea':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="%3$d" class="nsj-input" data-max="%4$d" placeholder="%5$s"%6$s>%7$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				(int) ( $field['rows'] ?? 4 ),
				(int) $max,
				esc_attr( $placeholder ),
				$required, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.
				esc_textarea( (string) $value )
			);
			break;

		case 'rich':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="6" class="nsj-rich" data-lite="%3$s">%4$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				empty( $field['lite'] ) ? '0' : '1',
				esc_textarea( (string) $value )
			);
			break;

		case 'select':
			printf( '<select id="%1$s" name="%2$s" class="nsj-input"%3$s>', esc_attr( $id ), esc_attr( $name ), $required ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.

			if ( ! isset( $field['default'] ) ) {
				printf( '<option value="">%s</option>', esc_html__( '— vyberte —', 'najdisvujsen' ) );
			}

			foreach ( $field['options'] as $option => $label ) {
				printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $option ), selected( (string) $value, $option, false ), esc_html( $label ) );
			}

			echo '</select>';
			break;

		case 'radio':
		case 'checkboxes':
			$multiple = 'checkboxes' === $field['type'];

			printf( '<div class="nsj-choices" role="%1$s" aria-labelledby="%2$s"%3$s>', $multiple ? 'group' : 'radiogroup', esc_attr( $id . '-label' ), $required ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.

			foreach ( $field['options'] as $option => $label ) {
				printf(
					'<label class="nsj-choice"><input type="%1$s" name="%2$s" value="%3$s" %4$s> <span>%5$s</span></label>',
					$multiple ? 'checkbox' : 'radio',
					esc_attr( $name . ( $multiple ? '[]' : '' ) ),
					esc_attr( $option ),
					checked( $multiple ? in_array( $option, (array) $value, true ) : (string) $value === $option, true, false ),
					esc_html( $label )
				);
			}

			echo '</div>';
			break;

		case 'toggle':
			printf(
				'<label class="nsj-toggle"><input type="hidden" name="%1$s" value="0"><input type="checkbox" id="%2$s" name="%1$s" value="1" %3$s> <span class="nsj-toggle__label">%4$s</span></label>',
				esc_attr( $name ),
				esc_attr( $id ),
				checked( (bool) $value, true, false ),
				esc_html( $field['label'] )
			);

			if ( ! empty( $field['help'] ) ) {
				printf( '<p class="nsj-field__help">%s</p>', esc_html( $field['help'] ) );
			}
			break;

		case 'list':
			najdisvujsen_form_list( $field, $id, $name, (array) $value );
			break;

		case 'date':
			printf( '<input type="date" id="%1$s" name="%2$s" value="%3$s" class="nsj-input"%4$s>', esc_attr( $id ), esc_attr( $name ), esc_attr( (string) $value ), $required ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.
			break;

		case 'number':
			printf( '<input type="text" inputmode="numeric" pattern="[0-9 ]*" id="%1$s" name="%2$s" value="%3$s" class="nsj-input"%4$s>', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ? (string) $value : '' ), $required ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.
			break;

		default:
			printf(
				'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="nsj-input" placeholder="%5$s" data-max="%6$d"%7$s>',
				'url' === $field['type'] ? 'url' : 'text',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( (string) $value ),
				esc_attr( $placeholder ),
				(int) $max,
				$required // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed attribute.
			);
	}
}

/**
 * Prints a single image picker.
 *
 * @since 0.5.0
 *
 * @param string $id        Element ID.
 * @param string $name      Input name.
 * @param int    $value     Attachment ID.
 * @param bool   $thumbnail Whether the input is the featured image of the post.
 */
function najdisvujsen_form_image( $id, $name, $value, $thumbnail ) {
	$image = $value ? wp_get_attachment_image( $value, 'medium', false, array( 'class' => 'nsj-image__img' ) ) : '';
	?>
	<div class="nsj-image<?php echo $image ? ' has-image' : ''; ?>" data-empty="<?php echo $thumbnail ? '-1' : ''; ?>">
		<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ? (string) $value : ( $thumbnail ? '-1' : '' ) ); ?>">
		<div class="nsj-image__preview"><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?></div>
		<div class="nsj-image__actions">
			<button type="button" class="button nsj-image__choose">
				<span class="nsj-image__choose-empty"><?php esc_html_e( 'Vybrat fotografii', 'najdisvujsen' ); ?></span>
				<span class="nsj-image__choose-change"><?php esc_html_e( 'Změnit fotografii', 'najdisvujsen' ); ?></span>
			</button>
			<button type="button" class="button-link button-link-delete nsj-image__remove"><?php esc_html_e( 'Odebrat', 'najdisvujsen' ); ?></button>
		</div>
	</div>
	<?php
}

/**
 * Prints a gallery picker.
 *
 * @since 0.5.0
 *
 * @param string $id    Element ID.
 * @param string $name  Input name.
 * @param int[]  $ids   Attachment IDs.
 * @param int    $max   Maximum number of images, 0 for no limit.
 */
function najdisvujsen_form_gallery( $id, $name, $ids, $max ) {
	?>
	<div class="nsj-gallery" id="<?php echo esc_attr( $id ); ?>" data-name="<?php echo esc_attr( $name . '[]' ); ?>" data-max="<?php echo esc_attr( (string) $max ); ?>">
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="">
		<ul class="nsj-gallery__list">
			<?php foreach ( $ids as $image_id ) : ?>
				<li class="nsj-gallery__item">
					<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
					<input type="hidden" name="<?php echo esc_attr( $name . '[]' ); ?>" value="<?php echo esc_attr( (string) $image_id ); ?>">
					<button type="button" class="nsj-gallery__remove" aria-label="<?php esc_attr_e( 'Odebrat fotografii', 'najdisvujsen' ); ?>"><span class="dashicons dashicons-no-alt" aria-hidden="true"></span></button>
				</li>
			<?php endforeach; ?>
		</ul>
		<button type="button" class="button nsj-gallery__add"><span class="dashicons dashicons-format-image" aria-hidden="true"></span> <?php esc_html_e( 'Přidat fotografie', 'najdisvujsen' ); ?></button>
		<?php if ( $max ) : ?>
			<span class="nsj-gallery__max">
				<?php
				/* translators: %d: maximum number of photos. */
				echo esc_html( sprintf( __( 'nejvýše %d', 'najdisvujsen' ), $max ) );
				?>
			</span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Prints a list of short texts with add, move and remove controls.
 *
 * @since 0.5.0
 *
 * @param array    $field Field definition.
 * @param string   $id    Element ID.
 * @param string   $name  Input name.
 * @param string[] $items Current values.
 */
function najdisvujsen_form_list( $field, $id, $name, $items ) {
	$multiline = ! empty( $field['multiline'] );
	$row       = static function ( $value ) use ( $name, $multiline, $field ) {
		$input = $multiline
			? sprintf( '<textarea name="%1$s[]" rows="2" class="nsj-input">%2$s</textarea>', esc_attr( $name ), esc_textarea( $value ) )
			: sprintf( '<input type="text" name="%1$s[]" value="%2$s" class="nsj-input" placeholder="%3$s">', esc_attr( $name ), esc_attr( $value ), esc_attr( $field['placeholder'] ?? '' ) );

		return '<li class="nsj-list__item"><span class="nsj-handle dashicons dashicons-menu" aria-hidden="true"></span>' . $input
			. '<button type="button" class="nsj-list__remove" aria-label="' . esc_attr__( 'Odebrat', 'najdisvujsen' ) . '"><span class="dashicons dashicons-no-alt" aria-hidden="true"></span></button></li>';
	};
	?>
	<div class="nsj-list" id="<?php echo esc_attr( $id ); ?>" data-max="<?php echo esc_attr( (string) ( $field['max'] ?? 0 ) ); ?>" aria-labelledby="<?php echo esc_attr( $id . '-label' ); ?>">
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="">
		<ul class="nsj-list__items">
			<?php
			foreach ( $items as $item ) {
				echo $row( (string) $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure.
			}
			?>
		</ul>
		<template class="nsj-list__template"><?php echo $row( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the closure. ?></template>
		<button type="button" class="button nsj-list__add"><span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span> <?php echo esc_html( $field['add'] ?? __( 'Přidat', 'najdisvujsen' ) ); ?></button>
	</div>
	<?php
}

/**
 * Prints a repeater: a list of items with the same fields.
 *
 * @since 0.5.0
 *
 * @param array    $field Field definition.
 * @param string[] $path  Data path.
 * @param array[]  $items Current items.
 * @param WP_Post  $post  Edited post.
 */
function najdisvujsen_form_repeater( $field, $path, $items, $post ) {
	$id = najdisvujsen_form_id( $path );
	?>
	<div class="nsj-field nsj-field--repeater">
		<span class="nsj-field__label"><?php echo esc_html( $field['label'] ); ?></span>
		<?php if ( ! empty( $field['help'] ) ) : ?>
			<p class="nsj-field__help"><?php echo esc_html( $field['help'] ); ?></p>
		<?php endif; ?>
		<div class="nsj-repeater" id="<?php echo esc_attr( $id ); ?>" data-name="<?php echo esc_attr( najdisvujsen_form_name( $path ) ); ?>" data-max="<?php echo esc_attr( (string) ( $field['max'] ?? 0 ) ); ?>" data-next="<?php echo esc_attr( (string) count( $items ) ); ?>">
			<input type="hidden" name="<?php echo esc_attr( najdisvujsen_form_name( $path ) ); ?>" value="">
			<ol class="nsj-repeater__items">
				<?php
				foreach ( array_values( $items ) as $index => $item ) {
					najdisvujsen_form_repeater_item( $field, $path, (string) $index, $item, $post, true );
				}
				?>
			</ol>
			<template class="nsj-repeater__template">
				<?php najdisvujsen_form_repeater_item( $field, $path, '__i__', najdisvujsen_fields_defaults( $field['fields'] ), $post, false ); ?>
			</template>
			<button type="button" class="button button-secondary nsj-repeater__add">
				<span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span>
				<?php echo esc_html( $field['add'] ); ?>
			</button>
		</div>
	</div>
	<?php
}

/**
 * Prints one repeater item.
 *
 * @since 0.5.0
 *
 * @param array    $field     Repeater definition.
 * @param string[] $path      Data path of the repeater.
 * @param string   $index     Item index or placeholder.
 * @param array    $item      Item values.
 * @param WP_Post  $post      Edited post.
 * @param bool     $collapsed Whether the item starts collapsed.
 */
function najdisvujsen_form_repeater_item( $field, $path, $index, $item, $post, $collapsed ) {
	$title = isset( $field['title'] ) ? (string) ( $item[ $field['title'] ] ?? '' ) : '';
	$badge = '';

	if ( isset( $field['badge'] ) && ! empty( $item[ $field['badge'] ] ) ) {
		$options = $field['fields'][ $field['badge'] ]['options'] ?? array();
		$badge   = $options[ $item[ $field['badge'] ] ] ?? '';
	}

	if ( isset( $field['title'] ) && 'date' === ( $field['fields'][ $field['title'] ]['type'] ?? '' ) && $title ) {
		$title = wp_date( 'j. n. Y', strtotime( $title . ' 12:00:00' ) );
	}

	$thumb = isset( $field['thumb'] ) && ! empty( $item[ $field['thumb'] ] ) ? wp_get_attachment_image( (int) $item[ $field['thumb'] ], array( 40, 40 ) ) : '';
	?>
	<li class="nsj-item<?php echo $collapsed ? ' is-collapsed' : ''; ?>" data-title-field="<?php echo esc_attr( $field['title'] ?? '' ); ?>" data-badge-field="<?php echo esc_attr( $field['badge'] ?? '' ); ?>" data-item-label="<?php echo esc_attr( $field['item'] ); ?>">
		<div class="nsj-item__head">
			<span class="nsj-handle dashicons dashicons-menu" aria-hidden="true" title="<?php esc_attr_e( 'Přetažením změníte pořadí', 'najdisvujsen' ); ?>"></span>
			<button type="button" class="nsj-item__toggle" aria-expanded="<?php echo $collapsed ? 'false' : 'true'; ?>">
				<span class="nsj-item__thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?></span>
				<span class="nsj-item__title"><?php echo esc_html( '' !== $title ? $title : $field['item'] ); ?></span>
				<span class="nsj-item__badge"<?php echo $badge ? '' : ' hidden'; ?>><?php echo esc_html( $badge ); ?></span>
				<span class="nsj-item__chevron dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>
			</button>
			<span class="nsj-item__tools">
				<button type="button" class="nsj-item__up" title="<?php esc_attr_e( 'Posunout nahoru', 'najdisvujsen' ); ?>"><span class="dashicons dashicons-arrow-up-alt" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Posunout nahoru', 'najdisvujsen' ); ?></span></button>
				<button type="button" class="nsj-item__down" title="<?php esc_attr_e( 'Posunout dolů', 'najdisvujsen' ); ?>"><span class="dashicons dashicons-arrow-down-alt" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Posunout dolů', 'najdisvujsen' ); ?></span></button>
				<button type="button" class="nsj-item__copy" title="<?php esc_attr_e( 'Duplikovat', 'najdisvujsen' ); ?>"><span class="dashicons dashicons-admin-page" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Duplikovat', 'najdisvujsen' ); ?></span></button>
				<button type="button" class="nsj-item__remove" title="<?php esc_attr_e( 'Odstranit', 'najdisvujsen' ); ?>"><span class="dashicons dashicons-trash" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Odstranit', 'najdisvujsen' ); ?></span></button>
			</span>
		</div>
		<div class="nsj-item__body">
			<?php
			foreach ( $field['fields'] as $key => $sub ) {
				najdisvujsen_form_field( $sub, array_merge( $path, array( $index, $key ) ), $item[ $key ] ?? najdisvujsen_field_default( $sub ), $post );
			}
			?>
		</div>
	</li>
	<?php
}

/**
 * Returns sanitized form values of the current request for a post.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 * @return array|null Values, or null when the form was not submitted.
 */
function najdisvujsen_form_submitted( $post_id ) {
	static $cache = array();

	if ( array_key_exists( $post_id, $cache ) ) {
		return $cache[ $post_id ];
	}

	$type = najdisvujsen_structured_type( $post_id );

	if ( ! $type
		|| ! isset( $_POST['najdisvujsen_form_nonce'], $_POST[ NAJDISVUJSEN_FORM ] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['najdisvujsen_form_nonce'] ), 'najdisvujsen_form_' . $post_id )
		|| ! current_user_can( 'edit_post', $post_id ) ) {
		$cache[ $post_id ] = null;
		return null;
	}

	$input = wp_unslash( (array) $_POST[ NAJDISVUJSEN_FORM ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized per field.

	$cache[ $post_id ] = najdisvujsen_fields_sanitize( najdisvujsen_schema_fields( $type['schema'] ), $input );

	return $cache[ $post_id ];
}

/**
 * Writes a readable copy of the structured content into the post content.
 *
 * Revisions compare the copy, so editors see what changed.
 *
 * @since 0.5.0
 *
 * @param array $data    Slashed post data to save.
 * @param array $postarr Submitted post data.
 * @return array Post data.
 */
function najdisvujsen_form_content_copy( $data, $postarr ) {
	if ( empty( $postarr['ID'] ) || 'revision' === $data['post_type'] || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return $data;
	}

	$values = najdisvujsen_form_submitted( (int) $postarr['ID'] );

	if ( null !== $values ) {
		$type                 = najdisvujsen_structured_type( (int) $postarr['ID'] );
		$data['post_content'] = wp_slash( najdisvujsen_structured_text( $type['schema'], $values ) );
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'najdisvujsen_form_content_copy', 20, 2 );

/**
 * Saves submitted form values.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 */
function najdisvujsen_form_save( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}

	$values = najdisvujsen_form_submitted( $post_id );

	if ( null === $values ) {
		return;
	}

	update_post_meta( $post_id, najdisvujsen_structured_type( $post_id )['key'], $values );
	delete_transient( najdisvujsen_preview_key( $post_id ) );
}
add_action( 'save_post', 'najdisvujsen_form_save' );

/**
 * Renders structured content as readable plain text.
 *
 * @since 0.5.0
 *
 * @param array[] $schema Tabs.
 * @param array   $data   Values.
 * @return string Text.
 */
function najdisvujsen_structured_text( $schema, $data ) {
	$parts = array();

	foreach ( $schema as $tab => $definition ) {
		$values = 'zakladni' === $tab ? $data : ( $data[ $tab ] ?? array() );
		$body   = najdisvujsen_structured_text_fields( $definition['fields'], $values, '' );

		if ( '' !== $body ) {
			$parts[] = '== ' . $definition['label'] . " ==\n\n" . $body;
		}
	}

	return implode( "\n\n", $parts ) . "\n";
}

/**
 * Renders values of a set of fields as readable plain text.
 *
 * @since 0.5.0
 *
 * @param array[] $fields Field definitions.
 * @param array   $values Values.
 * @param string  $indent Indentation of nested items.
 * @return string Text.
 */
function najdisvujsen_structured_text_fields( $fields, $values, $indent ) {
	$lines = array();
	$plain = static function ( $html ) {
		$html = preg_replace( '#</(p|li|h3|blockquote)>|<br\s*/?>#i', "\n", (string) $html );
		$html = preg_replace( '#<li>#i', '– ', $html );

		return trim( preg_replace( "/\n{2,}/", "\n", html_entity_decode( wp_strip_all_tags( $html ) ) ) );
	};

	foreach ( $fields as $key => $field ) {
		$value = $values[ $key ] ?? null;
		$label = $indent . ( $field['label'] ?? '' );

		if ( null === $value || '' === $value || array() === $value || false === $value || 0 === $value ) {
			continue;
		}

		switch ( $field['type'] ) {
			case 'group':
				$body    = najdisvujsen_structured_text_fields( $field['fields'], $value, $indent );
				$lines[] = '' !== $body ? $label . ":\n" . $body : '';
				break;
			case 'repeater':
				foreach ( $value as $index => $item ) {
					$title   = isset( $field['title'] ) ? (string) ( $item[ $field['title'] ] ?? '' ) : '';
					$lines[] = $indent . '# ' . $field['item'] . ' ' . ( $index + 1 ) . ( '' !== $title ? ': ' . $title : '' ) . "\n" . najdisvujsen_structured_text_fields( $field['fields'], $item, $indent . '   ' );
				}
				break;
			case 'rich':
				$lines[] = $label . ":\n" . $indent . str_replace( "\n", "\n" . $indent, $plain( $value ) );
				break;
			case 'list':
				$lines[] = $label . ":\n" . $indent . '– ' . implode( "\n" . $indent . '– ', $value );
				break;
			case 'gallery':
				$lines[] = $label . ': ' . implode( ', ', array_map( 'basename', array_map( 'wp_get_attachment_url', $value ) ) );
				break;
			case 'image':
				$lines[] = $label . ': ' . basename( (string) wp_get_attachment_url( $value ) );
				break;
			case 'checkboxes':
				$lines[] = $label . ': ' . implode( ', ', array_intersect_key( $field['options'], array_flip( $value ) ) );
				break;
			case 'select':
			case 'radio':
				$lines[] = $label . ': ' . ( $field['options'][ $value ] ?? $value );
				break;
			case 'toggle':
				$lines[] = $label . ': ' . __( 'ano', 'najdisvujsen' );
				break;
			case 'date':
				$lines[] = $label . ': ' . wp_date( 'j. n. Y', strtotime( $value . ' 12:00:00' ) );
				break;
			default:
				$lines[] = $label . ': ' . $value;
		}
	}

	return implode( "\n", array_filter( $lines, 'strlen' ) );
}

/**
 * Returns the transient key of preview values of a post for the current user.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 * @return string Transient key.
 */
function najdisvujsen_preview_key( $post_id ) {
	return 'najdisvujsen_preview_' . $post_id . '_' . get_current_user_id();
}

/**
 * Stores unsaved form values for a preview and returns the preview URL.
 *
 * @since 0.5.0
 */
function najdisvujsen_ajax_preview() {
	check_ajax_referer( 'najdisvujsen_preview', 'nonce' );

	$post_id = isset( $_POST['post_ID'] ) ? absint( $_POST['post_ID'] ) : 0;
	$post    = get_post( $post_id );

	if ( ! $post || ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( null, 403 );
	}

	$type  = najdisvujsen_structured_type( $post );
	$input = isset( $_POST[ NAJDISVUJSEN_FORM ] ) ? wp_unslash( (array) $_POST[ NAJDISVUJSEN_FORM ] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized per field.

	if ( ! $type ) {
		wp_send_json_error( null, 400 );
	}

	set_transient(
		najdisvujsen_preview_key( $post_id ),
		array(
			'data'      => najdisvujsen_fields_sanitize( najdisvujsen_schema_fields( $type['schema'] ), $input ),
			'title'     => isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : $post->post_title,
			'excerpt'   => isset( $_POST['excerpt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['excerpt'] ) ) : $post->post_excerpt,
			'thumbnail' => isset( $_POST['_thumbnail_id'] ) ? max( 0, (int) $_POST['_thumbnail_id'] ) : (int) get_post_thumbnail_id( $post ),
		),
		HOUR_IN_SECONDS
	);

	$url = 'publish' === $post->post_status ? get_permalink( $post ) : get_preview_post_link( $post );

	wp_send_json_success( array( 'url' => add_query_arg( 'najdisvujsen_preview', wp_create_nonce( 'najdisvujsen_preview_' . $post_id ), $url ) ) );
}
add_action( 'wp_ajax_najdisvujsen_preview', 'najdisvujsen_ajax_preview' );

/**
 * Returns unsaved preview values of a post when the current request is its preview.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 * @return array|null Preview values, or null outside a preview.
 */
function najdisvujsen_preview_data( $post_id ) {
	if ( is_admin() || ! isset( $_GET['najdisvujsen_preview'] ) || ! is_user_logged_in() ) {
		return null;
	}

	$nonce = sanitize_key( wp_unslash( $_GET['najdisvujsen_preview'] ) );

	if ( ! wp_verify_nonce( $nonce, 'najdisvujsen_preview_' . $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return null;
	}

	$preview = get_transient( najdisvujsen_preview_key( $post_id ) );

	return is_array( $preview ) ? $preview : null;
}

/**
 * Uses unsaved title, excerpt and photo in a preview.
 *
 * @since 0.5.0
 *
 * @param mixed  $value     Meta value or post field.
 * @param int    $object_id Post ID.
 * @param string $meta_key  Meta key.
 * @return mixed Value.
 */
function najdisvujsen_preview_thumbnail( $value, $object_id, $meta_key ) {
	if ( '_thumbnail_id' !== $meta_key ) {
		return $value;
	}

	$preview = najdisvujsen_preview_data( $object_id );

	return null !== $preview ? array( $preview['thumbnail'] ? $preview['thumbnail'] : '' ) : $value;
}
add_filter( 'get_post_metadata', 'najdisvujsen_preview_thumbnail', 10, 3 );

/**
 * Uses the unsaved title in a preview.
 *
 * @since 0.5.0
 *
 * @param string $title   Title.
 * @param int    $post_id Post ID.
 * @return string Title.
 */
function najdisvujsen_preview_title( $title, $post_id = 0 ) {
	$preview = $post_id ? najdisvujsen_preview_data( (int) $post_id ) : null;

	return null !== $preview && '' !== $preview['title'] ? $preview['title'] : $title;
}
add_filter( 'the_title', 'najdisvujsen_preview_title', 10, 2 );

/**
 * Uses the unsaved excerpt in a preview.
 *
 * @since 0.5.0
 *
 * @param string  $excerpt Excerpt.
 * @param WP_Post $post    Post.
 * @return string Excerpt.
 */
function najdisvujsen_preview_excerpt( $excerpt, $post = null ) {
	$preview = $post ? najdisvujsen_preview_data( $post->ID ) : null;

	return null !== $preview ? $preview['excerpt'] : $excerpt;
}
add_filter( 'get_the_excerpt', 'najdisvujsen_preview_excerpt', 10, 2 );

/**
 * Prints the preview notice bar on the front end.
 *
 * @since 0.5.0
 */
function najdisvujsen_preview_notice() {
	$id = get_queried_object_id();

	if ( ! $id || null === najdisvujsen_preview_data( $id ) ) {
		return;
	}

	printf(
		'<div class="preview-bar" role="status">%s</div>',
		esc_html__( 'Náhled neuložených změn – návštěvníci webu je zatím nevidí. Změny uložíte v administraci.', 'najdisvujsen' )
	);
}
add_action( 'wp_body_open', 'najdisvujsen_preview_notice' );

/**
 * Keeps search engines and caches away from previews.
 *
 * @since 0.5.0
 */
function najdisvujsen_preview_headers() {
	if ( isset( $_GET['najdisvujsen_preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only sends headers.
		nocache_headers();
		header( 'X-Robots-Tag: noindex' );
	}
}
add_action( 'send_headers', 'najdisvujsen_preview_headers' );

/**
 * Adds the front page editor to the admin menu.
 *
 * @since 0.5.0
 */
function najdisvujsen_front_menu() {
	$front = (int) get_option( 'page_on_front' );

	if ( ! $front || ! current_user_can( 'edit_post', $front ) ) {
		return;
	}

	add_menu_page(
		__( 'Titulní stránka', 'najdisvujsen' ),
		__( 'Titulní stránka', 'najdisvujsen' ),
		'edit_pages',
		'post.php?post=' . $front . '&action=edit',
		'',
		'dashicons-admin-home',
		3
	);
}
add_action( 'admin_menu', 'najdisvujsen_front_menu' );

/**
 * Highlights the front page menu item while the front page is edited.
 *
 * @since 0.5.0
 *
 * @param string $parent_file Parent menu file.
 * @return string Parent menu file.
 */
function najdisvujsen_front_menu_highlight( $parent_file ) {
	global $post, $pagenow;

	$front = (int) get_option( 'page_on_front' );

	if ( 'post.php' === $pagenow && $post instanceof WP_Post && $front === $post->ID ) {
		return 'post.php?post=' . $front . '&action=edit';
	}

	return $parent_file;
}
add_filter( 'parent_file', 'najdisvujsen_front_menu_highlight' );

/**
 * Prints warnings about content that will not show on the site.
 *
 * @since 0.5.0
 */
function najdisvujsen_form_notices() {
	$post = najdisvujsen_form_screen_post();

	if ( ! $post ) {
		return;
	}

	$data     = najdisvujsen_get_data( $post );
	$warnings = array();

	if ( isset( $data['dod']['dates'] ) ) {
		foreach ( $data['dod']['dates'] as $date ) {
			if ( $date['date'] && $date['date'] < wp_date( 'Y-m-d' ) ) {
				/* translators: %s: date. */
				$warnings[] = sprintf( __( 'Termín dne otevřených dveří %s už proběhl a na webu se nezobrazuje. Můžete ho smazat nebo přidat nový.', 'najdisvujsen' ), wp_date( 'j. n. Y', strtotime( $date['date'] . ' 12:00:00' ) ) );
			}
		}
	}

	if ( NAJDISVUJSEN_OBOR === $post->post_type && 'publish' === $post->post_status && ! najdisvujsen_obor_catalogue_items( $post->ID ) ) {
		$warnings[] = __( 'Žádný bakalářský ani magisterský program oboru není zaškrtnutý pro katalog na titulní stránce, obor tam tedy nebude.', 'najdisvujsen' );
	}

	foreach ( $warnings as $warning ) {
		printf( '<div class="notice notice-warning"><p>%s</p></div>', esc_html( $warning ) );
	}
}
add_action( 'admin_notices', 'najdisvujsen_form_notices' );
