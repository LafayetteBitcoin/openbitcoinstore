<?php
/*

Filename: common.php
Description: common.php loads commonly accessed functions across the Visser Labs suite.

- wpsc_is_admin_icon_valid

- wpsc_get_action
- wpsc_get_major_version
- wpsc_get_minor_version

- wpsc_manage_purchase_logs_actions_column
- wpsc_manage_purchase_logs_actions_cell

*/

if( is_admin() ) {

	/* Start of: WordPress Administration */

	if( !function_exists( 'wpsc_is_admin_icon_valid' ) ) {
		function wpsc_is_admin_icon_valid( $icon = 'tools' ) {

			switch( $icon ) {

				case 'index':
				case 'edit':
				case 'post':
				case 'link':
				case 'comments':
				case 'page':
				case 'users':
				case 'upload':
				case 'tools':
				case 'plugins':
				case 'themes':
				case 'profile':
				case 'admin':
					return $icon;
					break;

			}

		}
	}

	/*
	 * Add a generic Actions column to the Manage Sales table within the WordPress Administration.
	 * Third party developers can use the available hook/action to add buttons to the Actions column until it is integrated into Core.
	 * 
	 * Since: 1.5.0
	 * 
	 */
	if( !function_exists( 'wpsc_manage_purchase_logs_actions_column' ) || !function_exists( 'wpsc_manage_purchase_logs_actions_cell' ) ) {

		function wpsc_manage_purchase_logs_actions_css() {

			/* Common stylesheet */
			wp_enqueue_style( 'wpsc_common-style', plugins_url( '/templates/admin/wpsc-admin_common.css', dirname( __FILE__ ) ), false, '1.0.0', 'all' );

		}
		add_action( 'admin_print_styles', 'wpsc_manage_purchase_logs_actions_css' );

		function wpsc_manage_purchase_logs_actions_column( $columns ) {

			$columns['actions'] = __( 'Actions', 'wpsc' );
			return $columns;

		}
		add_filter( 'manage_dashboard_page_wpsc-purchase-logs_columns', 'wpsc_manage_purchase_logs_actions_column', 10, 1 );

		function wpsc_manage_purchase_logs_actions_cell( $default = null, $column_name = null, $item = null ) {

			$output = $default;
			$output = apply_filters( 'wpsc_manage_purchase_logs_actions_column', $default, $column_name, $item );
			return $output;

		}
		add_filter( 'wpsc_manage_purchase_logs_custom_column', 'wpsc_manage_purchase_logs_actions_cell', 10, 3 );

	}

	include_once( 'common-dashboard_widgets.php' );

	/* End of: WordPress Administration */

}

if( !function_exists( 'wpsc_get_action' ) ) {

	function wpsc_get_action( $switch = false ) {

		if( $switch ) {

			if( isset( $_GET['action'] ) )
				$action = $_GET['action'];
			else if( !isset( $action ) && isset( $_POST['action'] ) )
				$action = $_POST['action'];
			else
				$action = false;

		} else {

			if( isset( $_POST['action'] ) )
				$action = $_POST['action'];
			else if( !isset( $action ) && isset( $_GET['action'] ) )
				$action = $_GET['action'];
			else
				$action = false;

		}
		return $action;

	}

}

if( !function_exists( 'wpsc_get_major_version' ) ) {

	function wpsc_get_major_version() {

		$version = get_option( 'wpsc_version' );
		return substr( $version, 0, 3 );

	}

}

if( !function_exists( 'wpsc_get_minor_version' ) ) {

	function wpsc_get_minor_version() {

		if( isset( $_GLOBAL['WPSC_VERSION'] ) )
			$version = WPSC_VERSION;
		else
			$version = get_option( 'wpsc_version' );
		return substr( $version, 0, 5 );

	}

}
?>