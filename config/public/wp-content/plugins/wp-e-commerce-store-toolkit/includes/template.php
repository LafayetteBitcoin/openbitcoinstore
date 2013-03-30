<?php
function wpsc_st_load_styles() {

	global $wpsc_st;

	$demo_store = wpsc_st_get_option( 'demo_store', 0 );
	if( $demo_store ) {
		$plugin_slug = $wpsc_st['dirname'];
		if( file_exists( STYLESHEETPATH . '/wpsc-store_demo.css' ) )
			wp_enqueue_style( 'wpsc_st-demo_store', STYLESHEETPATH . '/wpsc-store_demo.css' );
		else
			wp_enqueue_style( 'wpsc_st-demo_store', plugins_url( $plugin_slug . '/templates/store/wpsc-store_demo.css' ) );
	}

}
add_action( 'wp_enqueue_scripts', 'wpsc_st_load_styles' );

function wpsc_st_demo_store() {

	$demo_store = wpsc_st_get_option( 'demo_store', 0 );
	if( $demo_store ) {
		$message = wpsc_st_get_option( 'demo_store_text', __( 'This is a demo store for testing purposes - no orders shall be fulfilled.', 'wpsc_st' ) );
		$output = '<p class="demo_store">' . $message . '</p>';
		echo $output;
	}

}
add_action( 'wp_footer', 'wpsc_st_demo_store' );

if( !function_exists( 'wpsc_the_product_weight' ) ) {
	function wpsc_the_product_weight( $product_id = null ) {

		global $wp_query;

		if( !$product_id )
			$product_id = $wp_query->post->ID;
		$product_data = get_post_meta( $product_id, '_wpsc_product_metadata', true );
		$weight = $product_data['weight'];
		$weight_unit = $product_data['weight_unit'];
		if( $weight && $weight_unit ) {
			$output = wpsc_convert_weight( $weight, 'pound', $weight_unit );
			switch( $weight_unit ) {

				case 'pound':
					$weight_unit = __( ' lbs.', 'wpsc' );
					break;

				case 'ounce':
					$weight_unit = __( ' oz.', 'wpsc' );
					break;

				case 'gram':
					$weight_unit = __( ' g', 'wpsc' );
					break;

				case 'kilograms':
				case 'kilogram':
					$weight_unit = __( ' kgs.', 'wpsc' );
					break;

			}
			$output .= ' ' . $weight_unit;
			echo $output;
		}

	}
}
?>