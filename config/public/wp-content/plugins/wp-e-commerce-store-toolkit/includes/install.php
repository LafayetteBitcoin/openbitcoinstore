<?php
function wpsc_st_install() {

	wpsc_st_create_options();

}

function wpsc_st_create_options() {

	global $wpsc_st;

	$prefix = $wpsc_st['prefix'];

	if( !get_option( $prefix . '_demo_store' ) )
		add_option( $prefix . '_demo_store', 'off' );
	if( !get_option( $prefix . '_demo_store_text' ) )
		add_option( $prefix . '_demo_store_text', __( 'This is a demo store for testing purposes - no orders shall be fulfilled.', 'wpsc_st' ) );

}
?>