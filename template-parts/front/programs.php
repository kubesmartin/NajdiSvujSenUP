<?php
/**
 * Template part for the program catalogue on the front page.
 *
 * The catalogue lists programs of the program pages. The career picker,
 * level tabs and search are enhancements; without scripts all programs
 * are listed.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_data      = $args['data'];
$najdisvujsen_catalogue = array( 'levels' => najdisvujsen_get_catalogue() );
$najdisvujsen_map       = najdisvujsen_career_map();
$najdisvujsen_careers   = array_values(
	array_filter(
		najdisvujsen_picker_careers(),
		static function ( $career ) use ( $najdisvujsen_map ) {
			return isset( $najdisvujsen_map[ mb_strtolower( $career ) ] );
		}
	)
);
$najdisvujsen_section   = array(
	'anchor' => 'programy',
	'title'  => $najdisvujsen_data['title'],
);

if ( ! $najdisvujsen_catalogue['levels'] ) {
	return;
}

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone' => 'muted',
		'type' => 'programs',
	)
);

najdisvujsen_prose( $najdisvujsen_data['text'], 'section__intro' );
?>
<div class="explorer" data-explorer>
	<?php if ( $najdisvujsen_careers ) : ?>
		<div class="picker js-only" data-careers="<?php echo esc_attr( (string) wp_json_encode( $najdisvujsen_careers, JSON_UNESCAPED_UNICODE ) ); ?>">
			<div class="picker__head">
				<p class="picker__title"><?php esc_html_e( 'Kým chceš být?', 'najdisvujsen' ); ?></p>
				<button type="button" class="btn btn--small btn--outline" data-picker-clear hidden>
					<?php echo wp_kses( najdisvujsen_icon( 'x' ), najdisvujsen_icon_kses() ); ?>
					<?php esc_html_e( 'Zrušit výběr', 'najdisvujsen' ); ?>
				</button>
			</div>
			<p class="picker__stage" data-picker-stage hidden><span class="bubble bubble--small" data-picker-stage-bubble></span></p>
			<div class="picker__dice">
				<span><?php esc_html_e( 'Nevíš, čím chceš být?', 'najdisvujsen' ); ?></span>
				<button type="button" class="dice" data-dice>
					<span class="dice__icon" aria-hidden="true">🎲</span>
					<span data-dice-label><?php esc_html_e( 'Vyber za mě', 'najdisvujsen' ); ?></span>
				</button>
			</div>
			<p class="picker__result" data-picker-result aria-live="polite" hidden></p>
		</div>
		<?php
		wp_print_inline_script_tag(
			(string) wp_json_encode( $najdisvujsen_map, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE ),
			array(
				'type' => 'application/json',
				'id'   => 'career-map',
			)
		);
		?>
	<?php endif; ?>

	<div class="explorer__controls js-only">
		<?php if ( count( $najdisvujsen_catalogue['levels'] ) > 1 ) : ?>
			<div class="tabs tabs--wide" role="tablist" aria-label="<?php esc_attr_e( 'Stupeň studia', 'najdisvujsen' ); ?>">
				<?php foreach ( array_keys( $najdisvujsen_catalogue['levels'] ) as $najdisvujsen_index => $najdisvujsen_level ) : ?>
					<button type="button" class="tabs__tab" role="tab" id="<?php echo esc_attr( 'level-tab-' . $najdisvujsen_level ); ?>" aria-controls="<?php echo esc_attr( 'level-' . $najdisvujsen_level ); ?>" aria-selected="<?php echo 0 === $najdisvujsen_index ? 'true' : 'false'; ?>" data-level-tab="<?php echo esc_attr( $najdisvujsen_level ); ?>"><?php echo esc_html( $najdisvujsen_catalogue['levels'][ $najdisvujsen_level ]['title'] ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<label class="search">
			<span class="screen-reader-text"><?php esc_html_e( 'Hledej program', 'najdisvujsen' ); ?></span>
			<?php echo wp_kses( najdisvujsen_icon( 'search' ), najdisvujsen_icon_kses() ); ?>
			<input type="search" class="search__input" placeholder="<?php esc_attr_e( 'Hledej program…', 'najdisvujsen' ); ?>" autocomplete="off" data-explorer-search>
		</label>
	</div>

	<p class="explorer__count" data-explorer-count aria-live="polite" hidden></p>

	<?php foreach ( $najdisvujsen_catalogue['levels'] as $najdisvujsen_level => $najdisvujsen_level_data ) : ?>
		<div class="explorer__level" id="<?php echo esc_attr( 'level-' . $najdisvujsen_level ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( 'level-tab-' . $najdisvujsen_level ); ?>" data-level="<?php echo esc_attr( $najdisvujsen_level ); ?>">
			<h3 class="explorer__level-title"><?php echo esc_html( $najdisvujsen_level_data['title'] ); ?></h3>
			<?php foreach ( $najdisvujsen_level_data['categories'] as $najdisvujsen_category => $najdisvujsen_items ) : ?>
				<details class="accordion" open>
					<summary class="accordion__head">
						<span class="accordion__title"><?php echo esc_html( $najdisvujsen_category ); ?></span>
						<span class="tag tag--grey"><?php echo esc_html( (string) count( $najdisvujsen_items ) ); ?></span>
						<span class="accordion__icon" aria-hidden="true"><?php echo wp_kses( najdisvujsen_icon( 'plus' ), najdisvujsen_icon_kses() ); ?></span>
					</summary>
					<ul class="program-links">
						<?php foreach ( $najdisvujsen_items as $najdisvujsen_item ) : ?>
							<li>
								<?php if ( $najdisvujsen_item['url'] ) : ?>
									<a class="program-link" href="<?php echo esc_url( $najdisvujsen_item['url'] ); ?>" data-slug="<?php echo esc_attr( $najdisvujsen_item['slug'] ); ?>">
										<?php echo esc_html( $najdisvujsen_item['name'] ); ?>
										<?php if ( ! $najdisvujsen_item['slug'] ) : ?>
											<?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?>
										<?php endif; ?>
									</a>
								<?php else : ?>
									<span class="program-link" data-slug=""><?php echo esc_html( $najdisvujsen_item['name'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</details>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>

<?php if ( $najdisvujsen_data['cards'] ) : ?>
	<div class="link-cards">
		<?php foreach ( $najdisvujsen_data['cards'] as $najdisvujsen_card ) : ?>
			<a class="card card--interactive<?php echo $najdisvujsen_card['label'] ? ' card--brand' : ''; ?>" href="<?php echo esc_url( $najdisvujsen_card['url'] ); ?>">
				<?php if ( $najdisvujsen_card['label'] ) : ?>
					<span class="card__eyebrow"><?php echo esc_html( $najdisvujsen_card['label'] ); ?></span>
					<span class="card__title"><?php echo esc_html( $najdisvujsen_card['text'] ); ?></span>
				<?php else : ?>
					<span class="card__body"><?php echo esc_html( najdisvujsen_nbsp( $najdisvujsen_card['text'] ) ); ?></span>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
najdisvujsen_section_close();
