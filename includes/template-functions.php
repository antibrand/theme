<?php
/**
 * Template functions
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Namespace specificity for theme functions & filters.
namespace AB_Theme\Includes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Body classes
 *
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0
 * @access public
 * @param  array $classes Classes for the body element.
 * @return array Returns the array of body classes.
 */
function body_classes( $classes ) {

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;

}
add_filter( 'body_class', 'AB_Theme\Includes\body_classes' );

/**
 * Pingback header
 *
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the link element in '<head>`.
 */
function pingback_header() {

	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}

}
add_action( 'wp_head', 'AB_Theme\Includes\pingback_header' );

/**
 * Theme toggle script
 *
 * Toggles a body class and toggles the
 * text of the toggle button.
 *
 * @since  1.0.0
 * @access public
 * @return mixed
 */
function theme_mode_script() {

	// Label before the icon.
	$label = esc_html__( 'Switch mode: ', 'antibrand' );

	// Moon icon for switching to dark theme.
	$moon = '<svg width="100%" height="100%" viewBox="0 0 513 513" aria-labelledby="theme-toggle-title theme-toggle-desc"><path id="path4103" d="M202.75,0.006c-1.223,-0.036 -2.491,0.088 -3.812,0.375c-116.434,27.751 -202.58,134.472 -198.813,260.563c-0.053,-1.65 -0.106,-3.294 -0.125,-4.938c0,129.122 95.597,235.915 219.875,253.469c8.43,1.312 16.973,2.209 25.625,2.656c128.701,6.741 237.809,-80.311 266,-198.594c3.677,-14.096 -13.488,-23.887 -23.906,-13.468c-40.449,41.061 -98.688,64.354 -161.813,57c-88.865,-9.806 -160.55,-82.136 -170.968,-171c-7.355,-63.125 15.938,-121.333 57,-161.782c9.441,-9.442 2.761,-23.931 -9.063,-24.281Zm-198.656,298.969c0.202,1.112 0.439,2.236 0.656,3.344c-0.218,-1.114 -0.453,-2.226 -0.656,-3.344Zm5,21.781c0.319,1.16 0.633,2.315 0.969,3.469c-0.336,-1.154 -0.65,-2.309 -0.969,-3.469Zm19.219,49.219c0.844,1.64 1.715,3.285 2.593,4.906c-0.88,-1.625 -1.747,-3.261 -2.593,-4.906Zm110.125,112.062c1.341,0.721 2.707,1.428 4.062,2.125c1.516,0.78 3.031,1.562 4.563,2.313c-1.529,-0.749 -3.05,-1.534 -4.563,-2.313c-1.363,-0.701 -2.713,-1.4 -4.062,-2.125Z"/>';

	$moon .= sprintf(
		'<title id="%1s">%2s</title>',
		'theme-toggle-title',
		esc_html__( 'Dark mode', 'antibrand' )
	);
	$moon .= sprintf(
		'<desc id="%1s">%2s</desc>',
		'theme-toggle-desc',
		esc_html__( 'Switch the theme to dark mode', 'antibrand' )
	);
	$moon .= sprintf(
		'<text class="screen-reader-text">%1s</text>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' )
	);

	$moon .= '</svg>';

	// Sun icon for switching to light theme.
	$sun = '<svg width="100%" height="100%" viewBox="0 0 512 512" aria-labelledby="theme-toggle-title theme-toggle-desc"><path id="path4097" d="M512,256c0,141.385 -114.615,256 -256,256c-141.385,0 -256,-114.615 -256,-256c0,-141.385 114.615,-256 256,-256c141.385,0 256,114.615 256,256Z"/>';

	$moon .= sprintf(
		'<title id="%1s">%2s</title>',
		'theme-toggle-title',
		esc_html__( 'Light mode', 'antibrand' )
	);
	$sun .= sprintf(
		'<desc id="%1s">%2s</desc>',
		'theme-toggle-desc',
		esc_html__( 'Switch the theme to light mode', 'antibrand' )
	);
	$sun .= sprintf(
		'<text class="screen-reader-text">%1s</text>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' )
	);

	$sun .= '</svg>';

	?>
<script>jQuery(document).ready(function(e){var t=e('#theme-toggle');localStorage.theme?(e('body').addClass(localStorage.theme),e(t).html(localStorage.html)):(e('body').addClass('light-mode'),e(t).html('<?php echo $label . $moon; ?>')),e(t).click(function(){e('body').hasClass('light-mode')?(e('body').removeClass('light-mode').addClass('dark-mode'),e(t).html('<?php echo $label . $sun; ?>'),localStorage.theme='dark-mode',localStorage.html='<?php echo $label . $sun; ?>'):(e('body').removeClass('dark-mode').addClass('light-mode'),e(t).html('<?php echo $label . $moon; ?>'),localStorage.theme='light-mode',localStorage.html='<?php echo $label . $moon; ?>')})});</script>
<?php

}