<?php

/** 2 Columns */
function col2_shortcode($atts, $content = null) {
    return '<div class="one_half">' . do_shortcode($content) . '</div>';
}

add_shortcode('col2', 'col2_shortcode');

/** 2 Columns Last */
function col2_last_shortcode($atts, $content = null) {
    return '<div class="one_half last">' . do_shortcode($content) . '</div>';
}

add_shortcode('col2_last', 'col2_last_shortcode');

/** 3 Columns */
function col3_shortcode($atts, $content = null) {
    return '<div class="one_third">' . do_shortcode($content) . '</div>';
}

add_shortcode('col3', 'col3_shortcode');

/** 3 Columns Last */
function col3_last_shortcode($atts, $content = null) {
    return '<div class="one_third last">' . do_shortcode($content) . '</div>';
}

add_shortcode('col3_last', 'col3_last_shortcode');

/** 4 Columns */
function col4_shortcode($atts, $content = null) {
    return '<div class="one_fourth">' . do_shortcode($content) . '</div>';
}

add_shortcode('col4', 'col4_shortcode');

/** 4 Columns Last */
function col4_last_shortcode($atts, $content = null) {
    return '<div class="one_fourth last">' . do_shortcode($content) . '</div>';
}

add_shortcode('col4_last', 'col4_last_shortcode');

/** Two-Third Columns */
function col2_3_shortcode($atts, $content = null) {
    return '<div class="two_third">' . do_shortcode($content) . '</div>';
}

add_shortcode('col2_3', 'col2_3_shortcode');

/** Two-Third Columns Last */
function col2_3_last_shortcode($atts, $content = null) {
    return '<div class="two_third last">' . do_shortcode($content) . '</div>';
}

add_shortcode('col2_3_last', 'col2_3_last_shortcode');

/** Three-Fourth Columns */
function col3_4_shortcode($atts, $content = null) {
    return '<div class="three_fourth">' . do_shortcode($content) . '</div>';
}

add_shortcode('col3_4', 'col3_4_shortcode');

/** Three-Fourth Columns Last */
function col3_4_last_shortcode($atts, $content = null) {
    return '<div class="three_fourth last">' . do_shortcode($content) . '</div>';
}

add_shortcode('col3_4_last', 'col3_4_last_shortcode');

//Clear div
function clear($atts, $content = null) {
    return '<div class="clear"></div>';
}

add_shortcode('clear', 'clear');

//Buttons
function smallblue_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="button2 blue">' . do_shortcode($content) . '<span></span></a>';
}

add_shortcode('smallblue', 'smallblue_shortcode');

//Small Green Button
function smallgreen_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="button2 green">' . do_shortcode($content) . '<span></span></a>';
}

add_shortcode('smallgreen', 'smallgreen_shortcode');

//Small Pink Button
function smallpink_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="button2 pink">' . do_shortcode($content) . '<span></span></a>';
}

add_shortcode('smallpink', 'smallpink_shortcode');

//Small Brown Button
function smallbrown_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="button2 brown">' . do_shortcode($content) . '<span></span></a>';
}

add_shortcode('smallbrown', 'smallbrown_shortcode');

//Small Yellow Button
function smallyellow_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="button2 yellow">' . do_shortcode($content) . '<span></span></a>';
}

add_shortcode('smallyellow', 'smallyellow_shortcode');

//Button Blue
function btnblue_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="buttons blue">' . do_shortcode($content) . '</a>';
}

add_shortcode('btnblue', 'btnblue_shortcode');

//Button Green
function btngreen_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="buttons green">' . do_shortcode($content) . '</a>';
}

add_shortcode('btngreen', 'btngreen_shortcode');

//Button Pink
function btngray_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="buttons gray">' . do_shortcode($content) . '</a>';
}

add_shortcode('btngray', 'btngray_shortcode');

//Button Brown
function btnred_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="buttons red">' . do_shortcode($content) . '</a>';
}

add_shortcode('btnred', 'btnred_shortcode');

//Button Yellow
function btnyellow_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("url" => ''), $atts));
    return '<a href="' . $url . '" class="buttons yellow">' . do_shortcode($content) . '</a>';
}

add_shortcode('btnyellow', 'btnyellow_shortcode');
?>
