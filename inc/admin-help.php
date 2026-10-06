<?php
/**
 * Guide for editors in the admin.
 *
 * @package NajdiSvujSen
 * @since 0.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the guide below the study program menu.
 *
 * @since 0.5.0
 */
function najdisvujsen_help_menu() {
	add_submenu_page(
		'edit.php?post_type=' . NAJDISVUJSEN_OBOR,
		__( 'Návod pro úpravy webu', 'najdisvujsen' ),
		__( 'Návod', 'najdisvujsen' ),
		'edit_najdisvujsen_obory',
		'najdisvujsen-navod',
		'najdisvujsen_help_page'
	);
}
add_action( 'admin_menu', 'najdisvujsen_help_menu' );

/**
 * Returns the URL of the guide.
 *
 * @since 0.5.0
 *
 * @return string URL.
 */
function najdisvujsen_help_url() {
	return admin_url( 'edit.php?post_type=' . NAJDISVUJSEN_OBOR . '&page=najdisvujsen-navod' );
}

/**
 * Prints the guide.
 *
 * @since 0.5.0
 */
function najdisvujsen_help_page() {
	$all      = current_user_can( 'edit_others_pages' );
	$sections = array(
		array(
			'title' => __( 'Úprava oboru', 'najdisvujsen' ),
			'steps' => array(
				__( 'V menu vlevo klikněte na Obory a vyberte obor ze seznamu.', 'najdisvujsen' ),
				__( 'Stránka oboru je rozdělená do očíslovaných částí (Základní údaje, Proč studovat, Studijní programy…). Část otevřete kliknutím vlevo. Každá část odpovídá jedné sekci na webu; odkaz „Zobrazit na webu“ ji ukáže.', 'najdisvujsen' ),
				__( 'Položky v seznamech (programy, vyučující, důvody…) rozbalíte kliknutím na jejich název. Šipkami nebo přetažením za ikonu ≡ změníte pořadí, ikonou koše položku smažete, ikonou kopie ji zduplikujete.', 'najdisvujsen' ),
				__( 'Než uložíte, můžete si změny prohlédnout tlačítkem „Zobrazit náhled“ vpravo nahoře. Náhled vidíte jen vy.', 'najdisvujsen' ),
				__( 'Změny uložíte modrým tlačítkem „Aktualizovat“ vpravo. Pokud chybí povinný údaj, formulář na něj upozorní a uložení počká.', 'najdisvujsen' ),
			),
		),
		array(
			'title' => __( 'Studijní programy a katalog na titulní stránce', 'najdisvujsen' ),
			'steps' => array(
				__( 'Každý program je jedna karta: název, stupeň studia (povinné), případně forma, typ a krátký popis.', 'najdisvujsen' ),
				__( 'Programy se zaškrtnutým „Zobrazit v katalogu na titulní stránce“ se samy objeví v katalogu programů pod kategorií oboru (Základní údaje → Kategorie). Stačí zaškrtnout jednu kartu za program.', 'najdisvujsen' ),
				__( 'Profese z Základních údajů běží pod záhlavím oboru a podle nich obor najde průvodce „Kým chceš být?“.', 'najdisvujsen' ),
			),
		),
		array(
			'title' => __( 'Texty a fotografie', 'najdisvujsen' ),
			'steps' => array(
				__( 'Delší texty mají jednoduchý editor: tučné písmo, kurzíva, odkaz, odrážky. U některých polí je i „Podnadpis“ a „Zvýrazněný box“ pro modře podbarvenou radu.', 'najdisvujsen' ),
				__( 'Text zkopírovaný z Wordu nebo e-mailu se vloží bez cizího formátování, vzhled pak určuje web.', 'najdisvujsen' ),
				__( 'Fotografie vybíráte z knihovny médií tlačítkem „Vybrat fotografii“; nahrát novou můžete přímo v okně knihovny. Fotky vyučujících ideálně čtvercové, fotky do záhlaví na šířku alespoň 2000 px.', 'najdisvujsen' ),
				__( 'Větu „A mnoho dalších expertů a expertek!“ pod vyučujícími doplňuje web sám, nepište ji.', 'najdisvujsen' ),
			),
		),
		array(
			'title' => __( 'Když se něco pokazí', 'najdisvujsen' ),
			'steps' => array(
				__( 'Každé uložení se pamatuje. V pravém panelu „Publikovat“ klikněte u položky „Revize“ na „Procházet“, porovnejte verze a starší verzi obnovte tlačítkem „Obnovit tuto revizi“.', 'najdisvujsen' ),
				__( 'Prázdné sekce se na webu nezobrazují, nic tedy nevznikne „rozbité“.', 'najdisvujsen' ),
			),
		),
	);

	if ( $all ) {
		$sections[] = array(
			'title' => __( 'Pro správce webu', 'najdisvujsen' ),
			'steps' => array(
				__( 'Nový obor: Obory → Přidat obor. Vyplňte název, Základní údaje a alespoň jeden program, pak „Publikovat“. Adresa oboru vznikne z názvu (lze upravit pod názvem jako „Trvalý odkaz“).', 'najdisvujsen' ),
				__( 'Titulní stránku (úvod, čísla, termíny dne otevřených dveří, galerie…) upravíte v menu Titulní stránka. Termíny dne otevřených dveří po svém datu samy zmizí.', 'najdisvujsen' ),
				__( 'Odkazy na e-přihlášku a přijímací řízení jsou v menu Odkazy na přihlášku a přijímačky – každý rok je zkontrolujte.', 'najdisvujsen' ),
				__( 'Kolegům z kateder vytvořte účet s rolí „Správce oboru“ (Uživatelé → Vytvořit uživatele) a dole na stránce uživatele zaškrtněte obory, které smí upravovat. Správce oboru nevidí ostatní obory ani titulní stránku a nemůže obor smazat, skrýt ani změnit jeho adresu.', 'najdisvujsen' ),
			),
		);
	}
	?>
	<div class="wrap nsj-help">
		<h1><?php esc_html_e( 'Návod pro úpravy webu', 'najdisvujsen' ); ?></h1>
		<?php foreach ( $sections as $section ) : ?>
			<div class="card" style="max-width: 860px;">
				<h2><?php echo esc_html( $section['title'] ); ?></h2>
				<ol>
					<?php foreach ( $section['steps'] as $step ) : ?>
						<li><?php echo esc_html( $step ); ?></li>
					<?php endforeach; ?>
				</ol>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Replaces the dashboard widgets with buttons to the editable parts of the site.
 *
 * @since 0.5.0
 */
function najdisvujsen_dashboard_widget() {
	global $wp_meta_boxes;

	$wp_meta_boxes['dashboard'] = array(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Only the theme's dashboard is shown.
	remove_action( 'welcome_panel', 'wp_welcome_panel' );

	wp_add_dashboard_widget( 'najdisvujsen-links', __( 'Úpravy webu Najdi svůj sen', 'najdisvujsen' ), 'najdisvujsen_dashboard_links' );
}
add_action( 'wp_dashboard_setup', 'najdisvujsen_dashboard_widget', 999 );

/**
 * Returns the dashboard buttons the current user can use.
 *
 * @since 0.6.0
 *
 * @return array[] Buttons with keys url, label, text and icon.
 */
function najdisvujsen_dashboard_buttons() {
	$front   = (int) get_option( 'page_on_front' );
	$buttons = array(
		array(
			'url'   => home_url( '/' ),
			'label' => __( 'Zobrazit web', 'najdisvujsen' ),
			'text'  => __( 'otevře web v nové záložce', 'najdisvujsen' ),
			'icon'  => 'external',
			'new'   => true,
		),
	);

	if ( $front && current_user_can( 'edit_post', $front ) ) {
		$buttons[] = array(
			'url'   => (string) get_edit_post_link( $front, 'raw' ),
			'label' => __( 'Úvodní stránka', 'najdisvujsen' ),
			'text'  => __( 'menu, úvod, čísla, katalog, den otevřených dveří, galerie', 'najdisvujsen' ),
			'icon'  => 'admin-home',
		);
	}

	if ( current_user_can( 'edit_najdisvujsen_obory' ) ) {
		$buttons[] = array(
			'url'   => admin_url( 'edit.php?post_type=' . NAJDISVUJSEN_OBOR ),
			'label' => __( 'Obory', 'najdisvujsen' ),
			'text'  => __( 'stránky jednotlivých oborů', 'najdisvujsen' ),
			'icon'  => 'welcome-learn-more',
		);
	}

	if ( current_user_can( 'edit_others_pages' ) ) {
		$buttons[] = array(
			'url'   => menu_page_url( 'najdisvujsen-settings', false ),
			'label' => __( 'Odkazy na přihlášku a přijímačky', 'najdisvujsen' ),
			'text'  => __( 'kam vedou tlačítka „Podat přihlášku“ a „Přijímací řízení“', 'najdisvujsen' ),
			'icon'  => 'admin-links',
		);
		$buttons[] = array(
			'url'   => menu_page_url( 'najdisvujsen-footer', false ),
			'label' => __( 'Patička a sociální sítě', 'najdisvujsen' ),
			'text'  => __( 'adresa, odkazy na sítě, aplikace, odkazy dole', 'najdisvujsen' ),
			'icon'  => 'share',
		);
	}

	$buttons[] = array(
		'url'   => najdisvujsen_help_url(),
		'label' => __( 'Návod', 'najdisvujsen' ),
		'text'  => __( 'jak web upravovat krok za krokem', 'najdisvujsen' ),
		'icon'  => 'editor-help',
	);

	return $buttons;
}

/**
 * Prints the dashboard buttons.
 *
 * @since 0.5.0
 */
function najdisvujsen_dashboard_links() {
	echo '<ul class="nsj-dash">';

	foreach ( najdisvujsen_dashboard_buttons() as $button ) {
		printf(
			'<li><a class="nsj-dash__btn" href="%1$s"%2$s><span class="nsj-dash__icon dashicons dashicons-%3$s" aria-hidden="true"></span><span class="nsj-dash__label">%4$s</span><span class="nsj-dash__text">%5$s</span></a></li>',
			esc_url( $button['url'] ),
			empty( $button['new'] ) ? '' : ' target="_blank" rel="noopener"',
			esc_attr( $button['icon'] ),
			esc_html( $button['label'] ),
			esc_html( $button['text'] )
		);
	}

	echo '</ul>';
}

/**
 * Styles the dashboard buttons.
 *
 * @since 0.6.0
 *
 * @param string $hook Current admin screen.
 */
function najdisvujsen_dashboard_assets( $hook ) {
	if ( 'index.php' === $hook ) {
		wp_enqueue_style( 'najdisvujsen-dashboard', NAJDISVUJSEN_URI . '/assets/admin/dashboard.css', array(), wp_get_theme()->get( 'Version' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'najdisvujsen_dashboard_assets' );

/**
 * Returns careers and the study programs that lead to them.
 *
 * @since 0.6.0
 *
 * @return array[] Rows with keys career, obor, url, bc, mgr, bubbles and picker.
 */
function najdisvujsen_career_rows() {
	$front   = (int) get_option( 'page_on_front' );
	$data    = $front ? najdisvujsen_get_data( $front ) : array();
	$bubbles = array_map( 'mb_strtolower', array_merge( $data['uvod']['bubbles']['top'] ?? array(), $data['uvod']['bubbles']['bottom'] ?? array() ) );
	$picker  = array_map( 'mb_strtolower', $data['katalog']['careers'] ?? array() );
	$rows    = array();

	foreach ( najdisvujsen_get_programs() as $program ) {
		$programs = array(
			'bc'  => array(),
			'mgr' => array(),
		);

		foreach ( najdisvujsen_get_data( $program->ID )['programy']['programs'] as $card ) {
			if ( isset( $programs[ $card['level'] ] ) && ! in_array( $card['name'], $programs[ $card['level'] ], true ) ) {
				$programs[ $card['level'] ][] = $card['name'];
			}
		}

		foreach ( najdisvujsen_program_careers( $program->ID ) as $career ) {
			$rows[] = array(
				'career'  => $career,
				'obor'    => get_the_title( $program ),
				'url'     => (string) get_permalink( $program ),
				'bc'      => implode( ', ', $programs['bc'] ),
				'mgr'     => implode( ', ', $programs['mgr'] ),
				'bubbles' => in_array( mb_strtolower( $career ), $bubbles, true ),
				'picker'  => in_array( mb_strtolower( $career ), $picker, true ),
			);
		}
	}

	usort(
		$rows,
		static function ( $a, $b ) {
			return strcmp( najdisvujsen_czech_sort_key( $a['career'] . ' ' . $a['obor'] ), najdisvujsen_czech_sort_key( $b['career'] . ' ' . $b['obor'] ) );
		}
	);

	return $rows;
}

/**
 * Adds the career export below the study program menu.
 *
 * @since 0.6.0
 */
function najdisvujsen_export_menu() {
	add_submenu_page(
		'edit.php?post_type=' . NAJDISVUJSEN_OBOR,
		__( 'Profese a obory', 'najdisvujsen' ),
		__( 'Export profesí', 'najdisvujsen' ),
		'edit_najdisvujsen_obory',
		'najdisvujsen-export',
		'najdisvujsen_export_page'
	);
}
add_action( 'admin_menu', 'najdisvujsen_export_menu' );

/**
 * Prints the career export screen.
 *
 * @since 0.6.0
 */
function najdisvujsen_export_page() {
	$rows = najdisvujsen_career_rows();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Profese a obory', 'najdisvujsen' ); ?></h1>
		<p><?php esc_html_e( 'Které profese vedou na které obory. Profese se zadávají u oborů (Obory → obor → Základní údaje → Profese); podle nich obory najde průvodce „Kým chceš být?“ a bubliny na titulní stránce.', 'najdisvujsen' ); ?></p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=najdisvujsen_export_careers' ), 'najdisvujsen_export_careers' ) ); ?>"><?php esc_html_e( 'Stáhnout tabulku pro Excel', 'najdisvujsen' ); ?></a>
		</p>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Profese', 'najdisvujsen' ); ?></th>
					<th><?php esc_html_e( 'Obor', 'najdisvujsen' ); ?></th>
					<th><?php esc_html_e( 'Bakalářské programy', 'najdisvujsen' ); ?></th>
					<th><?php esc_html_e( 'Magisterské programy', 'najdisvujsen' ); ?></th>
					<th><?php esc_html_e( 'V bublinách', 'najdisvujsen' ); ?></th>
					<th><?php esc_html_e( 'V losování', 'najdisvujsen' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<td><strong><?php echo esc_html( $row['career'] ); ?></strong></td>
						<td><a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['obor'] ); ?></a></td>
						<td><?php echo esc_html( $row['bc'] ); ?></td>
						<td><?php echo esc_html( $row['mgr'] ); ?></td>
						<td><?php echo $row['bubbles'] ? esc_html__( 'ano', 'najdisvujsen' ) : '—'; ?></td>
						<td><?php echo $row['picker'] ? esc_html__( 'ano', 'najdisvujsen' ) : '—'; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Sends the careers and study programs as a CSV file for Excel.
 *
 * @since 0.6.0
 */
function najdisvujsen_export_careers() {
	check_admin_referer( 'najdisvujsen_export_careers' );

	if ( ! current_user_can( 'edit_najdisvujsen_obory' ) ) {
		wp_die( esc_html__( 'Na export nemáte oprávnění.', 'najdisvujsen' ) );
	}

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="profese-a-obory-' . wp_date( 'Y-m-d' ) . '.csv"' );

	// Excel opens UTF-8 with a byte order mark; Czech Excel expects semicolons.
	$lines = array( "\xEF\xBB\xBF" . implode( ';', array( 'Profese', 'Obor', 'Bakalářské programy', 'Magisterské programy', 'V bublinách na titulní stránce', 'V losování „Kým chceš být?“', 'Odkaz' ) ) );

	foreach ( najdisvujsen_career_rows() as $row ) {
		$cells   = array( $row['career'], $row['obor'], $row['bc'], $row['mgr'], $row['bubbles'] ? 'ano' : 'ne', $row['picker'] ? 'ano' : 'ne', $row['url'] );
		$lines[] = implode(
			';',
			array_map(
				static function ( $cell ) {
					return '"' . str_replace( '"', '""', $cell ) . '"';
				},
				$cells
			)
		);
	}

	echo implode( "\r\n", $lines ) . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSV download, not HTML.
	exit;
}
add_action( 'admin_post_najdisvujsen_export_careers', 'najdisvujsen_export_careers' );
