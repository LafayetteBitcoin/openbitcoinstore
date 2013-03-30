<?php

include_once get_template_directory() . '/functions/blackbird-functions.php';
$functions_path = get_template_directory() . '/functions/';
/* These files build out the options interface.  Likely won't need to edit these. */
require_once ($functions_path . 'admin-functions.php');  // Custom functions and plugins
require_once ($functions_path . 'admin-interface.php');  // Admin Interfaces (options,framework, seo)
/* These files build out the theme specific options and associated functions. */
require_once ($functions_path . 'theme-options.php');   // Options panel settings and custom settings
require_once ($functions_path . 'shortcodes.php');
require_once ($functions_path . 'dynamic-image.php');
?>
<?php

/* ----------------------------------------------------------------------------------- */
/* Styles Enqueue */
/* ----------------------------------------------------------------------------------- */

function blackbird_add_stylesheet() {
    wp_enqueue_style('shortcodes', get_template_directory_uri() . "/css/shortcode.css", '', '', 'all');
}

add_action('init', 'blackbird_add_stylesheet');
/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ----------------------------------------------------------------------------------- */

function blackbird_wp_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('blackbird-ddsmoothmenu', get_template_directory_uri() . '/js/ddsmoothmenu.js', array('jquery'));
        wp_enqueue_script('blckbird-flex-slider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'));
        wp_enqueue_script('blackbird-testimonial', get_template_directory_uri() . '/js/slides.min.jquery.js', array('jquery'));
        wp_enqueue_script('blackbird-prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery'));
        wp_enqueue_script('blackbird-validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'));
        wp_enqueue_script('blackbird-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'));
    } elseif (is_admin()) {
        
    }
}

add_action('wp_enqueue_scripts', 'blackbird_wp_enqueue_scripts');
/* ----------------------------------------------------------------------------------- */
/* Custom Jqueries Enqueue */
/* ----------------------------------------------------------------------------------- */

function blackbird_custom_jquery() {
    wp_enqueue_script('mobile-menu', get_template_directory_uri() . "/js/mobile-menu.js", array('jquery'));
}

add_action('wp_footer', 'blackbird_custom_jquery');
//Front Page Rename
$get_status = blackbird_get_option('re_nm');
$get_file_ac = get_template_directory() . '/front-page.php';
$get_file_dl = get_template_directory() . '/front-page-hold.php';
//True Part
if ($get_status === 'off' && file_exists($get_file_ac)) {
    rename("$get_file_ac", "$get_file_dl");
}
//False Part
if ($get_status === 'on' && file_exists($get_file_dl)) {
    rename("$get_file_dl", "$get_file_ac");
}

//
function blackbird_get_option($name) {
    $options = get_option('blackbird_options');
    if (isset($options[$name]))
        return $options[$name];
}

//
function blackbird_update_option($name, $value) {
    $options = get_option('blackbird_options');
    $options[$name] = $value;
    return update_option('blackbird_options', $options);
}

//
function blackbird_delete_option($name) {
    $options = get_option('blackbird_options');
    unset($options[$name]);
    return update_option('blackbird_options', $options);
}

//Enqueue comment thread js
function blackbird_enqueue_scripts() {
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'blackbird_enqueue_scripts');
?>
