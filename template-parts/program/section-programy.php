<?php
/**
 * Template part for the "What can you study" section of a program page.
 *
 * Program cards with notes and a tip below them. When the cards cover more
 * than one study level, they can be filtered by tabs.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_section = $args['section'];
$najdisvujsen_data    = $najdisvujsen_section['data'];
$najdisvujsen_cards   = $najdisvujsen_data['programs'];
$najdisvujsen_levels  = array_intersect_key( najdisvujsen_program_levels(), array_flip( wp_list_pluck( $najdisvujsen_cards, 'level' ) ) );
$najdisvujsen_tabs    = count( $najdisvujsen_levels ) > 1 && ! in_array( '', wp_list_pluck( $najdisvujsen_cards, 'level' ), true );
$najdisvujsen_dept    = najdisvujsen_get_data()['department'];
$najdisvujsen_url     = najdisvujsen_get_data()['department_url'];

najdisvujsen_section_open(
	$najdisvujsen_section,
	array(
		'tone'    => $args['tone'],
		'type'    => 'programy',
		'heading' => false,
	)
);
?>
<div class="section__head">
	<h2 class="section__title"><?php echo wp_kses( $najdisvujsen_section['title'], najdisvujsen_inline_kses() ); ?></h2>
	<?php if ( $najdisvujsen_tabs ) : ?>
		<div class="tabs js-only" role="tablist" aria-label="<?php esc_attr_e( 'Stupeň studia', 'najdisvujsen' ); ?>" data-filter-tabs>
			<?php foreach ( $najdisvujsen_levels as $najdisvujsen_level => $najdisvujsen_label ) : ?>
				<button type="button" class="tabs__tab" role="tab" aria-selected="<?php echo array_key_first( $najdisvujsen_levels ) === $najdisvujsen_level ? 'true' : 'false'; ?>" data-value="<?php echo esc_attr( $najdisvujsen_level ); ?>"><?php echo esc_html( $najdisvujsen_label ); ?></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php if ( $najdisvujsen_cards ) : ?>
	<div class="programs-grid"<?php echo $najdisvujsen_tabs ? ' data-filter-items' : ''; ?>>
		<?php foreach ( $najdisvujsen_cards as $najdisvujsen_card ) : ?>
			<article class="program-card"<?php echo $najdisvujsen_tabs ? ' data-level="' . esc_attr( $najdisvujsen_card['level'] ) . '"' : ''; ?>>
				<?php $najdisvujsen_tags = najdisvujsen_program_tags( $najdisvujsen_card ); ?>
				<?php if ( $najdisvujsen_tags ) : ?>
					<ul class="program-card__tags">
						<?php foreach ( $najdisvujsen_tags as $najdisvujsen_tag ) : ?>
							<li class="tag tag--<?php echo esc_attr( $najdisvujsen_tag['tone'] ); ?>"><?php echo esc_html( $najdisvujsen_tag['label'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<h3 class="program-card__title"><?php echo esc_html( $najdisvujsen_card['name'] ); ?></h3>

				<?php najdisvujsen_prose( $najdisvujsen_card['text'], 'program-card__text' ); ?>

				<?php if ( $najdisvujsen_card['url'] ) : ?>
					<a class="program-card__link" href="<?php echo esc_url( $najdisvujsen_card['url'] ); ?>">
						<span><?php esc_html_e( 'Detail programu', 'najdisvujsen' ); ?><span class="screen-reader-text"> <?php echo esc_html( $najdisvujsen_card['name'] ); ?></span></span>
						<span class="program-card__arrow"><?php echo wp_kses( najdisvujsen_icon( 'arrow-right' ), najdisvujsen_icon_kses() ); ?></span>
					</a>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php najdisvujsen_prose( $najdisvujsen_data['text'], 'programs-notes' ); ?>
<?php if ( '' !== $najdisvujsen_data['tip'] ) : ?>
	<?php najdisvujsen_prose( '<blockquote>' . $najdisvujsen_data['tip'] . '</blockquote>', 'programs-notes' ); ?>
<?php endif; ?>

<div class="info-cards">
	<?php if ( $najdisvujsen_dept && $najdisvujsen_url ) : ?>
		<a class="info-card info-card--blue" href="<?php echo esc_url( $najdisvujsen_url ); ?>">
			<span class="info-card__eyebrow"><?php esc_html_e( 'Katedra', 'najdisvujsen' ); ?></span>
			<span class="info-card__title"><?php echo esc_html( $najdisvujsen_dept ); ?></span>
			<span class="info-card__foot"><?php esc_html_e( 'Navštiv web katedry', 'najdisvujsen' ); ?><?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?></span>
		</a>
	<?php elseif ( $najdisvujsen_dept ) : ?>
		<div class="info-card info-card--blue">
			<span class="info-card__eyebrow"><?php esc_html_e( 'Katedra', 'najdisvujsen' ); ?></span>
			<span class="info-card__title"><?php echo esc_html( $najdisvujsen_dept ); ?></span>
		</div>
	<?php endif; ?>
	<a class="info-card" href="https://www.univerzitnimesto.cz/">
		<span class="info-card__eyebrow"><?php esc_html_e( 'Univerzita Palackého v Olomouci', 'najdisvujsen' ); ?></span>
		<span class="info-card__text"><?php esc_html_e( 'Druhá nejstarší vysoká škola v Česku v jediném skutečně univerzitním městě', 'najdisvujsen' ); ?></span>
		<span class="info-card__foot"><?php esc_html_e( 'Poznej UP', 'najdisvujsen' ); ?><?php echo wp_kses( najdisvujsen_icon( 'arrow-up-right' ), najdisvujsen_icon_kses() ); ?></span>
	</a>
</div>
<?php
najdisvujsen_collage( $najdisvujsen_section['photos'], 'programy', 'section__photos' );
najdisvujsen_section_close();
