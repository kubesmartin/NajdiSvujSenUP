<?php
/**
 * The header template.
 *
 * @package NajdiSvujSen
 * @since 0.1.0
 */

$najdisvujsen_nav = najdisvujsen_page_nav();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#191d34">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Přejít na obsah', 'najdisvujsen' ); ?></a>

<header class="site-header<?php echo $najdisvujsen_nav || has_nav_menu( 'primary' ) ? ' site-header--has-menu' : ''; ?>" data-header>
	<div class="site-header__inner">
		<?php najdisvujsen_site_branding(); ?>

		<?php if ( $najdisvujsen_nav ) : ?>
			<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e( 'Obsah stránky', 'najdisvujsen' ); ?>">
				<?php foreach ( $najdisvujsen_nav as $najdisvujsen_anchor => $najdisvujsen_label ) : ?>
					<a href="#<?php echo esc_attr( $najdisvujsen_anchor ); ?>"><?php echo esc_html( $najdisvujsen_label ); ?></a>
				<?php endforeach; ?>
				<div class="site-nav__apply"><?php najdisvujsen_apply_button( 'large' ); ?></div>
			</nav>
		<?php elseif ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-nav" class="site-nav" aria-label="<?php esc_attr_e( 'Hlavní menu', 'najdisvujsen' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'walker'         => new Najdisvujsen_Flat_Nav_Walker(),
					)
				);
				?>
				<div class="site-nav__apply"><?php najdisvujsen_apply_button( 'large' ); ?></div>
			</nav>
		<?php endif; ?>

		<div class="site-header__actions">
			<?php najdisvujsen_apply_button(); ?>
			<?php if ( $najdisvujsen_nav || has_nav_menu( 'primary' ) ) : ?>
				<button type="button" class="burger" aria-expanded="false" aria-controls="site-nav" aria-label="<?php esc_attr_e( 'Otevřít menu', 'najdisvujsen' ); ?>" data-burger>
					<span></span><span></span><span></span>
				</button>
			<?php endif; ?>
		</div>
	</div>
</header>

<main id="content" class="site-main">
