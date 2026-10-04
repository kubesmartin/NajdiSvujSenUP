<?php
/**
 * Template part for the front page hero.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

$najdisvujsen_id    = get_the_ID();
$najdisvujsen_title = najdisvujsen_get_page_header_field( 'hero_title', $najdisvujsen_id );
$najdisvujsen_rows  = najdisvujsen_hero_bubbles();
$najdisvujsen_label = __( 'Profese, ke kterým vede studium na FF UP', 'najdisvujsen' );
?>
<section class="hero" aria-labelledby="hero-title">
	<img
		class="hero__bg"
		src="<?php echo esc_url( NAJDISVUJSEN_URI . '/assets/images/hero-1600.webp' ); ?>"
		srcset="<?php echo esc_attr( NAJDISVUJSEN_URI . '/assets/images/hero-960.webp 960w, ' . NAJDISVUJSEN_URI . '/assets/images/hero-1600.webp 1600w, ' . NAJDISVUJSEN_URI . '/assets/images/hero-2400.webp 2400w' ); ?>"
		sizes="100vw"
		width="2400"
		height="1350"
		alt=""
		fetchpriority="high"
		decoding="async"
	>

	<div class="container container--wide hero__main">
		<p class="slogan">
			<span class="slogan__line"><span class="word">Najdi</span><span class="sticker sticker--slogan" aria-hidden="true">✨</span></span>
			<span class="slogan__line slogan__line--end"><span class="sticker sticker--slogan sticker--outline" aria-hidden="true"><?php echo wp_kses( najdisvujsen_icon( 'arrow-down-right' ), najdisvujsen_icon_kses() ); ?></span><span class="word">svůj</span></span>
			<span class="slogan__line"><span class="word">sen</span><span class="sticker sticker--slogan" aria-hidden="true">🎓</span></span>
		</p>

		<div class="hero__intro">
			<h1 id="hero-title" class="hero__title"><?php echo esc_html( $najdisvujsen_title ? $najdisvujsen_title : __( 'Studuj na FF UP', 'najdisvujsen' ) ); ?></h1>
			<p class="hero__lead">
				<?php
				echo esc_html(
					najdisvujsen_nbsp(
						has_excerpt() ? get_the_excerpt() : __( 'informace pro uchazeče o studium na Filozofické fakultě UP v Olomouci', 'najdisvujsen' )
					)
				);
				?>
			</p>
			<div class="cta-row">
				<?php najdisvujsen_apply_button( 'large' ); ?>
			</div>
			<?php najdisvujsen_social_buttons( $najdisvujsen_id ); ?>
		</div>
	</div>

	<?php
	najdisvujsen_marquee(
		$najdisvujsen_rows[0] ?? array(),
		array(
			'reverse'     => true,
			'duration'    => 80,
			'interactive' => true,
			'label'       => $najdisvujsen_label,
		)
	);
	najdisvujsen_marquee(
		$najdisvujsen_rows[1] ?? array(),
		array(
			'duration'    => 90,
			'interactive' => true,
			'label'       => $najdisvujsen_label,
		)
	);
	?>
</section>
