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
				__( 'Větu „A mnoho dalších expertů a expertek“ pod vyučujícími doplňuje web sám, nepište ji.', 'najdisvujsen' ),
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
				__( 'Odkazy na e-přihlášku a přijímací řízení jsou v menu Nastavení webu – každý rok je zkontrolujte.', 'najdisvujsen' ),
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
 * Adds quick links for editors to the dashboard.
 *
 * @since 0.5.0
 */
function najdisvujsen_dashboard_widget() {
	if ( ! current_user_can( 'edit_najdisvujsen_obory' ) ) {
		return;
	}

	wp_add_dashboard_widget( 'najdisvujsen-links', __( 'Úpravy webu Najdi svůj sen', 'najdisvujsen' ), 'najdisvujsen_dashboard_links', null, null, 'normal', 'high' );
}
add_action( 'wp_dashboard_setup', 'najdisvujsen_dashboard_widget' );

/**
 * Prints the dashboard quick links.
 *
 * @since 0.5.0
 */
function najdisvujsen_dashboard_links() {
	$front = (int) get_option( 'page_on_front' );
	$links = array(
		array( admin_url( 'edit.php?post_type=' . NAJDISVUJSEN_OBOR ), __( 'Obory', 'najdisvujsen' ), __( 'stránky jednotlivých oborů', 'najdisvujsen' ) ),
		array( $front && current_user_can( 'edit_post', $front ) ? get_edit_post_link( $front ) : '', __( 'Titulní stránka', 'najdisvujsen' ), __( 'úvod, čísla, den otevřených dveří, galerie', 'najdisvujsen' ) ),
		array( current_user_can( 'edit_others_pages' ) ? menu_page_url( 'najdisvujsen-settings', false ) : '', __( 'Nastavení webu', 'najdisvujsen' ), __( 'odkazy na e-přihlášku a přijímací řízení', 'najdisvujsen' ) ),
		array( najdisvujsen_help_url(), __( 'Návod', 'najdisvujsen' ), __( 'jak web upravovat krok za krokem', 'najdisvujsen' ) ),
	);

	echo '<ul>';

	foreach ( $links as $link ) {
		if ( $link[0] ) {
			printf( '<li><a href="%1$s"><strong>%2$s</strong></a> – %3$s</li>', esc_url( $link[0] ), esc_html( $link[1] ), esc_html( $link[2] ) );
		}
	}

	echo '</ul>';
}
