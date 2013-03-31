<?php
/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'ecommerce_twentyeleven_setup' );

//if ( ! function_exists( 'ecommerce_twentyeleven_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function ecommerce_twentyeleven_setup() {
	/* Remove twenty Eleven functions */
	remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
	remove_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );
	remove_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

	/* Make Twenty Eleven eCommerce available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_child_theme_textdomain ('ecommerce_twentyeleven', get_stylesheet_directory(). '/languages');
	
	/* Extra image cropped sizes */
	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'product-thumb-cropped', 200, 200, true ); //(cropped)
		add_image_size( 'product-big-thumb-cropped', 300, 300, true ); //(cropped)
	}


	/**
	 * Enqueue the styles for the current color scheme.
	 */
	function ecommerce_twentyeleven_enqueue_color_scheme() {
		$options = twentyeleven_get_theme_options();
		$color_scheme = $options['color_scheme'];
	
		if ( 'dark' == $color_scheme )
			wp_enqueue_style( 'chil-dark', get_stylesheet_directory_uri() . '/css/dark.css', array(), null );
	
	}
	add_action( 'wp_enqueue_scripts', 'ecommerce_twentyeleven_enqueue_color_scheme', 99);

} //endif; // ecommerce_twentyeleven_setup



/* Excerpt for ecommerce_twentyeleven */
  function ecommerce_twentyeleven_excerpt_length( $length ) {
	  return 20;
  }
  add_filter( 'excerpt_length', 'ecommerce_twentyeleven_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
  function ecommerce_twentyeleven_continue_reading_link() {
	  return '<a href="'. esc_url( get_permalink() ) . '">' . __( ' More <span class="meta-nav">&raquo;</span>', 'ecommerce_twentyeleven' ) . '</a>';
  }
	
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and ecommerce_twentyeleven_continue_reading_link().
 *
 * function tied to the excerpt_more filter hook.
 */
  function ecommerce_twentyeleven_auto_excerpt_more( $more ) {
	  return ' &hellip;' . ecommerce_twentyeleven_continue_reading_link();
  }
  add_filter( 'excerpt_more', 'ecommerce_twentyeleven_auto_excerpt_more' );



/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
	function ecommerce_twentyeleven_custom_excerpt_more( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$output .= ecommerce_twentyeleven_continue_reading_link();
		}
		return $output;
	}
	add_filter( 'get_the_excerpt', 'ecommerce_twentyeleven_custom_excerpt_more' );



/**
* Add custom body classes
*/
/*The is_singular() conditional tag check if the user is displaying a single post, a single page or an attachment, which are respectively check with is_single(), is_page() and is_attachment(). With these conditional tags come three usefull classes nested in the body tag: .single, .page or .attachment. But, no .singular classe to rule them all!*/

add_filter( 'body_class', 'basics_body_class' );
if ( ! function_exists( 'basics_body_class' ) ) :
    function basics_body_class($classes) {
        if ( is_singular() )
        $classes[] = 'singular';
        return $classes;
    }
endif;




/**
*Register extra sidebar.
*
*/
register_sidebar( array(
	'name' => __( 'Related Content', 'twentyeleven-ecommerce' ),
	'id' => 'sidebar-related',
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => "</aside>",
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
) );

?>
