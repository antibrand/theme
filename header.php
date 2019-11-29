<?php
/**
 * Begin HTML markup
 *
 * Renders the `<head>` section, gets site
 * identity and navigation.
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 *
 * @todo       Add hooks for nav above or below header.
 */

// Conditional canonical link.
if ( is_home() && ! is_front_page() ) {
    $canonical = get_permalink( get_option( 'page_for_posts' ) );
} elseif ( is_archive() ) {
    $canonical = get_permalink( get_option( 'page_for_posts' ) );
} else {
    $canonical = get_permalink();
}

/**
 * Conditional site logo markup
 *
 * Uses a link to the home page if not on the home page.
 */
if ( is_front_page() && has_custom_logo() ) {
	$id        = get_theme_mod( 'custom_logo' );
	$src       = wp_get_attachment_image_src( $id , 'full' );
	$site_logo = sprintf(
		'<img src="%1s" class="custom-logo" alt="%2s" itemprop="logo" width="512" height="512">',
		$src[0],
		get_bloginfo( 'name' )
	);
} elseif ( has_custom_logo() ) {
	$site_logo = get_custom_logo();
} else {
	$site_logo = sprintf(
		'<a href="%1s" class="custom-logo-link" rel="home" itemprop="url"><img src="%2s" class="custom-logo" alt="%3s" itemprop="logo" width="512" height="512"></a>',
		esc_url( home_url( '/' ) ),
		get_theme_file_uri( '/assets/images/non-logo.jpg' ),
		get_bloginfo( 'name' )
	);
}

/**
 * Conditional site name markup
 *
 * Uses an h1 element on the home page with no link.
 * Uses a p element on all other pages with a link to
 * the home page.
 */
if ( is_front_page() ) {
	$site_title = sprintf(
		'<h1 class="site-title">%1s</h1>',
		get_bloginfo( 'name' )
	);
} else {
	$site_title = sprintf(
		'<p class="site-title"><a href="%1s" rel="home">%2s</a></p>',
		esc_url( home_url( '/' ) ),
		get_bloginfo( 'name' )
	);
}
// Apply a filter for customization.
$site_title = apply_filters( 'ab_site_title', $site_title );

/**
 * Conditional site description markup
 *
 * Prints nothing if the description/tagline is field empty.
 * Prints a p element if the customizer is open, wether the
 * description/tagline is field empty or not because the Site
 * Identity section can edit the description.
 */
$get_description = get_bloginfo( 'description', 'display' );
if ( ! empty( $get_description ) || is_customize_preview() ) {
	$site_description = sprintf(
		'<p class="site-description">%1s</p>',
		$get_description
	);
} else {
	$site_description = null;
}
// Apply a filter for customization.
$site_description = apply_filters( 'ab_site_title', $site_description );

// Begin HTML output.
?>
<!doctype html>
<?php do_action( 'before_html' ); ?>
<html <?php language_attributes(); ?> class="no-js">
<head id="<?php echo get_bloginfo( 'wpurl' ); ?>" data-template-set="<?php echo get_template(); ?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open() ) {
		echo sprintf( '<link rel="pingback" href="%s" />', get_bloginfo( 'pingback_url' ) );
	} ?>
	<link href="<?php echo $canonical; ?>" rel="canonical" />
	<?php if ( is_search() ) { echo '<meta name="robots" content="noindex,nofollow" />'; } ?>

	<!-- Prefetch font URLs -->
	<link rel='dns-prefetch' href='//fonts.adobe.com'/>
	<link rel='dns-prefetch' href='//fonts.google.com'/>

	<?php do_action( 'before_wp_head' ); ?>
	<?php wp_head(); ?>
	<?php do_action( 'after_wp_head' ); ?>
</head>

<body <?php body_class(); ?>>
<?php AB_Theme\Tags\before_page(); ?>
<div id="page" class="site" itemscope="itemscope" itemtype="<?php AB_Theme\Tags\site_schema(); ?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'antibrand' ); ?></a>

	<nav id="site-navigation" class="main-navigation" role="directory" itemscope itemtype="http://schema.org/SiteNavigationElement">
		<button class="menu-toggle" aria-controls="main-menu" aria-expanded="false">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="theme-icon menu-icon"><path d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"/></svg>
			<?php esc_html_e( 'Menu', 'antibrand' ); ?>
		</button>
		<?php
		wp_nav_menu( [
			'theme_location' => 'main',
			'menu_id'        => 'main-menu',
		] );
		?>
	</nav>

	<header id="masthead" class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
		<div class="site-identity">
			<div class="site-logo">
				<?php echo $site_logo; ?>
			</div>
			<div class="site-title-description">
				<?php echo $site_title; ?>
				<?php echo $site_description; ?>
			</div>
		</div>
		<div class="site-header-image" role="presentation">
			<figure>
				<?php
				if ( has_header_image() ) {
					$attributes = [
						'alt'  => ''
					];
					the_header_image_tag( $attributes );
				} else {
					echo sprintf(
						'<img src="%1s" alt="" width="2048" height="878" />',
						get_theme_file_uri( '/assets/images/default-header.jpg' )
					);
				} ?>
			</figure>
		</div>
	</header>

	<div id="content" class="site-content">
