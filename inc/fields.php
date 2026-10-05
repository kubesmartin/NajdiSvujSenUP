<?php
/**
 * Field definitions of structured content: study program pages and the front page.
 *
 * One definition drives the edit form, sanitization and default values.
 *
 * @package NajdiSvujSen
 * @since 0.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the catalogue categories of study programs.
 *
 * @since 0.5.0
 *
 * @return string[] Labels keyed by category.
 */
function najdisvujsen_program_categories() {
	return array(
		'filologicke' => __( 'Filologické', 'najdisvujsen' ),
		'spolecenske' => __( 'Společenskovědní a humanitní', 'najdisvujsen' ),
		'umenovedne'  => __( 'Uměnovědné', 'najdisvujsen' ),
	);
}

/**
 * Returns the study level options of a program card.
 *
 * @since 0.5.0
 *
 * @return string[] Labels keyed by level.
 */
function najdisvujsen_program_level_options() {
	return array(
		'bc'  => __( 'Bakalářské', 'najdisvujsen' ),
		'mgr' => __( 'Navazující magisterské', 'najdisvujsen' ),
		'phd' => __( 'Doktorské', 'najdisvujsen' ),
	);
}

/**
 * Returns the study form options of a program card.
 *
 * @since 0.5.0
 *
 * @return string[] Labels keyed by form.
 */
function najdisvujsen_program_form_options() {
	return array(
		'prezencni'   => __( 'Prezenční', 'najdisvujsen' ),
		'kombinovana' => __( 'Kombinovaná', 'najdisvujsen' ),
	);
}

/**
 * Returns the study type options of a program card.
 *
 * @since 0.5.0
 *
 * @return string[] Labels keyed by type.
 */
function najdisvujsen_program_type_options() {
	return array(
		'samostatny' => __( 'Samostatný program', 'najdisvujsen' ),
		'maior'      => __( 'Maior', 'najdisvujsen' ),
		'minor'      => __( 'Minor', 'najdisvujsen' ),
		'kombinace'  => __( 'V kombinaci', 'najdisvujsen' ),
	);
}

/**
 * Returns field definitions shared by sections: heading and photos.
 *
 * @since 0.5.0
 *
 * @param string $placeholder Default heading shown when the field is empty.
 * @return array[] Field definitions.
 */
function najdisvujsen_section_heading_field( $placeholder ) {
	return array(
		'type'        => 'text',
		'label'       => __( 'Nadpis sekce', 'najdisvujsen' ),
		'placeholder' => $placeholder,
		'help'        => __( 'Když pole necháte prázdné, použije se nadpis uvedený šedě.', 'najdisvujsen' ),
		'default'     => '',
	);
}

/**
 * Returns the definition of a photo gallery field of a section.
 *
 * @since 0.5.0
 *
 * @param string $help Help text.
 * @param int    $max  Maximum number of photos.
 * @return array Field definition.
 */
function najdisvujsen_photos_field( $help = '', $max = 4 ) {
	return array(
		'type'  => 'gallery',
		'label' => __( 'Fotografie sekce', 'najdisvujsen' ),
		'help'  => $help ? $help : __( 'Nepovinné. Zobrazí se vedle textu nebo pod ním. Pořadí změníte přetažením.', 'najdisvujsen' ),
		'max'   => $max,
	);
}

/**
 * Returns the edit form definition of a study program page.
 *
 * Tabs group fields; a tab with a key stores its fields under that key.
 *
 * @since 0.5.0
 *
 * @return array[] Tabs.
 */
function najdisvujsen_obor_schema() {
	$rich_help = __( 'Odstavce, odrážky, tučné písmo, odkazy. Tlačítkem „Zvýrazněný box“ vložíte modře podbarvenou radu.', 'najdisvujsen' );

	return array(
		'zakladni'  => array(
			'label'  => __( 'Základní údaje', 'najdisvujsen' ),
			'icon'   => 'id',
			'intro'  => __( 'Záhlaví stránky: velký název v bublině, perex, fotografie, katedra a odkazy na sociální sítě.', 'najdisvujsen' ),
			'fields' => array(
				'hero_title'     => array(
					'type'  => 'text',
					'label' => __( 'Krátký název v záhlaví', 'najdisvujsen' ),
					'help'  => __( 'Zobrazí se ve velké bublině nahoře a v nabídce „Mohlo by tě zajímat“. Prázdné pole = použije se název oboru.', 'najdisvujsen' ),
					'max'   => 60,
				),
				'excerpt'        => array(
					'type'     => 'excerpt',
					'label'    => __( 'Perex', 'najdisvujsen' ),
					'help'     => __( 'Jedna až dvě věty pod názvem oboru. Ukazuje se také ve výsledcích vyhledávání.', 'najdisvujsen' ),
					'max'      => 260,
					'required' => true,
				),
				'thumbnail'      => array(
					'type'  => 'thumbnail',
					'label' => __( 'Fotografie v záhlaví', 'najdisvujsen' ),
					'help'  => __( 'Velká fotografie na pozadí záhlaví. Ideálně na šířku, alespoň 2000 px.', 'najdisvujsen' ),
				),
				'category'       => array(
					'type'     => 'select',
					'label'    => __( 'Kategorie v katalogu programů', 'najdisvujsen' ),
					'help'     => __( 'Pod touto kategorií se programy oboru ukážou v katalogu na titulní stránce.', 'najdisvujsen' ),
					'options'  => najdisvujsen_program_categories(),
					'required' => true,
				),
				'department'     => array(
					'type'        => 'text',
					'label'       => __( 'Katedra', 'najdisvujsen' ),
					'placeholder' => __( 'např. Katedra historie FF UP', 'najdisvujsen' ),
					'required'    => true,
				),
				'department_url' => array(
					'type'        => 'url',
					'label'       => __( 'Web katedry', 'najdisvujsen' ),
					'placeholder' => 'https://',
				),
				'social'         => array(
					'type'   => 'group',
					'label'  => __( 'Sociální sítě', 'najdisvujsen' ),
					'help'   => __( 'Celé odkazy na profily oboru nebo katedry. Prázdné se nezobrazí.', 'najdisvujsen' ),
					'fields' => array(
						'facebook'  => array(
							'type'        => 'url',
							'label'       => 'Facebook',
							'placeholder' => 'https://www.facebook.com/…',
						),
						'instagram' => array(
							'type'        => 'url',
							'label'       => 'Instagram',
							'placeholder' => 'https://www.instagram.com/…',
						),
						'youtube'   => array(
							'type'        => 'url',
							'label'       => 'YouTube',
							'placeholder' => 'https://www.youtube.com/…',
						),
						'tiktok'    => array(
							'type'        => 'url',
							'label'       => 'TikTok',
							'placeholder' => 'https://www.tiktok.com/…',
						),
					),
				),
				'careers'        => array(
					'type'        => 'list',
					'label'       => __( 'Profese', 'najdisvujsen' ),
					'help'        => __( 'Běží v pásu pod záhlavím a podle nich obor najde průvodce „Kým chceš být?“ na titulní stránce. Jedna profese na řádek, např. Historik.', 'najdisvujsen' ),
					'add'         => __( 'Přidat profesi', 'najdisvujsen' ),
					'placeholder' => __( 'např. Novinářka', 'najdisvujsen' ),
				),
				'emoji'          => array(
					'type'  => 'text',
					'label' => __( 'Emoji', 'najdisvujsen' ),
					'help'  => __( 'Jeden symbol u úvodní věty sekce „Proč studovat“, např. 📜. Prázdné pole = 🎓.', 'najdisvujsen' ),
					'max'   => 8,
					'size'  => 'small',
				),
			),
		),
		'proc'      => array(
			'label'  => __( 'Proč studovat', 'najdisvujsen' ),
			'icon'   => 'lightbulb',
			'intro'  => __( 'Hlavní důvody, proč obor studovat. Každý důvod je jedna očíslovaná karta.', 'najdisvujsen' ),
			'fields' => array(
				'title'  => najdisvujsen_section_heading_field( __( 'Proč studovat u nás?', 'najdisvujsen' ) ),
				'lead'   => array(
					'type'  => 'rich',
					'label' => __( 'Úvodní karta', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné. Zobrazí se ve zvýrazněné modré kartě s emoji. Stačí jedna až dvě věty.', 'najdisvujsen' ),
					'lite'  => true,
				),
				'items'  => array(
					'type'   => 'repeater',
					'label'  => __( 'Důvody', 'najdisvujsen' ),
					'add'    => __( 'Přidat důvod', 'najdisvujsen' ),
					'item'   => __( 'Důvod', 'najdisvujsen' ),
					'title'  => 'title',
					'fields' => array(
						'title' => array(
							'type'        => 'text',
							'label'       => __( 'Krátký nadpis', 'najdisvujsen' ),
							'placeholder' => __( 'např. Porozumíš minulosti i přítomnosti', 'najdisvujsen' ),
						),
						'text'  => array(
							'type'  => 'rich',
							'label' => __( 'Text', 'najdisvujsen' ),
							'lite'  => true,
						),
					),
				),
				'text'   => array(
					'type'  => 'rich',
					'label' => __( 'Doplňující text pod kartami', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné.', 'najdisvujsen' ) . ' ' . $rich_help,
				),
				'photos' => najdisvujsen_photos_field(),
			),
		),
		'programy'  => array(
			'label'  => __( 'Studijní programy', 'najdisvujsen' ),
			'icon'   => 'welcome-learn-more',
			'intro'  => __( 'Každý program, který lze u oboru studovat, je jedna karta. Karty se na webu rozdělí do záložek Bakalářské / Magisterské / Doktorské a zaškrtnuté programy se samy zobrazí v katalogu na titulní stránce.', 'najdisvujsen' ),
			'fields' => array(
				'title'    => najdisvujsen_section_heading_field( __( 'Co u nás můžeš studovat?', 'najdisvujsen' ) ),
				'programs' => array(
					'type'   => 'repeater',
					'label'  => __( 'Programy', 'najdisvujsen' ),
					'add'    => __( 'Přidat program', 'najdisvujsen' ),
					'item'   => __( 'Program', 'najdisvujsen' ),
					'title'  => 'name',
					'badge'  => 'level',
					'fields' => array(
						'name'           => array(
							'type'        => 'text',
							'label'       => __( 'Název programu', 'najdisvujsen' ),
							'placeholder' => __( 'např. Historické vědy', 'najdisvujsen' ),
							'required'    => true,
						),
						'level'          => array(
							'type'     => 'radio',
							'label'    => __( 'Stupeň studia', 'najdisvujsen' ),
							'options'  => najdisvujsen_program_level_options(),
							'required' => true,
						),
						'duration'       => array(
							'type'        => 'text',
							'label'       => __( 'Délka studia', 'najdisvujsen' ),
							'placeholder' => __( 'např. 3 roky', 'najdisvujsen' ),
							'help'        => __( 'Nepovinné.', 'najdisvujsen' ),
							'size'        => 'small',
						),
						'forms'          => array(
							'type'    => 'checkboxes',
							'label'   => __( 'Forma studia', 'najdisvujsen' ),
							'options' => najdisvujsen_program_form_options(),
							'help'    => __( 'Nepovinné. Zaškrtněte jen to, co platí.', 'najdisvujsen' ),
						),
						'types'          => array(
							'type'    => 'checkboxes',
							'label'   => __( 'Typ studia', 'najdisvujsen' ),
							'options' => najdisvujsen_program_type_options(),
							'help'    => __( 'Nepovinné. Maior = hlavní program dvouoborového studia, minor = vedlejší.', 'najdisvujsen' ),
						),
						'text'           => array(
							'type'  => 'rich',
							'label' => __( 'Krátký popis', 'najdisvujsen' ),
							'help'  => __( 'Nepovinné. Co je na programu zvláštního, pro koho je.', 'najdisvujsen' ),
							'lite'  => true,
						),
						'url'            => array(
							'type'        => 'url',
							'label'       => __( 'Odkaz na detail programu', 'najdisvujsen' ),
							'placeholder' => 'https://studium.upol.cz/…',
							'help'        => __( 'Nepovinné. Na kartě se zobrazí jako „Detail programu“.', 'najdisvujsen' ),
						),
						'catalogue'      => array(
							'type'    => 'toggle',
							'label'   => __( 'Zobrazit v katalogu na titulní stránce', 'najdisvujsen' ),
							'help'    => __( 'Stačí u jedné karty programu, i když jich má program víc (např. zvlášť prezenční a kombinovaná forma). Doktorské programy se v katalogu nezobrazují.', 'najdisvujsen' ),
							'default' => true,
						),
						'catalogue_name' => array(
							'type'  => 'text',
							'label' => __( 'Jiný název v katalogu', 'najdisvujsen' ),
							'help'  => __( 'Nepovinné. Jen pokud má být v katalogu jiný název než na kartě.', 'najdisvujsen' ),
						),
					),
				),
				'text'     => array(
					'type'  => 'rich',
					'label' => __( 'Text pod kartami', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné. Např. s čím lze obor kombinovat.', 'najdisvujsen' ) . ' ' . $rich_help,
				),
				'tip'      => array(
					'type'  => 'rich',
					'label' => __( 'Zvýrazněná rada', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné. Zobrazí se v modrém rámečku, např. „Naše rada: …“.', 'najdisvujsen' ),
					'lite'  => true,
				),
				'photos'   => najdisvujsen_photos_field(),
			),
		),
		'olomouc'   => array(
			'label'  => __( 'Studium v Olomouci', 'najdisvujsen' ),
			'icon'   => 'location',
			'intro'  => __( 'Proč studovat právě u vás. Odrážky v textu se na webu zobrazí s modrou fajfkou.', 'najdisvujsen' ),
			'fields' => array(
				'title'      => najdisvujsen_section_heading_field( __( 'Proč studovat u nás v Olomouci?', 'najdisvujsen' ) ),
				'highlights' => array(
					'type'   => 'repeater',
					'label'  => __( 'Tři hlavní body', 'najdisvujsen' ),
					'help'   => __( 'Nepovinné. Zobrazí se jako tři výrazné dlaždice nad textem.', 'najdisvujsen' ),
					'add'    => __( 'Přidat bod', 'najdisvujsen' ),
					'item'   => __( 'Bod', 'najdisvujsen' ),
					'max'    => 3,
					'fields' => array(
						'text' => array(
							'type'     => 'rich',
							'label'    => __( 'Text', 'najdisvujsen' ),
							'lite'     => true,
							'required' => true,
						),
					),
				),
				'text'       => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'photos'     => najdisvujsen_photos_field(),
			),
		),
		'uplatneni' => array(
			'label'  => __( 'Uplatnění', 'najdisvujsen' ),
			'icon'   => 'businessperson',
			'intro'  => __( 'Kde absolventi najdou práci. Oblasti uplatnění se na webu zobrazí jako přepínací bubliny.', 'najdisvujsen' ),
			'fields' => array(
				'title'  => najdisvujsen_section_heading_field( __( 'Kde se uplatníš?', 'najdisvujsen' ) ),
				'text'   => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'areas'  => array(
					'type'   => 'repeater',
					'label'  => __( 'Oblasti uplatnění', 'najdisvujsen' ),
					'help'   => __( 'Nepovinné. Zobrazí se pod textem, nejlépe tři a více.', 'najdisvujsen' ),
					'add'    => __( 'Přidat oblast', 'najdisvujsen' ),
					'item'   => __( 'Oblast', 'najdisvujsen' ),
					'title'  => 'title',
					'fields' => array(
						'title' => array(
							'type'        => 'text',
							'label'       => __( 'Oblast', 'najdisvujsen' ),
							'placeholder' => __( 'např. Média', 'najdisvujsen' ),
							'required'    => true,
						),
						'text'  => array(
							'type'  => 'rich',
							'label' => __( 'Popis', 'najdisvujsen' ),
							'lite'  => true,
						),
					),
				),
				'photos' => najdisvujsen_photos_field(),
			),
		),
		'zahranici' => array(
			'label'  => __( 'Zahraničí', 'najdisvujsen' ),
			'icon'   => 'admin-site-alt3',
			'intro'  => __( 'Možnosti studia a stáží v zahraničí.', 'najdisvujsen' ),
			'fields' => array(
				'title'  => najdisvujsen_section_heading_field( __( 'Chceš studovat v zahraničí?', 'najdisvujsen' ) ),
				'text'   => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'photos' => najdisvujsen_photos_field(),
			),
		),
		'lide'      => array(
			'label'  => __( 'Vyučující', 'najdisvujsen' ),
			'icon'   => 'groups',
			'intro'  => __( 'Představení vyučujících. Na webu jsou nejvýše tři vedle sebe a pod nimi je vždy věta „A mnoho dalších expertů a expertek“ – nepište ji sem.', 'najdisvujsen' ),
			'fields' => array(
				'title'  => najdisvujsen_section_heading_field( __( 'Kdo tě bude učit?', 'najdisvujsen' ) ),
				'text'   => array(
					'type'  => 'rich',
					'label' => __( 'Úvodní text', 'najdisvujsen' ),
					'help'  => __( 'Nepovinné. Zobrazí se nad vyučujícími.', 'najdisvujsen' ),
					'lite'  => true,
				),
				'people' => array(
					'type'   => 'repeater',
					'label'  => __( 'Vyučující', 'najdisvujsen' ),
					'add'    => __( 'Přidat vyučující/ho', 'najdisvujsen' ),
					'item'   => __( 'Vyučující', 'najdisvujsen' ),
					'title'  => 'name',
					'thumb'  => 'photo',
					'fields' => array(
						'photo' => array(
							'type'  => 'image',
							'label' => __( 'Fotografie', 'najdisvujsen' ),
							'help'  => __( 'Ideálně čtvercová, obličej uprostřed. Bez fotky se zobrazí iniciály.', 'najdisvujsen' ),
						),
						'name'  => array(
							'type'     => 'text',
							'label'    => __( 'Jméno', 'najdisvujsen' ),
							'help'     => __( 'Bez titulů, např. Jana Nováková.', 'najdisvujsen' ),
							'required' => true,
						),
						'text'  => array(
							'type'  => 'rich',
							'label' => __( 'Krátké představení', 'najdisvujsen' ),
							'help'  => __( 'Pár vět v první osobě: čím se zabývá a co se u ní/něj studenti naučí.', 'najdisvujsen' ),
							'lite'  => true,
						),
					),
				),
				'photos' => najdisvujsen_photos_field( __( 'Nepovinné. Fotky pod vyučujícími, např. z výuky.', 'najdisvujsen' ) ),
			),
		),
		'extra'     => array(
			'label'  => __( 'Další sekce', 'najdisvujsen' ),
			'icon'   => 'plus-alt',
			'intro'  => __( 'Nepovinné sekce navíc, např. „Co tě ve studiu čeká?“, „Přijímačky“ nebo „Zajímavosti a kontakty“. Vyberte, za kterou sekci se mají zařadit.', 'najdisvujsen' ),
			'fields' => array(
				'sections' => array(
					'type'   => 'repeater',
					'label'  => __( 'Sekce', 'najdisvujsen' ),
					'add'    => __( 'Přidat sekci', 'najdisvujsen' ),
					'item'   => __( 'Sekce', 'najdisvujsen' ),
					'title'  => 'title',
					'fields' => array(
						'title'    => array(
							'type'     => 'text',
							'label'    => __( 'Nadpis sekce', 'najdisvujsen' ),
							'required' => true,
						),
						'position' => array(
							'type'    => 'select',
							'label'   => __( 'Umístění', 'najdisvujsen' ),
							'options' => array(
								'proc'      => __( 'za sekcí Proč studovat', 'najdisvujsen' ),
								'programy'  => __( 'za sekcí Studijní programy', 'najdisvujsen' ),
								'olomouc'   => __( 'za sekcí Studium v Olomouci', 'najdisvujsen' ),
								'uplatneni' => __( 'za sekcí Uplatnění', 'najdisvujsen' ),
								'zahranici' => __( 'za sekcí Zahraničí', 'najdisvujsen' ),
								'lide'      => __( 'za sekcí Vyučující', 'najdisvujsen' ),
							),
							'default' => 'programy',
						),
						'nav'      => array(
							'type'        => 'text',
							'label'       => __( 'Štítek v navigaci stránky', 'najdisvujsen' ),
							'placeholder' => __( 'např. Přijímačky', 'najdisvujsen' ),
							'help'        => __( 'Nepovinné. Krátké slovo, pod kterým bude sekce v horní navigaci oboru. Prázdné = sekce v navigaci nebude.', 'najdisvujsen' ),
							'max'         => 20,
						),
						'text'     => array(
							'type'  => 'rich',
							'label' => __( 'Text', 'najdisvujsen' ),
							'help'  => $rich_help,
						),
						'photos'   => najdisvujsen_photos_field(),
					),
				),
			),
		),
	);
}

/**
 * Returns the edit form definition of the front page.
 *
 * @since 0.5.0
 *
 * @return array[] Tabs.
 */
function najdisvujsen_front_schema() {
	$rich_help = __( 'Odstavce, odrážky, tučné písmo, odkazy.', 'najdisvujsen' );
	$bubble    = __( 'Profese nebo emoji (jeden symbol). Bubliny jedou v pásu pod úvodem stránky.', 'najdisvujsen' );

	return array(
		'uvod'      => array(
			'label'  => __( 'Úvod', 'najdisvujsen' ),
			'icon'   => 'cover-image',
			'intro'  => __( 'Horní část stránky s heslem „Najdi svůj sen“.', 'najdisvujsen' ),
			'fields' => array(
				'title'   => array(
					'type'        => 'text',
					'label'       => __( 'Nadpis', 'najdisvujsen' ),
					'placeholder' => __( 'Studuj na FF UP', 'najdisvujsen' ),
				),
				'lead'    => array(
					'type'  => 'textarea',
					'label' => __( 'Podnadpis', 'najdisvujsen' ),
					'rows'  => 2,
					'max'   => 200,
				),
				'social'  => array(
					'type'   => 'group',
					'label'  => __( 'Sociální sítě fakulty', 'najdisvujsen' ),
					'fields' => array(
						'facebook'  => array(
							'type'  => 'url',
							'label' => 'Facebook',
						),
						'instagram' => array(
							'type'  => 'url',
							'label' => 'Instagram',
						),
						'youtube'   => array(
							'type'  => 'url',
							'label' => 'YouTube',
						),
						'tiktok'    => array(
							'type'  => 'url',
							'label' => 'TikTok',
						),
					),
				),
				'bubbles' => array(
					'type'   => 'group',
					'label'  => __( 'Pásy s bublinami', 'najdisvujsen' ),
					'fields' => array(
						'top'    => array(
							'type'  => 'list',
							'label' => __( 'Horní pás', 'najdisvujsen' ),
							'help'  => $bubble,
							'add'   => __( 'Přidat bublinu', 'najdisvujsen' ),
						),
						'bottom' => array(
							'type'  => 'list',
							'label' => __( 'Dolní pás', 'najdisvujsen' ),
							'help'  => $bubble,
							'add'   => __( 'Přidat bublinu', 'najdisvujsen' ),
						),
					),
				),
			),
		),
		'proc'      => array(
			'label'  => __( 'Proč FF UP', 'najdisvujsen' ),
			'icon'   => 'awards',
			'intro'  => __( 'Text o fakultě a čtyři dlaždice s čísly.', 'najdisvujsen' ),
			'fields' => array(
				'title' => array(
					'type'        => 'text',
					'label'       => __( 'Nadpis', 'najdisvujsen' ),
					'placeholder' => __( 'Druhá nejstarší univerzita v Česku', 'najdisvujsen' ),
				),
				'text'  => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'stats' => array(
					'type'   => 'repeater',
					'label'  => __( 'Dlaždice s čísly', 'najdisvujsen' ),
					'help'   => __( 'Ideálně čtyři.', 'najdisvujsen' ),
					'add'    => __( 'Přidat dlaždici', 'najdisvujsen' ),
					'item'   => __( 'Dlaždice', 'najdisvujsen' ),
					'title'  => 'number',
					'max'    => 4,
					'fields' => array(
						'number' => array(
							'type'     => 'text',
							'label'    => __( 'Číslo', 'najdisvujsen' ),
							'help'     => __( 'Např. 1573 nebo 600+.', 'najdisvujsen' ),
							'size'     => 'small',
							'required' => true,
						),
						'label'  => array(
							'type'     => 'text',
							'label'    => __( 'Popisek', 'najdisvujsen' ),
							'required' => true,
						),
					),
				),
				'photo' => array(
					'type'  => 'image',
					'label' => __( 'Fotografie', 'najdisvujsen' ),
					'help'  => __( 'Široká dlaždice vedle čísel, ideálně na šířku.', 'najdisvujsen' ),
				),
			),
		),
		'video'     => array(
			'label'  => __( 'Video', 'najdisvujsen' ),
			'icon'   => 'video-alt3',
			'intro'  => __( 'Video přes celou šířku stránky pod úvodními dlaždicemi.', 'najdisvujsen' ),
			'fields' => array(
				'youtube' => array(
					'type'        => 'text',
					'label'       => __( 'Odkaz na video na YouTube', 'najdisvujsen' ),
					'placeholder' => 'https://www.youtube.com/watch?v=…',
					'help'        => __( 'Vložte odkaz na video. Prázdné pole video skryje.', 'najdisvujsen' ),
				),
			),
		),
		'mesto'     => array(
			'label'  => __( 'Univerzitní město', 'najdisvujsen' ),
			'icon'   => 'building',
			'intro'  => __( 'Modrá sekce o Olomouci s fotkou a čísly.', 'najdisvujsen' ),
			'fields' => array(
				'title'     => array(
					'type'  => 'text',
					'label' => __( 'Nadpis', 'najdisvujsen' ),
				),
				'text'      => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'statement' => array(
					'type'  => 'textarea',
					'label' => __( 'Výrazná věta pod textem', 'najdisvujsen' ),
					'rows'  => 2,
					'max'   => 160,
				),
				'photo'     => array(
					'type'  => 'image',
					'label' => __( 'Fotografie', 'najdisvujsen' ),
				),
				'stats'     => array(
					'type'   => 'repeater',
					'label'  => __( 'Čísla', 'najdisvujsen' ),
					'help'   => __( 'Ideálně čtyři. Číslo zadávejte bez mezer, např. 21000.', 'najdisvujsen' ),
					'add'    => __( 'Přidat číslo', 'najdisvujsen' ),
					'item'   => __( 'Číslo', 'najdisvujsen' ),
					'title'  => 'label',
					'max'    => 4,
					'fields' => array(
						'number' => array(
							'type'     => 'number',
							'label'    => __( 'Číslo', 'najdisvujsen' ),
							'required' => true,
							'size'     => 'small',
						),
						'plus'   => array(
							'type'  => 'toggle',
							'label' => __( 'Zobrazit „+“ za číslem', 'najdisvujsen' ),
						),
						'label'  => array(
							'type'     => 'text',
							'label'    => __( 'Popisek', 'najdisvujsen' ),
							'required' => true,
						),
					),
				),
			),
		),
		'katalog'   => array(
			'label'  => __( 'Katalog programů', 'najdisvujsen' ),
			'icon'   => 'welcome-learn-more',
			'intro'  => __( 'Seznam programů se skládá sám ze stránek oborů (karty programů se zaškrtnutým „Zobrazit v katalogu“). Tady upravíte jen texty kolem a programy, které nemají vlastní stránku oboru.', 'najdisvujsen' ),
			'fields' => array(
				'title'   => array(
					'type'  => 'text',
					'label' => __( 'Nadpis', 'najdisvujsen' ),
				),
				'text'    => array(
					'type'  => 'rich',
					'label' => __( 'Úvodní text', 'najdisvujsen' ),
					'lite'  => true,
				),
				'levels'  => array(
					'type'   => 'group',
					'label'  => __( 'Názvy záložek', 'najdisvujsen' ),
					'fields' => array(
						'bc'  => array(
							'type'        => 'text',
							'label'       => __( 'Bakalářské', 'najdisvujsen' ),
							'placeholder' => __( 'Bakalářské studijní programy', 'najdisvujsen' ),
						),
						'mgr' => array(
							'type'        => 'text',
							'label'       => __( 'Magisterské', 'najdisvujsen' ),
							'placeholder' => __( 'Magisterské navazující studijní programy', 'najdisvujsen' ),
						),
					),
				),
				'careers' => array(
					'type'  => 'list',
					'label' => __( 'Profese v průvodci „Kým chceš být?“', 'najdisvujsen' ),
					'help'  => __( 'Zobrazí se jen profese, které má uvedené alespoň jeden obor (Základní údaje → Profese). Musí se shodovat přesně, např. „Historik“.', 'najdisvujsen' ),
					'add'   => __( 'Přidat profesi', 'najdisvujsen' ),
				),
				'extra'   => array(
					'type'   => 'repeater',
					'label'  => __( 'Programy bez vlastní stránky oboru', 'najdisvujsen' ),
					'help'   => __( 'Např. programy v angličtině s odkazem do katalogu univerzity.', 'najdisvujsen' ),
					'add'    => __( 'Přidat program', 'najdisvujsen' ),
					'item'   => __( 'Program', 'najdisvujsen' ),
					'title'  => 'name',
					'badge'  => 'level',
					'fields' => array(
						'name'     => array(
							'type'     => 'text',
							'label'    => __( 'Název programu', 'najdisvujsen' ),
							'required' => true,
						),
						'level'    => array(
							'type'     => 'radio',
							'label'    => __( 'Stupeň studia', 'najdisvujsen' ),
							'options'  => array_slice( najdisvujsen_program_level_options(), 0, 2, true ),
							'required' => true,
						),
						'category' => array(
							'type'     => 'select',
							'label'    => __( 'Kategorie', 'najdisvujsen' ),
							'options'  => najdisvujsen_program_categories(),
							'required' => true,
						),
						'url'      => array(
							'type'     => 'url',
							'label'    => __( 'Odkaz', 'najdisvujsen' ),
							'required' => true,
						),
					),
				),
				'cards'   => array(
					'type'   => 'repeater',
					'label'  => __( 'Odkazové karty pod katalogem', 'najdisvujsen' ),
					'add'    => __( 'Přidat kartu', 'najdisvujsen' ),
					'item'   => __( 'Karta', 'najdisvujsen' ),
					'title'  => 'text',
					'fields' => array(
						'label' => array(
							'type'  => 'text',
							'label' => __( 'Nadpis karty', 'najdisvujsen' ),
							'help'  => __( 'Nepovinné. S nadpisem bude karta modrá, např. „Erasmus Mundus Master degree“.', 'najdisvujsen' ),
						),
						'text'  => array(
							'type'     => 'text',
							'label'    => __( 'Text karty', 'najdisvujsen' ),
							'required' => true,
						),
						'url'   => array(
							'type'     => 'url',
							'label'    => __( 'Odkaz', 'najdisvujsen' ),
							'required' => true,
						),
					),
				),
			),
		),
		'dod'       => array(
			'label'  => __( 'Den otevřených dveří', 'najdisvujsen' ),
			'icon'   => 'calendar-alt',
			'intro'  => __( 'Termíny se na webu ukazují jen do dne konání, po něm samy zmizí. Den v týdnu se doplní automaticky.', 'najdisvujsen' ),
			'fields' => array(
				'title'  => array(
					'type'  => 'text',
					'label' => __( 'Nadpis', 'najdisvujsen' ),
				),
				'text'   => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'dates'  => array(
					'type'   => 'repeater',
					'label'  => __( 'Termíny', 'najdisvujsen' ),
					'add'    => __( 'Přidat termín', 'najdisvujsen' ),
					'item'   => __( 'Termín', 'najdisvujsen' ),
					'title'  => 'date',
					'fields' => array(
						'date' => array(
							'type'     => 'date',
							'label'    => __( 'Datum', 'najdisvujsen' ),
							'required' => true,
						),
						'time' => array(
							'type'        => 'text',
							'label'       => __( 'Čas', 'najdisvujsen' ),
							'placeholder' => __( 'např. od 8–14 hodin', 'najdisvujsen' ),
							'required'    => true,
						),
					),
				),
				'links'  => array(
					'type'   => 'repeater',
					'label'  => __( 'Tlačítka', 'najdisvujsen' ),
					'help'   => __( 'První tlačítko je výrazné, druhé obrysové.', 'najdisvujsen' ),
					'add'    => __( 'Přidat tlačítko', 'najdisvujsen' ),
					'item'   => __( 'Tlačítko', 'najdisvujsen' ),
					'title'  => 'label',
					'max'    => 2,
					'fields' => array(
						'label' => array(
							'type'     => 'text',
							'label'    => __( 'Text tlačítka', 'najdisvujsen' ),
							'required' => true,
						),
						'url'   => array(
							'type'     => 'url',
							'label'    => __( 'Odkaz', 'najdisvujsen' ),
							'required' => true,
						),
					),
				),
				'note'   => array(
					'type'        => 'text',
					'label'       => __( 'Poznámka pod termíny', 'najdisvujsen' ),
					'placeholder' => __( 'např. Těšíme se na vás!', 'najdisvujsen' ),
				),
				'photos' => najdisvujsen_photos_field( __( 'Nepovinné. Čtyři fotky v mozaice: první velká, poslední široká.', 'najdisvujsen' ), 4 ),
			),
		),
		'zivot'     => array(
			'label'  => __( 'Život na FF UP', 'najdisvujsen' ),
			'icon'   => 'format-gallery',
			'intro'  => __( 'Galerie fotografií. Zobrazí se, když jsou vybrané alespoň tři.', 'najdisvujsen' ),
			'fields' => array(
				'photos' => najdisvujsen_photos_field( __( 'Nejvýše deset fotografií. Pořadí změníte přetažením.', 'najdisvujsen' ), 10 ),
			),
		),
		'slovensko' => array(
			'label'  => __( 'Ze Slovenska', 'najdisvujsen' ),
			'icon'   => 'flag',
			'intro'  => __( 'Sekce pro uchazeče ze Slovenska.', 'najdisvujsen' ),
			'fields' => array(
				'title'  => array(
					'type'  => 'text',
					'label' => __( 'Nadpis', 'najdisvujsen' ),
				),
				'text'   => array(
					'type'  => 'rich',
					'label' => __( 'Text', 'najdisvujsen' ),
					'help'  => $rich_help,
				),
				'map'    => array(
					'type'  => 'image',
					'label' => __( 'Mapa nebo fotografie', 'najdisvujsen' ),
				),
				'trains' => array(
					'type'   => 'repeater',
					'label'  => __( 'Cesta vlakem', 'najdisvujsen' ),
					'add'    => __( 'Přidat město', 'najdisvujsen' ),
					'item'   => __( 'Město', 'najdisvujsen' ),
					'title'  => 'city',
					'fields' => array(
						'city' => array(
							'type'     => 'text',
							'label'    => __( 'Město', 'najdisvujsen' ),
							'required' => true,
						),
						'time' => array(
							'type'        => 'text',
							'label'       => __( 'Doba cesty', 'najdisvujsen' ),
							'placeholder' => __( 'např. 3 h', 'najdisvujsen' ),
							'required'    => true,
							'size'        => 'small',
						),
					),
				),
			),
		),
	);
}

/**
 * Returns the default value of a field.
 *
 * @since 0.5.0
 *
 * @param array $field Field definition.
 * @return mixed Default value.
 */
function najdisvujsen_field_default( $field ) {
	if ( array_key_exists( 'default', $field ) ) {
		return $field['default'];
	}

	switch ( $field['type'] ) {
		case 'group':
			return najdisvujsen_fields_defaults( $field['fields'] );
		case 'repeater':
		case 'list':
		case 'gallery':
		case 'checkboxes':
			return array();
		case 'image':
		case 'number':
			return 0;
		case 'toggle':
			return false;
		default:
			return '';
	}
}

/**
 * Returns default values of a set of fields.
 *
 * @since 0.5.0
 *
 * @param array[] $fields Field definitions keyed by name.
 * @return array Default values.
 */
function najdisvujsen_fields_defaults( $fields ) {
	$values = array();

	foreach ( $fields as $key => $field ) {
		if ( in_array( $field['type'], array( 'excerpt', 'thumbnail' ), true ) ) {
			continue;
		}

		$values[ $key ] = najdisvujsen_field_default( $field );
	}

	return $values;
}

/**
 * Fills missing values with defaults and drops unknown keys.
 *
 * @since 0.5.0
 *
 * @param array[] $fields Field definitions keyed by name.
 * @param mixed   $data   Stored values.
 * @return array Values of all fields.
 */
function najdisvujsen_fields_fill( $fields, $data ) {
	$data   = is_array( $data ) ? $data : array();
	$values = array();

	foreach ( $fields as $key => $field ) {
		if ( in_array( $field['type'], array( 'excerpt', 'thumbnail' ), true ) ) {
			continue;
		}

		if ( ! array_key_exists( $key, $data ) ) {
			$values[ $key ] = najdisvujsen_field_default( $field );
		} elseif ( 'group' === $field['type'] ) {
			$values[ $key ] = najdisvujsen_fields_fill( $field['fields'], $data[ $key ] );
		} elseif ( 'repeater' === $field['type'] ) {
			$values[ $key ] = array();

			foreach ( (array) $data[ $key ] as $item ) {
				$values[ $key ][] = najdisvujsen_fields_fill( $field['fields'], $item );
			}
		} else {
			$values[ $key ] = $data[ $key ];
		}
	}

	return $values;
}

/**
 * Sanitizes submitted values of a set of fields.
 *
 * @since 0.5.0
 *
 * @param array[] $fields Field definitions keyed by name.
 * @param mixed   $input  Submitted values.
 * @return array Sanitized values.
 */
function najdisvujsen_fields_sanitize( $fields, $input ) {
	$input  = is_array( $input ) ? $input : array();
	$values = array();

	foreach ( $fields as $key => $field ) {
		if ( in_array( $field['type'], array( 'excerpt', 'thumbnail' ), true ) ) {
			continue;
		}

		$values[ $key ] = najdisvujsen_field_sanitize( $field, $input[ $key ] ?? null );
	}

	return $values;
}

/**
 * Sanitizes a submitted value of one field.
 *
 * @since 0.5.0
 *
 * @param array $field Field definition.
 * @param mixed $value Submitted value.
 * @return mixed Sanitized value.
 */
function najdisvujsen_field_sanitize( $field, $value ) {
	switch ( $field['type'] ) {
		case 'group':
			return najdisvujsen_fields_sanitize( $field['fields'], $value );

		case 'repeater':
			$items = array();

			foreach ( is_array( $value ) ? $value : array() as $item ) {
				$item = najdisvujsen_fields_sanitize( $field['fields'], $item );

				if ( najdisvujsen_item_has_content( $field['fields'], $item ) ) {
					$items[] = $item;
				}
			}

			return isset( $field['max'] ) ? array_slice( $items, 0, $field['max'] ) : $items;

		case 'list':
			$items = array();

			foreach ( is_array( $value ) ? $value : array() as $item ) {
				$item = empty( $field['multiline'] ) ? sanitize_text_field( (string) $item ) : sanitize_textarea_field( (string) $item );

				if ( '' !== trim( $item ) ) {
					$items[] = trim( $item );
				}
			}

			return isset( $field['max'] ) ? array_slice( $items, 0, $field['max'] ) : $items;

		case 'gallery':
			$ids = array_values( array_filter( array_map( 'absint', is_array( $value ) ? $value : explode( ',', (string) $value ) ) ) );
			$ids = array_values( array_filter( $ids, 'wp_attachment_is_image' ) );

			return isset( $field['max'] ) ? array_slice( $ids, 0, $field['max'] ) : $ids;

		case 'image':
			$id = absint( $value );

			return $id && wp_attachment_is_image( $id ) ? $id : 0;

		case 'checkboxes':
			return array_values( array_intersect( array_keys( $field['options'] ), is_array( $value ) ? $value : array() ) );

		case 'select':
		case 'radio':
			$value = (string) $value;

			if ( isset( $field['options'][ $value ] ) ) {
				return $value;
			}

			return $field['default'] ?? '';

		case 'toggle':
			return ! empty( $value );

		case 'number':
			return absint( preg_replace( '/\D/', '', (string) $value ) );

		case 'date':
			$value = (string) $value;

			return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) && wp_checkdate( (int) substr( $value, 5, 2 ), (int) substr( $value, 8, 2 ), (int) substr( $value, 0, 4 ), $value ) ? $value : '';

		case 'url':
			return esc_url_raw( trim( (string) $value ) );

		case 'textarea':
			return trim( sanitize_textarea_field( (string) $value ) );

		case 'rich':
			return trim( wp_kses( (string) $value, najdisvujsen_rich_kses( ! empty( $field['lite'] ) ) ) );

		default:
			return trim( sanitize_text_field( (string) $value ) );
	}
}

/**
 * Checks whether a repeater item contains anything the editor typed or chose.
 *
 * Toggles and preset choices alone do not make an item worth keeping.
 *
 * @since 0.5.0
 *
 * @param array[] $fields Field definitions keyed by name.
 * @param array   $item   Sanitized item.
 * @return bool True if the item has content.
 */
function najdisvujsen_item_has_content( $fields, $item ) {
	foreach ( $fields as $key => $field ) {
		if ( in_array( $field['type'], array( 'toggle', 'select', 'radio', 'checkboxes' ), true ) ) {
			continue;
		}

		if ( ! empty( $item[ $key ] ) && '' !== wp_strip_all_tags( is_array( $item[ $key ] ) ? implode( '', array_map( 'strval', $item[ $key ] ) ) : (string) $item[ $key ] ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Returns the HTML allowed in rich text fields.
 *
 * @since 0.5.0
 *
 * @param bool $lite Whether the field allows only inline formatting and lists.
 * @return array[] Allowed tags for wp_kses().
 */
function najdisvujsen_rich_kses( $lite = false ) {
	$tags = array(
		'p'      => array(),
		'br'     => array(),
		'strong' => array(),
		'b'      => array(),
		'em'     => array(),
		'i'      => array(),
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
		'ul'     => array(),
		'ol'     => array(),
		'li'     => array(),
	);

	if ( ! $lite ) {
		$tags['h3']         = array();
		$tags['blockquote'] = array();
	}

	return $tags;
}

/**
 * Returns the definition of the content stored for a post.
 *
 * @since 0.5.0
 *
 * @param WP_Post|int $post Post.
 * @return array{schema: array[], key: string}|null Tabs and meta key, or null for other posts.
 */
function najdisvujsen_structured_type( $post ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return null;
	}

	if ( NAJDISVUJSEN_OBOR === $post->post_type ) {
		return array(
			'schema' => najdisvujsen_obor_schema(),
			'key'    => '_najdisvujsen_obor',
		);
	}

	if ( 'page' === $post->post_type && (int) get_option( 'page_on_front' ) === $post->ID ) {
		return array(
			'schema' => najdisvujsen_front_schema(),
			'key'    => '_najdisvujsen_front',
		);
	}

	return null;
}

/**
 * Returns the flat field list of a form: tab fields stored under the tab key.
 *
 * @since 0.5.0
 *
 * @param array[] $schema Tabs.
 * @return array[] Field definitions keyed by data key.
 */
function najdisvujsen_schema_fields( $schema ) {
	$fields = array();

	foreach ( $schema as $tab => $definition ) {
		if ( 'zakladni' === $tab ) {
			$fields = array_merge( $fields, $definition['fields'] );
			continue;
		}

		$fields[ $tab ] = array(
			'type'   => 'group',
			'fields' => $definition['fields'],
		);
	}

	return $fields;
}

/**
 * Returns the structured content of a study program page or the front page.
 *
 * During a preview from the edit screen, unsaved values are returned instead.
 *
 * @since 0.5.0
 *
 * @param WP_Post|int|null $post Post, defaults to the current post.
 * @return array Content with every field present.
 */
function najdisvujsen_get_data( $post = null ) {
	$post = get_post( $post );
	$type = $post ? najdisvujsen_structured_type( $post ) : null;

	if ( ! $type ) {
		return array();
	}

	$preview = najdisvujsen_preview_data( $post->ID );
	$stored  = null !== $preview ? $preview['data'] : get_post_meta( $post->ID, $type['key'], true );

	return najdisvujsen_fields_fill( najdisvujsen_schema_fields( $type['schema'] ), $stored );
}

/**
 * Formats rich text field content for output.
 *
 * Highlight boxes are stored as quotes and lists can be shown with check marks.
 *
 * @since 0.5.0
 *
 * @param string $html   Stored HTML.
 * @param bool   $checks Whether bulleted lists get check marks.
 * @return string HTML with paragraphs.
 */
function najdisvujsen_rich( $html, $checks = false ) {
	$html = trim( (string) $html );

	if ( '' === $html ) {
		return '';
	}

	$html = wpautop( $html );
	$html = str_replace( array( '<blockquote>', '</blockquote>' ), array( '<div class="wp-block-group is-style-highlight">', '</div>' ), $html );

	if ( $checks ) {
		$icon = '<span class="checks__icon">' . najdisvujsen_icon( 'check' ) . '</span>';
		$html = (string) preg_replace_callback(
			'#<ul>(.*?)</ul>#s',
			static function ( $matches ) use ( $icon ) {
				return '<ul class="checks">' . preg_replace( '#<li>(.*?)</li>#s', '<li>' . $icon . '<span>$1</span></li>', $matches[1] ) . '</ul>';
			},
			$html
		);
	}

	return najdisvujsen_nbsp( wp_filter_content_tags( $html ) );
}
