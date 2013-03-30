<?php
/*
Plugin Name: WP e-Commerce - Store Toolkit
Plugin URI: http://www.visser.com.au/wp-ecommerce/plugins/store-toolkit/
Description: Store Toolkit - formally Nuke - includes a growing set of commonly-used WP e-Commerce administration tools aimed at web developers and store maintainers.
Version: 1.9.3
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'wpsc_st', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/functions.php' );

include_once( 'includes/common.php' );

switch( wpsc_get_major_version() ) {

	case '3.7':
		include_once( 'includes/release-3_7.php' );
		break;

	case '3.8':
		include_once( 'includes/release-3_8.php' );
		break;

}

$wpsc_st = array(
	'filename' => basename( __FILE__ ),
	'dirname' => basename( dirname( __FILE__ ) ),
	'abspath' => dirname( __FILE__ ),
	'relpath' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ )
);

$wpsc_st['prefix'] = 'wpsc_st';
$wpsc_st['name'] = __( 'Store Toolkit for WP e-Commerce', 'wpsc_st' );
$wpsc_st['menu'] = __( 'Store Toolkit', 'wpsc_st' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	include_once( 'includes/install.php' );
	register_activation_hook( __FILE__, 'wpsc_st_install' );

	function wpsc_st_add_settings_link( $links, $file ) {

		static $this_plugin;
		if( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );
		if( $file == $this_plugin ) {
			/* Settings */
			$manage_link = sprintf( '<a href="%s">' . __( 'Settings', 'wpsc_st' ) . '</a>', add_query_arg( 'page', 'wpsc_st', 'options-general.php' ) );
			array_unshift( $links, $manage_link );
			if( function_exists( 'wpsc_find_purchlog_status_name' ) ) {
				/* Manage */
				$settings_link = sprintf( '<a href="%s">' . __( 'Manage', 'wpsc_st' ) . '</a>', add_query_arg( array( 'post_type' => 'wpsc-product', 'page' => 'wpsc_st-toolkit' ), 'edit.php' ) );
				array_unshift( $links, $settings_link );
			} else {
				/* Uninstall */
				$manage_link = sprintf( '<a href="%s#uninstall-wpecommerce">' . __( 'Uninstall', 'wpsc_st' ) . '</a>', add_query_arg( 'page', 'wpsc_st', 'options-general.php' ) );
				array_unshift( $links, $manage_link );
			}
		}
		return $links;

	}
	add_filter( 'plugin_action_links', 'wpsc_st_add_settings_link', 10, 2 );

	function wpsc_st_enqueue_scripts( $hook ) {

		/* Settings */
		$page = 'settings_page_wpsc_st';
		if( $page == $hook ) {
			/* Color Picker */
			wp_register_script( 'colorpicker', plugins_url( '/js/colorpicker.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'colorpicker' );
			wp_enqueue_style( 'colorpicker', plugins_url( '/templates/admin/colorpicker.css', __FILE__ ) );

			/* Common */
			wp_enqueue_script( 'wpsc_st-settings', plugins_url( '/templates/admin/wpsc-admin_st-settings.js', __FILE__ ), array( 'jquery' ) );
		}

		/* Manage Sales */
		$pages = array( 'dashboard_page_wpsc-purchase-logs', 'wpsc-product_page_wpsc_st-toolkit', 'wpsc-product_page_wpsc_st-store_status', 'settings_page_wpsc_st' );
		if( in_array( $hook, $pages ) ) {
			wp_enqueue_style( 'wpsc_st_styles', plugins_url( '/templates/admin/wpsc-admin_st-toolkit.css', __FILE__ ) );
			wp_enqueue_script( 'wpsc_st-toolkit', plugins_url( '/templates/admin/wpsc-admin_st-toolkit.js', __FILE__ ), array( 'jquery' ) );
		}

	}
	add_action( 'admin_enqueue_scripts', 'wpsc_st_enqueue_scripts' );

	function wpsc_st_init() {

		global $wpdb, $wpsc_st;

		$action = wpsc_get_action();
		if( !$action && isset( $_POST['wpsc_admin_action'] ) )
			$action = $_POST['wpsc_admin_action'];
		if( !$action && isset( $_GET['wpsc_admin_action'] ) )
			$action = $_GET['wpsc_admin_action'];
		switch( $action ) {

			case 'relink-pages':
				$product_page = wpsc_st_get_page_by_shortcode( '[productspage]' );
				$checkout = wpsc_st_get_page_by_shortcode( '[shoppingcart]' );
				$transaction_results = wpsc_st_get_page_by_shortcode( '[transactionresults]' );
				$my_account = wpsc_st_get_page_by_shortcode( '[userlog]' );
				if( $product_page )
					update_option( 'product_list_url', get_bloginfo( 'url' ) . "/?page_id=" . $product_page );
				if( $checkout )
					update_option( 'shopping_cart_url', get_bloginfo( 'url' ) . "/?page_id=" . $checkout );
				if( $checkout )
					update_option( 'checkout_url', get_bloginfo( 'url' ) . "/?page_id=" . $checkout );
				if( $transaction_results )
					update_option( 'transact_url', get_bloginfo( 'url' ) . "/?page_id=" . $transaction_results );
				if( $my_account )
					update_option( 'user_account_url', get_bloginfo( 'url' ) . "/?page_id=" . $my_account );
				break;

			case 'relink-existing-preregistered-sales':
				$sales = wpsc_st_get_unlinked_sales();
				$size = count( $sales );
				if( $sales ) {
					$adjusted_sales = 0;
					foreach( $sales as $sale ) {
						$sale_email = wpsc_st_get_email_from_sale( $sale->ID );
						if( $sale_email ) {
							$sale_user = get_user_by( 'email', $sale_email );
							if( $sale_user ) {
								$wpdb->update( $wpdb->prefix . 'wpsc_purchase_logs', array(
									'user_ID' => $sale_user->ID
								), array( 'id' => $sale->ID ) );
							}
							$adjusted_sales++;
						}
					}
				}
				if( $adjusted_sales > 0 )
					$message = sprintf( __( '%s of %s unlinked Sale\'s from pre-registered Users have been re-linked.', 'wpsc_st' ), '<strong>' . $adjusted_sales . '</strong>', '<strong>' . $size . '</strong>' );
				else
					$message = __( 'No existing Sales from pre-registered Users have been re-linked.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;
				break;

			case 'reset-file-downloads-sale':
				if( wpsc_get_minor_version() >= '3.8.8' )
					$purchase_id = $_GET['id'];
				else
					$purchase_id = $_GET['purchaselog_id'];
				$file_downloads_sql = $wpdb->prepare( "SELECT `id`, `downloads` FROM `" . $wpdb->prefix . "wpsc_download_status` WHERE `purchid` = %d", $purchase_id );
				$file_downloads = $wpdb->get_results( $file_downloads_sql );
				if( $file_downloads ) {
					$max_downloads = get_option( 'wpsc_max_downloads' );
					if( $max_downloads ) {
						foreach( $file_downloads as $file_download ) {
							if( $file_download->downloads < $max_downloads ) {
								$wpdb->update( $wpdb->prefix . 'wpsc_download_status', array(
									'downloads' => $max_downloads
								), array( 'id' => $file_download->id ) );
							}
						}
					} else {
					$message = __( 'Max downloads per file has not been set - or is set to 0 - within Settings > Store > Admin screen.', 'wpsc_st' );
					$output = '<div class="error settings-error"><p>' . $message . '</p></div>';
					}
					$message = __( 'File Downloads have been reset for this Sale.', 'wpsc_st' );
					$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				} else {
					$message = __( 'No File Downloads are assigned to this Sale.', 'wpsc_st' );
					$output = '<div class="error settings-error"><p>' . $message . '</p></div>';
				}
				echo $output;
				break;

			case 'demo':
				$options = $_POST['options'];
				if( isset( $options['demo_store'] ) )
					$options['demo_store'] = 1;
				else
					$options['demo_store'] = 0;
				foreach( $options as $key => $option )
					wpsc_st_update_option( $key, $option );
				break;

			case 'nuke':
				if( !ini_get( 'safe_mode' ) )
					set_time_limit( 0 );

				/* WP e-Commerce */
				if( isset( $_POST['wpsc_st_products'] ) )
					wpsc_st_clear_dataset( 'products' );
				if( isset( $_POST['wpsc_st_product_variations'] ) )
					wpsc_st_clear_dataset( 'variations' );
				if( isset( $_POST['wpsc_st_product_tags'] ) )
					wpsc_st_clear_dataset( 'tags' );
				if( isset( $_POST['wpsc_st_categories'] ) ) {
					$categories = $_POST['wpsc_st_categories'];
					wpsc_st_clear_dataset( 'categories', $categories );
				} else if( isset( $_POST['wpsc_st_product_categories'] ) ) {
					wpsc_st_clear_dataset( 'categories' );
				}
				if( isset( $_POST['wpsc_st_product_images'] ) )
					wpsc_st_clear_dataset( 'images' );
				if( isset( $_POST['wpsc_st_product_files'] ) )
					wpsc_st_clear_dataset( 'files' );
				if( isset( $_POST['wpsc_st_sales_orders'] ) )
					wpsc_st_clear_dataset( 'orders' );
				if( isset( $_POST['wpsc_st_coupons'] ) )
					wpsc_st_clear_dataset( 'coupons' );

				/* 3rd Party */
				if( isset( $_POST['wpsc_st_wishlist'] ) )
					wpsc_st_clear_dataset( 'wishlist' );
				if( isset( $_POST['wpsc_st_enquiries'] ) )
					wpsc_st_clear_dataset( 'enquiries' );
				if( isset( $_POST['wpsc_st_creditcards'] ) )
					wpsc_st_clear_dataset( 'credit-cards' );
				if( isset( $_POST['wpsc_st_customfields'] ) )
					wpsc_st_clear_dataset( 'custom-fields' );
				if( isset( $_POST['wpsc_st_previewfiles'] ) )
					wpsc_st_clear_dataset( 'preview-files' );

				/* WordPress */
				if( isset( $_POST['wpsc_st_posts'] ) )
					wpsc_st_clear_dataset( 'posts' );
				if( isset( $_POST['wpsc_st_post_categories'] ) )
					wpsc_st_clear_dataset( 'post_categories' );
				if( isset( $_POST['wpsc_st_post_tags'] ) )
					wpsc_st_clear_dataset( 'post_tags' );
				if( isset( $_POST['wpsc_st_links'] ) )
					wpsc_st_clear_dataset( 'links' );
				if( isset( $_POST['wpsc_st_comments'] ) )
					wpsc_st_clear_dataset( 'comments' );

				break;

				case 'uninstall':
					global $uninstall;

					$uninstall = new stdClass();
					$uninstall->log = '';

					$uninstall->log .= "<br />" . __( 'Removing WP e-Commerce tables...', 'wpsc_st' );
					$wpsc_tables = array();
					$wpsc_tables[] = array( 'label' => __( 'Also Bought', 'wpsc_st' ), 'table' => 'wpsc_also_bought' );
					$wpsc_tables[] = array( 'label' => __( 'Cart Contents', 'wpsc_st' ), 'table' => 'wpsc_cart_contents' );
					$wpsc_tables[] = array( 'label' => __( 'Checkout Forms', 'wpsc_st' ), 'table' => 'wpsc_checkout_forms' );
					$wpsc_tables[] = array( 'label' => __( 'Claimed Stock', 'wpsc_st' ), 'table' => 'wpsc_claimed_stock' );
					$wpsc_tables[] = array( 'label' => __( 'Coupons', 'wpsc_st' ), 'table' => 'wpsc_coupon_codes' );
					$wpsc_tables[] = array( 'label' => __( 'Currency List', 'wpsc_st' ), 'table' => 'wpsc_currency_list' );
					$wpsc_tables[] = array( 'label' => __( 'Download Status', 'wpsc_st' ), 'table' => 'wpsc_download_status' );
					$wpsc_tables[] = array( 'label' => __( 'Meta', 'wpsc_st' ), 'table' => 'wpsc_meta' );
					$wpsc_tables[] = array( 'label' => __( 'Product Rating', 'wpsc_st' ), 'table' => 'wpsc_product_rating' );
					$wpsc_tables[] = array( 'label' => __( 'Purchase Logs', 'wpsc_st' ), 'table' => 'wpsc_purchase_logs' );
					$wpsc_tables[] = array( 'label' => __( 'Tax Regions', 'wpsc_st' ), 'table' => 'wpsc_region_tax' );
					$wpsc_tables[] = array( 'label' => __( 'Submited Form Data', 'wpsc_st' ), 'table' => 'wpsc_submited_form_data' );
					$size = count( $wpsc_tables );
					for( $i = 0; $i < $size; $i++ ) {
						/* Check that the WP e-Commerce table exists */
						if( wpsc_st_check_table_exists( $wpsc_tables[$i]['table'] ) ) {
							// $wpdb->query( "DROP TABLE `" . $wpdb->prefix . $wpsc_tables[$i]['table'] . "`" );
							$uninstall->log .= "<br />>>> " . sprintf( __( 'Removed %s (%s)', 'wpsc_st' ), $wpsc_tables[$i]['label'], $wpdb->prefix . $wpsc_tables[$i]['table'] );
						}
					}

					$uninstall->log .= "<br /><br />" . __( 'Removing WP e-Commerce details...', 'wpsc_st' );
					$wpsc_dataset = array();
					$wpsc_dataset[] = array( 'label' => __( 'Products', 'wpsc_st' ), 'dataset' => 'products' );
					$wpsc_dataset[] = array( 'label' => __( 'Product Variations', 'wpsc_st' ), 'dataset' => 'variations' );
					$wpsc_dataset[] = array( 'label' => __( 'Product Tags', 'wpsc_st' ), 'dataset' => 'tags' );
					$wpsc_dataset[] = array( 'label' => __( 'Product Categories', 'wpsc_st' ), 'dataset' => 'categories' );
					$wpsc_dataset[] = array( 'label' => __( 'Product Images', 'wpsc_st' ), 'dataset' => 'images' );
					$wpsc_dataset[] = array( 'label' => __( 'Product Files', 'wpsc_st' ), 'dataset' => 'files' );
					$wpsc_dataset[] = array( 'label' => __( 'Sales', 'wpsc_st' ), 'dataset' => 'orders' );
					$wpsc_dataset[] = array( 'label' => __( 'Coupons', 'wpsc_st' ), 'dataset' => 'coupons' );
					$wpsc_dataset[] = array( 'label' => __( 'Add to Wishlist', 'wpsc_st' ), 'dataset' => 'wishlist' );
					$wpsc_dataset[] = array( 'label' => __( 'Enquiries', 'wpsc_st' ), 'dataset' => 'enquiries' );
					$wpsc_dataset[] = array( 'label' => __( 'Offline Payments', 'wpsc_st' ), 'dataset' => 'credit-cards' );
					$wpsc_dataset[] = array( 'label' => __( 'Custom Fields', 'wpsc_st' ), 'dataset' => 'custom-fields' );
					$wpsc_dataset[] = array( 'label' => __( 'Preview Files', 'wpsc_st' ), 'dataset' => 'preview-files' );
					$wpsc_dataset[] = array( 'label' => __( 'Plugin Pages', 'wpsc_st' ), 'dataset' => 'wpsc_pages' );
					$wpsc_dataset[] = array( 'label' => __( 'Plugin Options', 'wpsc_st' ), 'dataset' => 'wpsc_options' );
					$size = count( $wpsc_dataset );
					for( $i = 0; $i < $size; $i++ ) {
						if( $wpsc_dataset[$i]['dataset'] ) {
							// wpsc_st_clear_dataset( $wpsc_dataset[$i] );
							$uninstall->log .= "<br />>>> " . sprintf( __( 'Removed %s', 'wpsc_st' ), $wpsc_dataset[$i]['label'] 	);
						}
					}
					break;

				case 'wpsc_update_session_id':

					global $wpdb;

					$session_id = $_POST['session_id'];
					$purchase_id = $_POST['purchase_id'];
					if( isset( $session_id ) && $purchase_id )
						$wpdb->update( $wpdb->prefix . 'wpsc_purchase_logs', array(
							'sessionid' => $session_id
						), array( 'id' => $purchase_id ) );
					break;

		}

	}
	add_action( 'admin_init', 'wpsc_st_init' );

	function wpsc_st_store_admin_menu() {

		add_submenu_page( 'wpsc_sm', __( 'Store Toolkit', 'wpsc_st' ), __( 'Store Toolkit', 'wpsc_st' ), 'manage_options', 'wpsc_st', 'wpsc_st_html_toolkit' );
		remove_filter( 'wpsc_additional_pages', 'wpsc_st_add_modules_manage_pages', 10 );
		remove_action( 'admin_menu', 'wpsc_st_admin_menu', 10 );

	}
	add_action( 'wpsc_sm_store_admin_subpages', 'wpsc_st_store_admin_menu' );

	function wpsc_st_default_file_downloads_html_page() {

		global $wpsc_st, $wpdb;

		$post_type = 'wpsc-product-file';

		$mime_types_all = new stdClass;
		$mime_types_all->post_mime_type = __( 'All', 'wpsc_st' );
		$mime_types_count = wp_count_posts( $post_type );
		$mime_types_all->count = $mime_types_count->inherit;
		if( $mime_types_all->count ) {

			$current_post_mime = false;
			if( isset( $_GET['post_mime_type'] ) ) {
				$current_post_mime = $_GET['post_mime_type'];
				if( $current_post_mime == 'all' )
					$current_post_mime = false;
			}

			if( $current_post_mime ) {
				$args = array(
					'post_type' => $post_type,
					'post_mime_type' => wpsc_st_format_post_mime_type_filter( $current_post_mime, 'expand' ),
					'post_status' => 'inherit',
					'numberposts' => -1
				);
			} else {
				$args = array(
					'post_type' => $post_type,
					'post_status' => 'inherit',
					'numberposts' => -1
				);
			}
			$files = get_posts( $args );
			$mime_types = wpsc_st_get_mime_types( $post_type );
			$show_mime_ext = true;
			if( $mime_types ) {
				$i = 1;
				array_unshift( $mime_types, $mime_types_all );
				$size = count( $mime_types );
				foreach( $mime_types as $key => $mime_type ) {
					$mime_types[$key]->current = false;
					if( empty( $mime_type->post_mime_type ) ) {
						$mime_types[$key]->filter = 'other';
						$mime_types[$key]->post_mime_type = __( 'N/A', 'wpsc_st' );
					} else if( $mime_type->post_mime_type == __( 'All', 'wpsc_st' ) ) {
						if( !$current_post_mime )
							$mime_types[$key]->current = true;
						$mime_types[$key]->filter = 'all';
					} else {
						$mime_types[$key]->filter = wpsc_st_format_post_mime_type_filter( $mime_type->post_mime_type );
						$mime_types[$key]->post_mime_type = wpsc_st_format_post_mime_type( $mime_type->post_mime_type, $show_mime_ext );
					}
					if( $mime_type->filter == 'all' )
						$mime_type->filter = false;
					if( $mime_type->filter )
						$mime_types[$key]->filter_url = add_query_arg( 'post_mime_type', $mime_type->filter );
					else
						$mime_types[$key]->filter_url = add_query_arg( array( 'post_type' => 'wpsc-product', 'page' => 'wpsc_st-file_downloads' ), 'edit.php' );
					if( $current_post_mime && ( $mime_type->filter == $current_post_mime ) )
						$mime_types[$key]->current = true;
					$mime_types[$key]->i = $i;
					$i++;
				}
			}
			if( $files ) {
				foreach( $files as $key => $file ) {
					$files[$key]->post_mime_type = get_post_mime_type( $file->ID );
					if( !$file->post_mime_type )
						$files[$key]->post_mime_type = __( 'N/A', 'wpsc_st' );
					$files[$key]->media_icon = wp_get_attachment_image( $file->ID, array( 80, 60 ), true );
					$author_name = get_user_by( 'id', $file->post_author );
					$parent_post = get_post( $file->post_parent );
					if( $parent_post ) {
						$files[$key]->post_parent_title = $parent_post->post_title;
					} else {
						$files[$key]->post_parent = '';
						$files[$key]->post_parent_title = __( 'Unassigned', 'wpsc_st' );
					}
					$files[$key]->post_author_name = $author_name->display_name;
					$t_time = strtotime( $file->post_date, current_time( 'timestamp' ) );
					$time = get_post_time( 'G', true, $file->ID, false );
					if( ( abs( $t_diff = time() - $time ) ) < 86400 )
						$files[$key]->post_date = sprintf( __( '%s ago' ), human_time_diff( $time ) );
					else
						$files[$key]->post_date = mysql2date( __( 'Y/m/d' ), $file->post_date );
				}
			}
		}
		include_once( 'templates/admin/wpsc-admin_st-file_downloads.php' );

	}

	function wpsc_st_default_toolkit_html_page() {

		global $wpsc_st, $wpdb;

		$tab = false;
		if( isset( $_GET['tab'] ) )
			$tab = $_GET['tab'];

		include_once( 'templates/admin/wpsc-admin_st-toolkit.php' );

	}

	function wpsc_st_html_file_downloads() {

		wpsc_st_template_header( __( 'File Downloads', 'wpsc_st' ), 'upload' );
		$action = wpsc_get_action();
		switch( $action ) {

			default:
				wpsc_st_default_file_downloads_html_page();
				break;

		}
		wpsc_st_template_footer();

	}

	function wpsc_st_html_store_status() {

		wpsc_st_template_header( __( 'Store Status', 'wpsc_st' ), 'admin' );
		wpsc_st_store_status_html_page();
		wpsc_st_template_footer();

	}

	function wpsc_st_store_status_html_page() {

		$gold_cart = wpsc_st_gold_cart_status();
		if( get_bloginfo( 'version' ) < '3.4' )
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
		else
			$theme_data = wp_get_theme();
		$theme = array( 'name' => $theme_data['Name'], 'version' => $theme_data['Version'], 'author' => $theme_data['Author'], 'author_url' => $theme_data['AuthorURI'] );
		$plugins = get_plugins();
		$plugins_option = get_option( 'active_plugins', array() );
		$active_plugins = array();
		foreach( $plugins as $plugin_path => $plugin ) {
			if( !in_array( $plugin_path, $plugins_option ) )
				continue;
			$active_plugins[] = array( 'name' => $plugin['Name'], 'version' => $plugin['Version'], 'url' => $plugin['PluginURI'], 'author' => $plugin['Author'], 'author_url' => $plugin['PluginURI'], 'raw' => print_r( $plugin, true ) );
		}
		$max_upload = round( wp_max_upload_size() / 1024 / 1024, 2 ) . 'MB';
		$max_post = (int)( ini_get( 'post_max_size' ) ) . 'MB';
		$memory_limit = (int)( ini_get( 'memory_limit' ) ) . 'MB';

		$wpsc_pages = wpsc_st_get_wpsc_pages();

		include_once( 'templates/admin/wpsc-admin_st-store_status.php' );

	}

	function wpsc_st_html_settings() {

		global $wpsc_st;

		$action = wpsc_get_action();
		wpsc_st_template_header();
		switch( $action ) {

			case 'update':
				$options = $_POST['options'];
				foreach( $options as $key => $option )
					wpsc_st_update_option( $key, $option );

				$message = __( 'Settings saved.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_options_form();
				break;

			case 'uninstall':
				wpsc_st_uninstall_form();
				break;

			default:
				wpsc_st_options_form();
				break;

		}
		wpsc_st_template_footer();

	}

	function wpsc_st_uninstall_form() {

		global $uninstall;

		$message = __( 'Uninstall complete.', 'wpsc_st' );
		$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
		echo $output;

		include( 'templates/admin/wpsc-admin_st-uninstall.php' );

	}

	function wpsc_st_options_form() {

		global $wpsc_purchlog_statuses;

		$sale_statuses = wpsc_st_add_colours_to_sale_statuses( $wpsc_purchlog_statuses );
		if( !$sale_status_background = wpsc_st_get_option( 'sale_status_background' ) ) {
			$sale_status_background = array();
			foreach( $sale_statuses as $sale_status )
				$sale_status_background[$sale_status['internalname']] = $sale_status['default_background'];
		}
		if( !$sale_status_border = wpsc_st_get_option( 'sale_status_border' ) ) {
			$sale_status_border = array();
			foreach( $sale_statuses as $sale_status )
				$sale_status_border[$sale_status['internalname']] = $sale_status['default_border'];
		}
		$options = wpsc_st_get_options();

		include( 'templates/admin/wpsc-admin_st-settings.php' );

	}

	function wpsc_st_html_toolkit() {

		global $wpdb;

		wpsc_st_template_header();
		wpsc_st_support_donate();
		$action = wpsc_get_action();
		switch( $action ) {

			case 'nuke':
				$message = __( 'Chosen WP e-Commerce details have been permanently erased from your store.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'relink-existing-preregistered-sales':
				wpsc_st_default_toolkit_html_page();
				break;

			case 'relink-pages':
				$message = __( 'Default WP e-Commerce Pages have been restored.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'fix-wpsc_version':
				if( ( wpsc_get_major_version() == '3.8' ) && ( WPSC_VERSION == '3.7' ) ) {
					update_option( 'wpsc_version', '3.7' );
					$message = __( 'WordPress option \'wpsc_version\' has been repaired.', 'wpsc_st' );
					$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				} else {
					$message = __( 'WordPress option \'wpsc_version\' did not require attention.', 'wpsc_st' );
					$output = '<div class="error settings-error"><p>' . $message . '</p></div>';
				}
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'demo':
				$message = __( 'Settings saved.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p><strong>' . $message . '</strong></p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'clear-claimed_stock':
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_claimed_stock`" );
				$message = __( 'The \'claimed stock\' table has been emptied.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			default:
				wpsc_st_default_toolkit_html_page();
				break;

		}
		wpsc_st_template_footer();

	}

	/* End of: WordPress Administration */

} else {

	/* Start of: Storefront */

	function wpsc_st_print_scripts() {

		global $wpsc_st;

		$output = '';
		$addtocart = wpsc_st_get_option( 'addtocart_label', __( 'Add To Cart', 'wpsc' ) );
		if( $addtocart <> __( 'Add To Cart', 'wpsc' ) ) {
			$output = '
<!-- Store Toolkit: Add To Cart -->
<script type="text/javascript">
var $j = jQuery.noConflict();

$j(function(){

	$j(\'input.wpsc_buy_button\').val( \'' . $addtocart . '\' );

});
</script>
';
		}
		echo $output;

	}
	add_action( 'wp_print_footer_scripts' , 'wpsc_st_print_scripts' );

	include_once( 'includes/template.php' );

	/* End of: Storefront */

}
?>