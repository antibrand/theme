<?php
/**
 * Template tags
 *
 * @package    system
 * @subpackage AB_Theme
 * @since      1.0.0
 */

// Namespace specificity for theme functions & filters.
namespace AB_Theme\Tags;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the application version is 5.0 or greater.
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if the version is 5.0 or greater.
 */
function theme_new_cms() {

	// Get the version.
	$version = get_bloginfo( 'version' );

	if ( $version >= 5.0 ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check if the CMS is ClassicPress.
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if ClassicPress is running.
 */
function theme_classicpress() {

	if ( function_exists( 'classicpress_version' ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check for Advanced Custom Fields
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if the ACF free or Pro plugin is active.
 */
function theme_acf() {

	if ( class_exists( 'acf' ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check for Advanced Custom Fields Pro
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if the ACF Pro plugin is active.
 */
function theme_acf_pro() {

	if ( class_exists( 'acf_pro' ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Check for Advanced Custom Fields options page
 *
 * @since  1.0.0
 * @access public
 * @return bool Returns true if ACF 4.0 free plus the
 *              Options Page addon or Pro plugin is active.
 */
function theme_acf_options() {

	if ( class_exists( 'acf_pro' ) ) {
		return true;
	} elseif ( ( class_exists( 'acf' ) && class_exists( 'acf_options_page' ) ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Site Schema
 *
 * Conditional Schema attributes for `<div id="page"`.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the relevant itemtype.
 */
function site_schema() {

	// Change page slugs and template names as needed.
	if ( is_page( 'about' ) || is_page( 'about-us' ) || is_page_template( 'page-about.php' ) || is_page_template( 'about.php' ) ) {
		$itemtype = esc_attr( 'AboutPage' );
	} elseif ( is_page( 'contact' ) || is_page( 'contact-us' ) || is_page_template( 'page-contact.php' ) || is_page_template( 'contact.php' ) ) {
		$itemtype = esc_attr( 'ContactPage' );
	} elseif ( is_page( 'faq' ) || is_page( 'faqs' ) || is_page_template( 'page-faq.php' ) || is_page_template( 'faq.php' ) ) {
		$itemtype = esc_attr( 'QAPage' );
	} elseif ( is_page( 'cart' ) || is_page( 'shopping-cart' ) || is_page( 'checkout' ) || is_page_template( 'cart.php' ) || is_page_template( 'checkout.php' ) ) {
		$itemtype = esc_attr( 'CheckoutPage' );
	} elseif ( is_front_page() || is_page() ) {
		$itemtype = esc_attr( 'WebPage' );
	} elseif ( is_author() || is_plugin_active( 'buddypress/bp-loader.php' ) && bp_is_home() || is_plugin_active( 'bbpress/bbpress.php' ) && bbp_is_user_home() ) {
		$itemtype = esc_attr( 'ProfilePage' );
	} elseif ( is_search() ) {
		$itemtype = esc_attr( 'SearchResultsPage' );
	} else {
		$itemtype = esc_attr( 'Blog' );
	}

	echo $itemtype;

}

/**
 * Posted on
 *
 * Prints HTML with meta information for the current post-date/time.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the date posted.
 */
function posted_on() {

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s: post date. */
		esc_html_x( 'Posted on %s', 'post date', 'antibrand' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}

/**
 * Posted by
 *
 * Prints HTML with meta information for the current author.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the author name.
 */
function posted_by() {

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'antibrand' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}

/**
 * Entry footer
 *
 * Prints HTML with meta information for the categories, tags and comments.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns various post-related links.
 */
function entry_footer() {

	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {

		$categories_list = get_the_category_list( esc_html__( ', ', 'antibrand' ) );
		if ( $categories_list ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'antibrand' ) . '</span>', $categories_list );
		}

		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'antibrand' ) );

		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'antibrand' ) . '</span>', $tags_list );
		}

	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'antibrand' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				get_the_title()
			)
		);
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			wp_kses(
				__( ' Edit <span class="screen-reader-text">%s</span>', 'antibrand' ),
				[
					'span' => [
						'class' => [],
					],
				]
			),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

}

/**
 * Post thumbnail
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since  1.0.0
 * @access public
 * @return string Returns HTML for the post thumbnail.
 */
function post_thumbnail() {

	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	// Check for the large 16:9 video image size.
	if ( has_image_size( 'image-name' ) ) {
		$size = 'large-video';
	} else {
		$size = 'post-thumbnail';
	}

	// Thumbnail image arguments.
	$args = [
		'alt'  => '',
		'role' => 'presentation'
	];


	if ( is_singular() ) :
		?>

		<div class="post-thumbnail">
			<?php the_post_thumbnail( $size, $args ); ?>
		</div>

		<?php
	else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( $size, $args ); ?>
		</a>

		<?php
	endif;
}

/**
 * Open template tags.
 *
 * Following are template tags for further development
 * by the theme author, child themes, or plugins.
 *
 * These are primarily provided as examples.
 *
 * @todo Possibly add more open tags but perhaps not,
 *       as this is a starter theme.
 *
 * @since  1.0.0
 * @access public
 */

// Fires after opening `body` and before `#page`.
function before_page() {
	do_action( 'before_page' );
}

// Fires after `#page` and before `wp_footer`.
function after_page() {
	do_action( 'after_page' );
}

/**
 * Theme toggle funcionality
 *
 * Prints the toggle button and adds the
 * toggle script to the footer.
 *
 * @since  1.0.0
 * @access public
 * @return mixed
 */
function theme_mode() {

	// Add the toggle script to the footer.
	add_action( 'wp_footer', 'AB_Theme\Includes\theme_mode_script' );

	// Label before the icon.
	$label = esc_html__( 'Switch mode: ', 'antibrand' );

	// Moon icon for when the default light theme is active.
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

	// Toggle button markup.
	$button = sprintf(
		'<button id="theme-toggle" class="theme-toggle" type="button" name="dark_light" title="%1s">%2s %3s</button>',
		esc_html__( 'Toggle light-dark theme', 'antibrand' ),
		$label,
		$moon
	);

	// Print the toggle button.
	echo $button;

}