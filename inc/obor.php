<?php
/**
 * Study program pages ("Obory"): post type, URLs, editor role and access.
 *
 * Program pages keep top-level URLs such as /historie/ like regular pages.
 *
 * @package NajdiSvujSen
 * @since 0.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const NAJDISVUJSEN_OBOR       = 'najdisvujsen_obor';
const NAJDISVUJSEN_MANAGER    = 'najdisvujsen_spravce_oboru';
const NAJDISVUJSEN_CAPS_LEVEL = 1;

/**
 * Registers the study program post type and its content field.
 *
 * @since 0.5.0
 */
function najdisvujsen_register_obor() {
	register_post_type(
		NAJDISVUJSEN_OBOR,
		array(
			'labels'          => array(
				'name'                   => __( 'Obory', 'najdisvujsen' ),
				'singular_name'          => __( 'Obor', 'najdisvujsen' ),
				'menu_name'              => __( 'Obory', 'najdisvujsen' ),
				'all_items'              => __( 'Všechny obory', 'najdisvujsen' ),
				'add_new'                => __( 'Přidat obor', 'najdisvujsen' ),
				'add_new_item'           => __( 'Přidat nový obor', 'najdisvujsen' ),
				'edit_item'              => __( 'Upravit obor', 'najdisvujsen' ),
				'new_item'               => __( 'Nový obor', 'najdisvujsen' ),
				'view_item'              => __( 'Zobrazit obor', 'najdisvujsen' ),
				'view_items'             => __( 'Zobrazit obory', 'najdisvujsen' ),
				'search_items'           => __( 'Hledat obory', 'najdisvujsen' ),
				'not_found'              => __( 'Žádný obor nenalezen.', 'najdisvujsen' ),
				'not_found_in_trash'     => __( 'V koši není žádný obor.', 'najdisvujsen' ),
				'item_published'         => __( 'Obor byl zveřejněn.', 'najdisvujsen' ),
				'item_updated'           => __( 'Obor byl uložen.', 'najdisvujsen' ),
				'item_reverted_to_draft' => __( 'Obor byl vrácen do konceptu.', 'najdisvujsen' ),
				'item_scheduled'         => __( 'Zveřejnění oboru bylo naplánováno.', 'najdisvujsen' ),
			),
			'description'     => __( 'Stránky studijních oborů.', 'najdisvujsen' ),
			'public'          => true,
			'show_in_rest'    => false,
			'menu_position'   => 4,
			'menu_icon'       => 'dashicons-welcome-learn-more',
			'capability_type' => array( 'najdisvujsen_obor', 'najdisvujsen_obory' ),
			'capabilities'    => array( 'create_posts' => 'create_najdisvujsen_obory' ),
			'map_meta_cap'    => true,
			'supports'        => array( 'title', 'excerpt', 'thumbnail', 'revisions' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => NAJDISVUJSEN_OBOR,
		)
	);

	$meta = array(
		'type'              => 'object',
		'single'            => true,
		'show_in_rest'      => false,
		'revisions_enabled' => true,
		'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
			return current_user_can( 'edit_post', $post_id );
		},
	);

	register_post_meta( NAJDISVUJSEN_OBOR, '_najdisvujsen_obor', $meta );
	register_post_meta( 'page', '_najdisvujsen_front', $meta );
}
add_action( 'init', 'najdisvujsen_register_obor' );

/**
 * Builds top-level permalinks of study program pages.
 *
 * @since 0.5.0
 *
 * @param string  $link Permalink.
 * @param WP_Post $post Post.
 * @return string Permalink.
 */
function najdisvujsen_obor_link( $link, $post ) {
	if ( NAJDISVUJSEN_OBOR !== $post->post_type || ! get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft', 'future' ), true ) ) {
		return $link;
	}

	return home_url( user_trailingslashit( $post->post_name ) );
}
add_filter( 'post_type_link', 'najdisvujsen_obor_link', 10, 2 );

/**
 * Resolves top-level URLs that do not belong to a page or post to a study program.
 *
 * @since 0.5.0
 *
 * @param array $vars Query variables.
 * @return array Query variables.
 */
function najdisvujsen_obor_request( $vars ) {
	if ( is_admin() ) {
		return $vars;
	}

	$slug = $vars['pagename'] ?? ( $vars['name'] ?? '' );

	if ( ! $slug || str_contains( $slug, '/' ) || isset( $vars['post_type'] ) || isset( $vars['attachment'] ) ) {
		return $vars;
	}

	$found = get_posts(
		array(
			'name'             => $slug,
			'post_type'        => array( 'page', 'post', NAJDISVUJSEN_OBOR ),
			'post_status'      => 'publish',
			'posts_per_page'   => 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);

	if ( ! $found || NAJDISVUJSEN_OBOR !== get_post_type( $found[0] ) ) {
		return $vars;
	}

	return array(
		NAJDISVUJSEN_OBOR => $slug,
		'post_type'       => NAJDISVUJSEN_OBOR,
		'name'            => $slug,
	);
}
add_filter( 'request', 'najdisvujsen_obor_request' );

/**
 * Keeps slugs of pages, posts and study programs unique among each other.
 *
 * They share the top-level URL space, so a new page must not take a program's URL.
 *
 * @since 0.5.0
 *
 * @param string $slug        Unique slug within the post type.
 * @param int    $post_id     Post ID.
 * @param string $post_status Post status.
 * @param string $post_type   Post type.
 * @param int    $post_parent Parent post ID.
 * @return string Slug unique across the shared types.
 */
function najdisvujsen_obor_unique_slug( $slug, $post_id, $post_status, $post_type, $post_parent ) {
	if ( ! in_array( $post_type, array( 'page', 'post', NAJDISVUJSEN_OBOR ), true ) || ( 'page' === $post_type && $post_parent ) ) {
		return $slug;
	}

	global $wpdb;

	$original = $slug;
	$suffix   = 2;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Runs only on save.
	while ( $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type IN ('page', 'post', %s) AND post_type != %s AND post_parent = 0 AND ID != %d LIMIT 1", $slug, NAJDISVUJSEN_OBOR, $post_type, $post_id ) ) ) {
		$slug = _truncate_post_slug( $original, 200 - ( strlen( (string) $suffix ) + 1 ) ) . '-' . $suffix;
		++$suffix;
	}

	return $slug;
}
add_filter( 'wp_unique_post_slug', 'najdisvujsen_obor_unique_slug', 10, 5 );

/**
 * Returns the capabilities of the study program post type.
 *
 * @since 0.5.0
 *
 * @return string[] Capability names.
 */
function najdisvujsen_obor_caps() {
	return array(
		'edit_najdisvujsen_obory',
		'edit_others_najdisvujsen_obory',
		'edit_published_najdisvujsen_obory',
		'edit_private_najdisvujsen_obory',
		'publish_najdisvujsen_obory',
		'read_private_najdisvujsen_obory',
		'delete_najdisvujsen_obory',
		'delete_others_najdisvujsen_obory',
		'delete_published_najdisvujsen_obory',
		'delete_private_najdisvujsen_obory',
		'create_najdisvujsen_obory',
	);
}

/**
 * Grants study program capabilities and creates the program editor role.
 *
 * Runs once per capability level, roles are stored in the database.
 *
 * @since 0.5.0
 */
function najdisvujsen_setup_roles() {
	if ( (int) get_option( 'najdisvujsen_caps_level' ) >= NAJDISVUJSEN_CAPS_LEVEL ) {
		return;
	}

	foreach ( array( 'administrator', 'editor' ) as $name ) {
		$role = get_role( $name );

		if ( $role ) {
			foreach ( najdisvujsen_obor_caps() as $cap ) {
				$role->add_cap( $cap );
			}
		}
	}

	remove_role( NAJDISVUJSEN_MANAGER );
	add_role(
		NAJDISVUJSEN_MANAGER,
		'Správce oboru',
		array(
			'read'                              => true,
			'upload_files'                      => true,
			'edit_najdisvujsen_obory'           => true,
			'edit_others_najdisvujsen_obory'    => true,
			'edit_published_najdisvujsen_obory' => true,
		)
	);

	update_option( 'najdisvujsen_caps_level', NAJDISVUJSEN_CAPS_LEVEL );
}
add_action( 'init', 'najdisvujsen_setup_roles' );

/**
 * Checks whether a user is a program editor limited to assigned programs.
 *
 * @since 0.5.0
 *
 * @param int $user_id User ID, defaults to the current user.
 * @return bool True for users with only the program editor role.
 */
function najdisvujsen_is_manager( $user_id = 0 ) {
	$user = $user_id ? get_userdata( $user_id ) : wp_get_current_user();

	return $user && $user->exists() && in_array( NAJDISVUJSEN_MANAGER, (array) $user->roles, true ) && ! user_can( $user, 'edit_others_pages' );
}

/**
 * Returns the study programs assigned to a program editor.
 *
 * @since 0.5.0
 *
 * @param int $user_id User ID.
 * @return int[] Post IDs.
 */
function najdisvujsen_manager_obory( $user_id ) {
	return array_values( array_filter( array_map( 'absint', (array) get_user_meta( $user_id, 'najdisvujsen_obory', true ) ) ) );
}

/**
 * Limits program editors to their assigned study programs.
 *
 * @since 0.5.0
 *
 * @param string[] $caps    Primitive capabilities required.
 * @param string   $cap     Capability being checked.
 * @param int      $user_id User ID.
 * @param array    $args    Additional arguments, the post ID first.
 * @return string[] Primitive capabilities required.
 */
function najdisvujsen_obor_map_meta_cap( $caps, $cap, $user_id, $args ) {
	if ( ! in_array( $cap, array( 'edit_post', 'delete_post', 'publish_post' ), true ) || empty( $args[0] ) ) {
		return $caps;
	}

	$post = get_post( $args[0] );

	if ( ! $post ) {
		return $caps;
	}

	if ( 'revision' === $post->post_type ) {
		$post = get_post( $post->post_parent );
	}

	if ( $post && NAJDISVUJSEN_OBOR === $post->post_type && najdisvujsen_is_manager( $user_id ) && ( 'edit_post' !== $cap || ! in_array( $post->ID, najdisvujsen_manager_obory( $user_id ), true ) ) ) {
		return array( 'do_not_allow' );
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'najdisvujsen_obor_map_meta_cap', 10, 4 );

/**
 * Shows program editors only their study programs in the admin list.
 *
 * @since 0.5.0
 *
 * @param WP_Query $query Query.
 */
function najdisvujsen_obor_admin_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || NAJDISVUJSEN_OBOR !== $query->get( 'post_type' ) ) {
		return;
	}

	if ( najdisvujsen_is_manager() ) {
		$ids = najdisvujsen_manager_obory( get_current_user_id() );
		$query->set( 'post__in', $ids ? $ids : array( 0 ) );
	}

	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'najdisvujsen_obor_admin_query' );

/**
 * Removes status counts of all programs from the list for program editors.
 *
 * @since 0.5.0
 *
 * @param string[] $views Status links.
 * @return string[] Status links.
 */
function najdisvujsen_obor_views( $views ) {
	return najdisvujsen_is_manager() ? array() : $views;
}
add_filter( 'views_edit-' . NAJDISVUJSEN_OBOR, 'najdisvujsen_obor_views' );

/**
 * Adds columns to the study program list.
 *
 * @since 0.5.0
 *
 * @param string[] $columns Column labels.
 * @return string[] Column labels.
 */
function najdisvujsen_obor_columns( $columns ) {
	$result = array();

	foreach ( $columns as $key => $label ) {
		$result[ $key ] = $label;

		if ( 'title' === $key ) {
			$result['najdisvujsen_category'] = __( 'Kategorie', 'najdisvujsen' );
			$result['najdisvujsen_programs'] = __( 'Programy', 'najdisvujsen' );

			if ( ! najdisvujsen_is_manager() ) {
				$result['najdisvujsen_managers'] = __( 'Správci', 'najdisvujsen' );
			}
		}
	}

	unset( $result['author'] );

	return $result;
}
add_filter( 'manage_' . NAJDISVUJSEN_OBOR . '_posts_columns', 'najdisvujsen_obor_columns' );

/**
 * Prints study program list columns.
 *
 * @since 0.5.0
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function najdisvujsen_obor_column( $column, $post_id ) {
	$data = najdisvujsen_get_data( $post_id );

	if ( 'najdisvujsen_category' === $column ) {
		echo esc_html( najdisvujsen_program_categories()[ $data['category'] ] ?? '—' );
	} elseif ( 'najdisvujsen_programs' === $column ) {
		$levels = array_count_values( wp_list_pluck( $data['programy']['programs'], 'level' ) );
		$parts  = array();

		foreach ( array(
			'bc'  => 'Bc.',
			'mgr' => 'Mgr.',
			'phd' => 'Ph.D.',
		) as $level => $label ) {
			if ( ! empty( $levels[ $level ] ) ) {
				$parts[] = $label . ' ' . $levels[ $level ];
			}
		}

		echo esc_html( $parts ? implode( ' · ', $parts ) : '—' );
	} elseif ( 'najdisvujsen_managers' === $column ) {
		$names = array();

		foreach ( najdisvujsen_obor_managers( $post_id ) as $user ) {
			$names[] = $user->display_name;
		}

		echo esc_html( $names ? implode( ', ', $names ) : '—' );
	}
}
add_action( 'manage_' . NAJDISVUJSEN_OBOR . '_posts_custom_column', 'najdisvujsen_obor_column', 10, 2 );

/**
 * Returns program editors assigned to a study program.
 *
 * @since 0.5.0
 *
 * @param int $post_id Post ID.
 * @return WP_User[] Users.
 */
function najdisvujsen_obor_managers( $post_id ) {
	static $users = null;

	if ( null === $users ) {
		$users = get_users( array( 'role' => NAJDISVUJSEN_MANAGER ) );
	}

	return array_values(
		array_filter(
			$users,
			static function ( $user ) use ( $post_id ) {
				return in_array( (int) $post_id, najdisvujsen_manager_obory( $user->ID ), true );
			}
		)
	);
}

/**
 * Prints the assigned study programs on the user profile screen.
 *
 * @since 0.5.0
 *
 * @param WP_User $user Edited user.
 */
function najdisvujsen_user_obory_field( $user ) {
	if ( ! current_user_can( 'promote_users' ) && ! current_user_can( 'edit_others_pages' ) ) {
		return;
	}

	$assigned = najdisvujsen_manager_obory( $user->ID );
	$obory    = get_posts(
		array(
			'post_type'      => NAJDISVUJSEN_OBOR,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<h2 id="najdisvujsen-obory"><?php esc_html_e( 'Spravované obory', 'najdisvujsen' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'Obory', 'najdisvujsen' ); ?></th>
			<td>
				<?php wp_nonce_field( 'najdisvujsen_user_obory', 'najdisvujsen_user_obory_nonce' ); ?>
				<p class="description" style="margin-bottom: 8px;">
					<?php esc_html_e( 'Platí pro roli „Správce oboru“: uživatel uvidí a upraví jen zaškrtnuté obory. Ostatní role tím nejsou omezené.', 'najdisvujsen' ); ?>
				</p>
				<fieldset class="najdisvujsen-user-obory">
					<?php foreach ( $obory as $obor ) : ?>
						<label>
							<input type="checkbox" name="najdisvujsen_obory[]" value="<?php echo esc_attr( (string) $obor->ID ); ?>" <?php checked( in_array( $obor->ID, $assigned, true ) ); ?>>
							<?php echo esc_html( get_the_title( $obor ) ); ?>
						</label>
					<?php endforeach; ?>
				</fieldset>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'najdisvujsen_user_obory_field' );
add_action( 'edit_user_profile', 'najdisvujsen_user_obory_field' );
add_action( 'user_new_form', 'najdisvujsen_user_obory_field' );

/**
 * Saves the assigned study programs of a user.
 *
 * @since 0.5.0
 *
 * @param int $user_id User ID.
 */
function najdisvujsen_save_user_obory( $user_id ) {
	if ( ! isset( $_POST['najdisvujsen_user_obory_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['najdisvujsen_user_obory_nonce'] ), 'najdisvujsen_user_obory' )
		|| ( ! current_user_can( 'promote_users' ) && ! current_user_can( 'edit_others_pages' ) )
		|| ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	$ids = isset( $_POST['najdisvujsen_obory'] ) ? array_values( array_filter( array_map( 'absint', (array) $_POST['najdisvujsen_obory'] ) ) ) : array();
	$ids = array_values(
		array_filter(
			$ids,
			static function ( $id ) {
				return NAJDISVUJSEN_OBOR === get_post_type( $id );
			}
		)
	);

	if ( $ids ) {
		update_user_meta( $user_id, 'najdisvujsen_obory', $ids );
	} else {
		delete_user_meta( $user_id, 'najdisvujsen_obory' );
	}
}
add_action( 'personal_options_update', 'najdisvujsen_save_user_obory' );
add_action( 'edit_user_profile_update', 'najdisvujsen_save_user_obory' );
add_action( 'user_register', 'najdisvujsen_save_user_obory' );

/**
 * Sends program editors to their study programs after login.
 *
 * @since 0.5.0
 *
 * @param string           $redirect_to Redirect URL.
 * @param string           $requested   Requested redirect URL.
 * @param WP_User|WP_Error $user        Logged in user.
 * @return string Redirect URL.
 */
function najdisvujsen_manager_login_redirect( $redirect_to, $requested, $user ) {
	if ( $user instanceof WP_User && najdisvujsen_is_manager( $user->ID ) && ( ! $requested || str_ends_with( untrailingslashit( $requested ), 'wp-admin' ) ) ) {
		return admin_url( 'edit.php?post_type=' . NAJDISVUJSEN_OBOR );
	}

	return $redirect_to;
}
add_filter( 'login_redirect', 'najdisvujsen_manager_login_redirect', 10, 3 );

/**
 * Simplifies the admin menu and dashboard for program editors.
 *
 * @since 0.5.0
 */
function najdisvujsen_manager_admin() {
	if ( ! najdisvujsen_is_manager() ) {
		return;
	}

	remove_menu_page( 'index.php' );
	remove_menu_page( 'tools.php' );

	global $pagenow;

	if ( 'index.php' === $pagenow ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=' . NAJDISVUJSEN_OBOR ) );
		exit;
	}
}
add_action( 'admin_menu', 'najdisvujsen_manager_admin', 99 );

/**
 * Keeps the URL and status of a study program for program editors.
 *
 * Program editors update content; renaming URLs or taking a program off the
 * site is left to users who manage all programs.
 *
 * @since 0.5.0
 *
 * @param array $data    Slashed post data to save.
 * @param array $postarr Submitted post data.
 * @return array Post data.
 */
function najdisvujsen_obor_lock_slug( $data, $postarr ) {
	if ( NAJDISVUJSEN_OBOR !== $data['post_type'] || empty( $postarr['ID'] ) || ! najdisvujsen_is_manager() ) {
		return $data;
	}

	$post = get_post( $postarr['ID'] );

	if ( $post && $post->post_name ) {
		$data['post_name'] = $post->post_name;
	}

	if ( $post && 'publish' === $post->post_status && 'trash' !== $data['post_status'] ) {
		$data['post_status'] = 'publish';
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'najdisvujsen_obor_lock_slug', 10, 2 );

/**
 * Marks admin screens of program editors, which hide status and date controls.
 *
 * @since 0.5.0
 *
 * @param string $classes Body classes.
 * @return string Body classes.
 */
function najdisvujsen_manager_body_class( $classes ) {
	return najdisvujsen_is_manager() ? $classes . ' nsj-manager' : $classes;
}
add_filter( 'admin_body_class', 'najdisvujsen_manager_body_class' );

/**
 * Sets the notices shown after saving a study program page.
 *
 * @since 0.5.0
 *
 * @param array[] $messages Messages keyed by post type.
 * @return array[] Messages.
 */
function najdisvujsen_obor_messages( $messages ) {
	$post = get_post();
	$view = $post ? sprintf( ' <a href="%1$s">%2$s</a>', esc_url( get_permalink( $post ) ), esc_html__( 'Zobrazit obor', 'najdisvujsen' ) ) : '';

	$messages[ NAJDISVUJSEN_OBOR ] = array(
		1  => __( 'Obor byl uložen.', 'najdisvujsen' ) . $view,
		4  => __( 'Obor byl uložen.', 'najdisvujsen' ),
		5  => __( 'Obor byl obnoven ze starší verze.', 'najdisvujsen' ),
		6  => __( 'Obor byl zveřejněn.', 'najdisvujsen' ) . $view,
		7  => __( 'Obor byl uložen.', 'najdisvujsen' ),
		8  => __( 'Obor byl odeslán ke schválení.', 'najdisvujsen' ),
		9  => __( 'Zveřejnění oboru bylo naplánováno.', 'najdisvujsen' ),
		10 => __( 'Koncept oboru byl uložen.', 'najdisvujsen' ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'najdisvujsen_obor_messages' );

