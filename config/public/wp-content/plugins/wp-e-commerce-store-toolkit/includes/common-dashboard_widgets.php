<?php
/*

Filename: common-dashboard_widgets.php
Description: common-dashboard_widgets.php loads commonly access Dashboard widgets across the Visser Labs suite.
Version: 1.1

*/

/* Start of: WP e-Commerce News - by Visser Labs */

if( !function_exists( 'wpsc_vl_dashboard_setup' ) ) {

	function wpsc_vl_dashboard_setup() {

		wp_add_dashboard_widget( 'wpsc_vl_news_widget', __( 'WP e-Commerce Plugin News - by Visser Labs', 'wpsc_vl' ), 'wpsc_vl_news_widget' );

	}
	add_action( 'wp_dashboard_setup', 'wpsc_vl_dashboard_setup' );

	function wpsc_vl_news_widget() {

		include_once( ABSPATH . WPINC . '/feed.php' );

		$rss = fetch_feed( 'http://www.visser.com.au/blog/category/e-commerce/feed/' );
		$output = '<div class="rss-widget">';
		if( !is_wp_error( $rss ) ) {
			$maxitems = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
			$output .= '<ul>';
			foreach ( $rss_items as $item ) :
				$output .= '<li>';
				$output .= '<a href="' . $item->get_permalink() . '" title="' . 'Posted ' . $item->get_date( 'j F Y | g:i a' ) . '" class="rsswidget">' . $item->get_title() . '</a>';
				$output .= '<span class="rss-date">' . $item->get_date( 'j F, Y' ) . '</span>';
				$output .= '<div class="rssSummary">' . $item->get_description() . '</div>';
				$output .= '</li>';
			endforeach;
			$output .= '</ul>';
		} else {
			$message = __( 'Connection failed. Please check your network settings.', 'wpsc_vl' );
			$output .= '<p>' . $message . '</p>';
		}
		$output .= '</div>';

		echo $output;

	}

}

/* End of: WP e-Commerce News - by Visser Labs */
?>