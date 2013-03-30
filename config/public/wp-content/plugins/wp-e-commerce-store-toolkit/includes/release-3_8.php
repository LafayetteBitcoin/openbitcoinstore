<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration menu */
	function wpsc_st_admin_menu() {

		add_options_page( __( 'Store Toolkit for WP e-Commerce', 'wpsc_st' ), __( 'Store Toolkit', 'wpsc_st' ), 'manage_options', 'wpsc_st', 'wpsc_st_html_settings' );

	}
	add_action( 'admin_menu', 'wpsc_st_admin_menu' );

	function wpsc_st_add_modules_admin_pages( $page_hooks, $base_page ) {

		$page_hooks[] = add_submenu_page( $base_page, __( 'Store Toolkit', 'wpsc_st' ), __( 'Store Toolkit', 'wpsc_st' ), 'manage_options', 'wpsc_st-toolkit', 'wpsc_st_html_toolkit' );
		$page_hooks[] = add_submenu_page( $base_page, __( 'File Downloads', 'wpsc_st' ), __( 'File Downloads', 'wpsc_st' ), 'manage_options', 'wpsc_st-file_downloads', 'wpsc_st_html_file_downloads' );
		$page_hooks[] = add_submenu_page( $base_page, __( 'Store Status', 'wpsc_st' ), __( 'Store Status', 'wpsc_st' ), 'manage_options', 'wpsc_st-store_status', 'wpsc_st_html_store_status' );
		return $page_hooks;

	}
	add_filter( 'wpsc_additional_pages', 'wpsc_st_add_modules_admin_pages', 10, 2 );

	function wpsc_st_manage_purchase_logs_status_css( $hook ) {

		global $wpsc_purchlog_statuses;

		/* Manage Sale */
		$page = 'dashboard_page_wpsc-purchase-logs';
		if( $page == $hook ) {
			$sale_statuses = wpsc_st_add_colours_to_sale_statuses( $wpsc_purchlog_statuses );
			$sale_status_background = get_option( 'wpsc_st_sale_status_background' );
			$sale_status_border = get_option( 'wpsc_st_sale_status_border' );

			$output = '';
			if( $wpsc_purchlog_statuses ) {
				$output = '
<!-- Store Toolkit: Sale Status Indicator -->
<style type="text/css">
';
				foreach( $sale_statuses as $status ) {
					$output .=  '/* ' . $status['label'] . ' */
.dashboard_page_wpsc-purchase-logs td.status_ind .status-' . $status['order'] . ' {
	background-color:#' . $sale_status_background[$status['internalname']] . ';
	border-color:#' . $sale_status_border[$status['internalname']] . ';
}
';
				}
				$output .= '</style>
';
			}
			echo $output;
		}

	}
	if( get_option( 'wpsc_st_enable_sale_status', 1 ) )
		add_action( 'admin_enqueue_scripts', 'wpsc_st_manage_purchase_logs_status_css' );

	function wpsc_st_manage_purchase_logs_status_column( $columns ) {

		$temp_columns = array();
		$temp_columns['status_ind'] = '<span>' . __( 'Status Indicator', 'wpsc' ) . '</span>';
		foreach( $columns as $key => $column ) {
			$temp_columns[$key] = $column;
		}
		$columns = $temp_columns;
		return $columns;

	}
	add_filter( 'manage_dashboard_page_wpsc-purchase-logs_columns', 'wpsc_st_manage_purchase_logs_status_column', 10, 1 );

	function wpsc_manage_purchase_logs_gateway_column( $columns ) {

		$columns['gateway'] = __( 'Payment Method', 'wpsc_st' );
		return $columns;

	}
	add_filter( 'manage_dashboard_page_wpsc-purchase-logs_columns', 'wpsc_manage_purchase_logs_gateway_column', 9, 1 );

	function wpsc_manage_purchase_logs_session_id_column( $columns ) {

		$columns['session_id'] = __( 'Session ID', 'wpsc_st' );
		return $columns;

	}
	add_filter( 'manage_dashboard_page_wpsc-purchase-logs_columns', 'wpsc_manage_purchase_logs_session_id_column', 9, 1 );

	function wpsc_manage_purchase_logs_ip_address_column( $columns ) {

		$columns['ip_address'] = __( 'IP Address', 'wpsc_st' );
		return $columns;

	}
	add_filter( 'manage_dashboard_page_wpsc-purchase-logs_columns', 'wpsc_manage_purchase_logs_ip_address_column', 9, 1 );

	function wpsc_manage_purchase_logs_status_ind_cell( $default = null, $column_name = null, $item = null ) {

		$output = $default;
		if( $column_name == 'status_ind' ) {
			$output = '';
			if( isset( $item->status ) && $item->status ) {
				$output = "<div class='status status-" . $item->status . "'>&nbsp;</div>";
			}
		}
		return $output;

	}
	add_filter( 'wpsc_manage_purchase_logs_custom_column', 'wpsc_manage_purchase_logs_status_ind_cell', 10, 3 );

	function wpsc_manage_purchase_logs_gateway_cell( $default = null, $column_name = null, $item = null ) {

		$output = $default;
		if( $column_name == 'gateway' ) {
			$output = '';
			if( isset( $item->id ) && $item->id )
				$output .= '<attr title="">' . wpsc_st_get_payment_method( $item->id ) . '</attr>';
		}
		return $output;

	}
	add_filter( 'wpsc_manage_purchase_logs_custom_column', 'wpsc_manage_purchase_logs_gateway_cell', 9, 3 );

	function wpsc_manage_purchase_logs_session_id_cell( $default = null, $column_name = null, $item = null ) {

		$output = $default;
		if( $column_name == 'session_id' ) {
			$output = '';
			if( isset( $item->id ) && $item->id )
				$output .= wpsc_st_get_session_id( $item->id );
		}
		return $output;

	}
	add_filter( 'wpsc_manage_purchase_logs_custom_column', 'wpsc_manage_purchase_logs_session_id_cell', 9, 3 );

	function wpsc_manage_purchase_logs_ip_address_cell( $default = null, $column_name = null, $item = null ) {

		$output = $default;
		if( $column_name == 'ip_address' ) {
			$output = '';
			if( isset( $item->id ) && $item->id )
				$output .= wpsc_st_get_ip_address( $item->id );
		}
		return $output;

	}
	add_filter( 'wpsc_manage_purchase_logs_custom_column', 'wpsc_manage_purchase_logs_ip_address_cell', 9, 3 );

	function wpsc_st_manage_purchase_logs_actions_button( $default = null, $column_name = null, $item = null ) {

		$output = $default;
		if( $column_name == 'actions' ) {
			if( isset( $item->id ) && $item->id ) {
				/* View */
				$output .= sprintf( '<a href="%s" class="button-primary"><abbr title="' . __( 'View Sale', 'wpsc_st' ) . '">' . __( 'View', 'wpsc_st' ) . '</abbr></a>', add_query_arg( array( 'c' => 'item_details', 'id' => $item->id ) ) );
				/* Delete */
				$output .= sprintf( '<a href="%s" class="button" onclick="%s"><abbr title="' . __( 'Delete Sale', 'wpsc_st' ) . '">' . __( 'Delete', 'wpsc_st' ) . '</abbr></a>', wp_nonce_url( add_query_arg( array( 'wpsc_admin_action' => 'delete_purchlog', 'purchlog_id' => $item->id ), 'admin.php' ), 'delete_purchlog_' . $item->id ), "if( confirm( '" . esc_js( sprintf( __( "Are you sure you want to delete this Sale (#%s)?", "wpsc" ), $item->id ) ) . "') ) { return true; } return false;" );
				/* Resend */
				$output .= sprintf( '<a href="%s" class="button" onclick="%s"><abbr title="' . __( 'Resend Sale', 'wpsc_st' ) . '">' . __( 'Resend', 'wpsc_st' ) . '</abbr></a>', add_query_arg( array( 'c' => 'item_details', 'id' => $item->id, 'email_buyer_id' => $item->id ) ), "if( confirm( '" . esc_js( sprintf( __( "Are you sure you want to resend the receipt for this Sale (#%s)?", "wpsc" ), $item->id ) ) . "') ) { return true; } return false;" );
			}
		}
		return $output;

	}
	add_filter( 'wpsc_manage_purchase_logs_actions_column', 'wpsc_st_manage_purchase_logs_actions_button', 9, 3 );

	function wpsc_st_sale_file_downloads() {

		if( wpsc_get_minor_version() >= '3.8.8' )
			$purchase_id = $_GET['id'];
		else
			$purchase_id = $_GET['purchaselog_id'];
		if( $purchase_id ) {

			if( file_exists( STYLESHEETPATH . '/wpsc-admin_st-sales_file_downloads_metabox.php' ) )
				include( STYLESHEETPATH . '/wpsc-admin_st-sales_file_downloads_metabox.php' );
			else
				include( $wpsc_oc['abspath'] . '/templates/admin/' . $template );

		}

	}
	$wpsc_version = get_option( 'wpsc_version' );
	if( $wpsc_version >= '3.8.9.5' )
		add_action( 'wpsc_purchlogitem_metabox_end', 'wpsc_st_sale_file_downloads', 10, 1 );

	function wpsc_st_sale_action_reset_downloads() {

		global $wpsc_st;

		if( wpsc_get_minor_version() >= '3.8.8' )
			$purchase_id = $_GET['id'];
		else
			$purchase_id = $_GET['purchaselog_id'];
		$url = '';
		switch( wpsc_get_major_version() ) {

			case '3.8':
				$url = add_query_arg( 'wpsc_admin_action', 'reset-file-downloads-sale' );
				break;

		}
		$output = '<img src="' . plugins_url( 'templates/admin/images/pencil.png', $wpsc_st['relpath'] ) . '" alt="pencil icon" />&ensp;<a href="' . $url . '">' . __( "Reset File Downloads Counter", "wpsc_st" ). '</a><br /><br class="small" />';
		echo $output;

	}
	add_action( 'wpsc_purchlogitem_links_start', 'wpsc_st_sale_action_reset_downloads' );

	function wpsc_st_meta_boxes_admin_init() {

		/* All in One SEO Pack */
		add_action( 'add_meta_boxes', 'wpsc_st_aioseop_meta_boxes' );
		add_filter( 'wpsc_products_page_forms', 'wpsc_st_aioseop_add_to_product_form' );

		/* Cost Price */
		add_action( 'add_meta_boxes', 'wpsc_st_costprice_meta_boxes' );
		add_filter( 'wpsc_products_page_forms', 'wpsc_st_costprice_add_to_product_form' );

	}
	add_action( 'admin_init', 'wpsc_st_meta_boxes_admin_init' );

	function wpsc_st_aioseop_meta_boxes() {

		$pagename = 'wpsc-product';
		add_meta_box( 'wpsc_st_aioseop_meta_box', __( 'All in One SEO Pack', 'wpsc_st' ), 'wpsc_st_aioseop_meta_box', $pagename, 'normal', 'default' );

	}

	function wpsc_st_aioseop_add_to_product_form( $order ) {

		if( array_search( 'wpsc_st_aioseop_meta_box', (array)$order['side'] ) === false )
			$order['side'][] = 'wpsc_st_aioseop_meta_box';

		return $order;

	}

	function wpsc_st_costprice_meta_boxes() {

		$pagename = 'wpsc-product';
		add_meta_box( 'wpsc_st_costprice_meta_box', __( 'Cost Price', 'wpsc_st' ), 'wpsc_st_costprice_meta_box', $pagename, 'side', 'default' );

	}

	function wpsc_st_costprice_add_to_product_form( $order ) {

		if( array_search( 'wpsc_st_costprice_meta_box', (array)$order['side'] ) === false )
			$order['side'][] = 'wpsc_st_costprice_meta_box';

		return $order;

	}

	function wpsc_st_export_product_fields( $fields ) {

		$fields[] = array(
			'name' => 'cost_price',
			'label' => __( 'Cost Price', 'wpsc_ce' ),
			'default' => 0
		);
		return $fields;

	}
	add_filter( 'wpsc_ce_product_fields', 'wpsc_st_export_product_fields' );

	function wpsc_st_export_product_data( $product ) {

		$product->cost_price = get_product_meta( $product->ID, 'cost_price', true );
		return $product;

	}
	add_filter( 'wpsc_ce_product_data', 'wpsc_st_export_product_data' );

	function wpsc_st_aioseop_meta_box() {

		global $post, $wpdb, $closed_postboxes, $wpsc_st;

		$aioseop_enabled = true;
		if( !function_exists( 'aioseop_get_version' ) ) {
			$aioseop_enabled = false;
			$link = 'http://wordpress.org/extend/plugins/all-in-one-seo-pack/';
			$message = sprintf( __( 'To enter All in One SEO Pack details you must install and activate the <a href="%s" target="_blank">All in One SEO Pack</a> (via WordPres Extend) Plugin.', 'wpsc_st' ), $link );
			$output = '<div class="error-message"><p>' . $message . '</p></div>';
			echo $output;
		}

		$title = get_post_meta( $post->ID, '_aioseop_title', true );
		$description = get_post_meta( $post->ID, '_aioseop_description', true );
		$keywords = get_post_meta( $post->ID, '_aioseop_keywords', true );
		$title_atr = get_post_meta( $post->ID, '_aioseop_titleatr', true );
		$menu_label = get_post_meta( $post->ID, '_aioseop_menulabel', true );

		include_once( $wpsc_st['abspath'] . '/templates/admin/wpsc-admin_st_aioseop_38.php' );

	}

	function wpsc_st_costprice_meta_box() {

		global $post, $wpdb, $closed_postboxes, $wpsc_st;

		$cost_price = get_post_meta( $post->ID, '_wpsc_cost_price', true );

		include_once( $wpsc_st['abspath'] . '/templates/admin/wpsc-admin_st_costprice_38.php' );

	}

	function wpsc_st_dashboard_setup() {

		global $wpsc_st;

		if( current_user_can( 'manage_options' ) && defined( 'WPSC_VERSION' ) ) {
			wp_add_dashboard_widget( 'wpsc_st-right_now', __( 'Right Now in Store', 'wpsc_st' ), 'wpsc_st_right_now_widget' );
			wp_enqueue_style( 'wpsc_st-right-now_styles', plugins_url( 'templates/admin/wpsc-admin_dashboard_st-right_now.css', $wpsc_st['relpath'] ) );
		}

	}
	add_action( 'wp_dashboard_setup', 'wpsc_st_dashboard_setup' );

	function wpsc_st_right_now_widget() {

		global $wpsc_st, $wpdb;

		$plugin_path = $wpsc_st['abspath'];

		/* Catalogue */

		/* Products */
		$products = 0;
		$post_type = 'wpsc-product';
		$count = wp_count_posts( $post_type );
		if( $count ) {
			if( is_object( $count ) ) {
				unset( $count->inherit );
				foreach( $count as $value )
					$products += $value;
			}
			unset( $count );
			$products = number_format( $products );
		}

		/* Variations */
		$variations = 0;
		$post_type = 'wpsc-variation';
		$count = wp_count_posts( $post_type );
		if( $count ) {
			if( is_object( $count ) ) {
				foreach( $count as $value )
					$variations += $value;
			}
			unset( $count );
			$variations = number_format( $variations );
		}

		/* Categories */
		$categories = 0;
		$term_taxonomy = 'wpsc_product_category';
		$categories = number_format( wp_count_terms( $term_taxonomy ) );

		/* Tags */
		$tags = 0;
		$term_taxonomy = 'product_tag';
		$tags = number_format( wp_count_terms( $term_taxonomy ) );

		/* Coupons */
		$coupons = 0;
		$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_coupon_codes`";
		$coupons = number_format( $wpdb->get_var( $count_sql ) );

		/* Attributes */
		if( function_exists( 'wpsc_cf_check_options_exist' ) ) {
			$attributes = 0;
			$attributes = get_option( 'wpsc_cf_data' );
			if( $attributes )
				$attributes = count( maybe_unserialize( $attributes ) );
		}

		/* Sales */

		$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_purchase_logs`";
		$sales_overall = number_format( $wpdb->get_var( $count_sql ) );

		$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE processed IN ('3','4','5')";
		$sales_approved = number_format( $wpdb->get_var( $count_sql ) );

		$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE processed IN ('2')";
		$sales_pending = number_format( $wpdb->get_var( $count_sql ) );

		$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE processed IN ('5')";
		$sales_declined = number_format( $wpdb->get_var( $count_sql ) );

		if( function_exists( 'wpsc_pp_purchlog_statuses' ) ) {
			$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE processed IN ('6')";
			$sales_refunded = number_format( $wpdb->get_var( $count_sql ) );
		}

		include_once( $plugin_path . '/templates/admin/wpsc-admin_dashboard_st-right_now.php' );

	}

	/* WordPress Administration menu */
	function wpsc_st_wpsc_admin_html_page() {

		global $wpsc_st;

		$pages = wpsc_st_get_pages();

		$product_list_url = get_option( 'product_list_url' );
		$checkout_url = get_option( 'checkout_url' );
		$shopping_cart_url = get_option( 'shopping_cart_url' );
		$transact_url = get_option( 'transact_url' );
		$user_account_url = get_option( 'user_account_url' );

		include_once( $wpsc_st['abspath'] . '/templates/admin/wpsc-admin_store_st-permalinks.php' );

	}
	add_action( 'wpsc_admin_settings_page', 'wpsc_st_wpsc_admin_html_page' );

	function wpsc_st_sales_session_id() {

		if( wpsc_get_minor_version() >= '3.8.8' )
			$purchase_id = $_GET['id'];
		else
			$purchase_id = $_GET['purchaselog_id'];
		$session_id = '-';
		if( $purchase_id )
			$session_id = wpsc_st_get_session_id( $purchase_id ); ?>

<form method="post" action="">
	<p>
		<label for="session_id"><strong><?php _e( 'Session ID', 'wpsc_pi' ); ?>:</strong></label>
		<input type="text" id="session_id" name="session_id" value="<?php echo $session_id; ?>" />
		<input type="hidden" name="purchase_id" value="<?php echo $purchase_id; ?>" />
		<input type="submit" id="button" name="button" value="<?php _e( 'Save', 'wpsc_pi' ); ?>" class="button" />
	</p>
	<input type="hidden" name="wpsc_admin_action" value="wpsc_update_session_id" />
</form>
<?php
	}
	add_action( 'wpsc_billing_details_bottom', 'wpsc_st_sales_session_id' );

	function wpsc_st_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			/* WP e-Commerce */

			case 'products':
				$post_type = 'wpsc-product';
				$count = wp_count_posts( $post_type );
				break;

			case 'variations':
				$post_type = 'wpsc-variation';
				$count = wp_count_posts( $post_type );
				break;

			case 'images':
				$post_type = 'wpsc-product';
				$products = (array)get_posts( array(
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				$count = 0;
				if( $products ) {
					foreach( $products as $product ) {
						$args = array(
							'post_type' => 'attachment',
							'post_parent' => $product->ID,
							'post_status' => 'inherit',
							'post_mime_type' => 'image',
							'numberposts' => -1
						);
						$images = get_children( $args );
						if( $images )
							$count = $count + count( $images );
					}
				}
				break;

			case 'files':
				$post_type = 'wpsc-product-file';
				$count = wp_count_posts( $post_type );
				break;

			case 'categories':
				$term_taxonomy = 'wpsc_product_category';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'tags':
				$term_taxonomy = 'product_tag';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'orders':
				$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_purchase_logs`";
				break;

			case 'coupons':
				$count_sql = "SELECT COUNT(`id`) FROM `" . $wpdb->prefix . "wpsc_coupon_codes`";
				break;

			/* 3rd Party */

			case 'wishlist':
				$post_type = 'wpsc-wishlist';
				$count = wp_count_posts( $post_type );
				break;

			case 'enquiries':
				$post_type = 'wpsc-enquiry';
				$count = wp_count_posts( $post_type );
				break;

			case 'credit-cards':
				$post_type = 'offline_payment';
				$count = wp_count_posts( $post_type );
				break;

			case 'custom-fields':
				$custom_fields = get_option( 'wpsc_cf_data' );
				if( $custom_fields )
					$count = count( maybe_unserialize( $custom_fields ) );
				else
					$count = 0;
				break;

			case 'preview-files':
				$post_type = 'wpsc-preview-file';
				$count = wp_count_posts( $post_type );
				break;

			/* WordPress */

			case 'posts':
				$post_type = 'post';
				$count = wp_count_posts( $post_type );
				break;

			case 'post_categories':
				$term_taxonomy = 'category';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'post_tags':
				$term_taxonomy = 'post_tag';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'links':
				$count_sql = "SELECT COUNT(`link_id`) FROM `" . $wpdb->prefix . "links`";
				break;

			case 'comments':
				$count = wp_count_comments();
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) ) {
				if( is_object( $count ) ) {
					$count_object = $count;
					$count = 0;
					foreach( $count_object as $key => $item )
						$count = $item + $count;
				}
				return $count;
			} else {
				$count = $wpdb->get_var( $count_sql );
			}
			return $count;
		} else {
			return 0;
		}

	}

	function wpsc_st_clear_dataset( $dataset, $data = null ) {

		global $wpdb;

		switch( $dataset ) {

			/* WP e-Commerce */

			case 'products':
				$post_type = 'wpsc-product';
				$products = (array)get_posts( array(
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $products ) {
					foreach( $products as $product ) {
						if( $product->ID )
							wp_delete_post( $product->ID, true );
					}
				}
				break;

			case 'variations':
				/* Products */
				$post_type = 'wpsc-product';
				$variations_sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_type` = '" . $post_type . "' AND `post_parent` <> 0";
				$variations = $wpdb->get_results( $variations_sql );
				if( $variations ) {
					foreach( $variations as $variation ) {
						if( $variation->ID )
							wp_delete_post( $variation->ID, true );
					}
				}
				/* Terms */
				$term_taxonomy = 'wpsc-variation';
				$variations = get_terms( $term_taxonomy, array( 'hide_empty' => false ) );
				if( $variations ) {
					foreach( $variations as $variation ) {
						if( $variation->term_id ) {
							wp_delete_term( $variation->term_id, $term_taxonomy );
							$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = %d", $variation->term_id ) );
						}
					}
				}
				delete_option( 'wpsc-variation_children' );
				break;

			case 'categories':
				$term_taxonomy = 'wpsc_product_category';
				if( $data ) {
					foreach( $data as $single_category ) {
						$post_type = 'wpsc-product';
						$args = array(
							'post_type' => $post_type,
							'tax_query' => array(
								array(
									'taxonomy' => $term_taxonomy,
									'field' => 'id',
									'terms' => $single_category
								)
							),
							'numberposts' => -1
						);
						$products = get_posts( $args );
						if( $products ) {
							foreach( $products as $product ) {
								if( $product->ID )
									wp_delete_post( $product->ID, true );
							}
						}
					}
				} else {
					$categories = get_terms( $term_taxonomy, array( 'hide_empty' => false ) );
					if( $categories ) {
						foreach( $categories as $category ) {
							if( $category->term_id ) {
								wp_delete_term( $category->term_id, $term_taxonomy );
								$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = %d", $category->term_id ) );
							}
							if( $category->term_taxonomy_id )
								$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->term_relationships . "` WHERE `term_taxonomy_id` = %d", $category->term_taxonomy_id ) );
						}
					}
					$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "wpsc_meta` WHERE `object_type` = 'wpsc_category'" );
					$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->term_taxonomy . "` WHERE `taxonomy` = '%s'", $term_taxonomy ) );
				}
				break;

			case 'tags':
				$term_taxonomy = 'product_tag';
				$tags = get_terms( $term_taxonomy, array( 'hide_empty' => false ) );
				if( $tags ) {
					foreach( $tags as $tag ) {
						if( $tag->term_id ) {
							wp_delete_term( $tag->term_id, $term_taxonomy );
							$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = %d", $tag->term_id ) );
						}
					}
				}
				break;

			case 'images':
				$post_type = 'wpsc-product';
				$products = (array)get_posts( array(
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $products ) {
					$upload_dir = wp_upload_dir();
					foreach( $products as $product ) {
						$args = array(
							'post_type' => 'attachment',
							'post_parent' => $product->ID,
							'post_status' => 'inherit',
							'post_mime_type' => 'image',
							'numberposts' => -1
						);
						$images = get_children( $args );
						if( $images ) {
							// $intermediate_sizes = wpsc_intermediate_image_sizes_advanced( $intermediate_sizes );
							foreach( $images as $image ) {
								wp_delete_attachment( $image->ID, true );
/*
								$image->filepath = dirname( $upload_dir['basedir'] . '/' . get_post_meta( $image->ID, '_wp_attached_file', true ) );
								chdir( $image->filepath );
								$image->filename = basename( get_post_meta( $image->ID, '_wp_attached_file', true ) );
								$image->extension = strrchr( $image->filename, '.' );
								$image->filebase = wpsc_st_remove_filename_extension( $image->filename );
								foreach( $intermediate_sizes as $intermediate_size ) {
									if( file_exists( $image->filebase . '-' . $intermediate_size['width'] . 'x' . $intermediate_size['height'] . $image->extension ) )
										@unlink( $image->filebase . '-' . $intermediate_size['width'] . 'x' . $intermediate_size['height'] . $image->extension );
								}
								if( file_exists( $image->filename ) )
									@unlink( basename( $image->filename ) );
								wp_delete_post( $image->ID );
*/
							}
							unset( $images, $image );
						}
					}
				}
				break;

			case 'files':
				$post_type = 'wpsc-product-file';
				$files = (array)get_posts( array(
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $files ) {
					foreach( $files as $file ) {
						if( $file->ID )
							wp_delete_post( $file->ID, true );
					}
				}
				break;

			case 'orders':
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_purchase_logs`" );
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_cart_contents`" );
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_submited_form_data`" );
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_download_status`" );
				$wpdb->query( "DELETE FROM `" . $wpdb->prefix . "wpsc_meta` WHERE `object_type` = 'wpsc_cart_item'" );
				break;

			case 'coupons':
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_coupon_codes`" );
				break;

			case 'wpsc_pages':
				$wpsc_pages = array(
					'[productspage]',
					'[shoppingcart]',
					'[transactionresults]',
					'[userlog]',
					'[download-manager]',
					'[order-tracking]'
				);
				$size = count( $wpsc_pages );
				for( $i = 0; $i < $sizes; $i++ ) {
					if( $wpsc_pages[$i] ) {
						$post_id = wpsc_st_get_page_by_shortcode( $wpsc_pages[$i] );
						if( $post_id )
							wp_delete_post( $wishlist->ID, true );
					}
				}
				break;

			case 'wpsc_options':
				$options = array();
				$wpec_options_sql = "SELECT `option_name` FROM  `" . $wpdb->prefix . "options` WHERE  `option_name` LIKE  'wpec_%'";
				$wpec_options = $wpdb->get_results( $wpec_options_sql );
				if( $wpec_options ) {
					foreach( $wpec_options as $wpec_option )
						$options[] = $wpec_option;
					// $wpdb->query( "DELETE FROM `" . $wpdb->prefix . "options` WHERE `option_name` LIKE 'wpec_%'" );
				}
				$wpsc_options_sql = "SELECT `option_name` FROM  `" . $wpdb->prefix . "options` WHERE  `option_name` LIKE  'wpsc_%'";
				$wpsc_options = $wpdb->get_results( $wpsc_options_sql );
				if( $wpsc_options ) {
					foreach( $wpsc_options as $wpsc_option )
						$options[] = $wpsc_option;
					// $wpdb->query( "DELETE FROM `" . $wpdb->prefix . "options` WHERE `option_name` LIKE 'wpsc_%'" );
				}
				break;

			/* 3rd Party */

			case 'wishlist':
				$post_type = 'wpsc-wishlist';
				$wishlists = (array)get_posts( array( 
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $wishlists ) {
					foreach( $wishlists as $wishlist ) {
						if( isset( $wishlist->ID ) )
							wp_delete_post( $wishlist->ID, true );
					}
				}
				break;

			case 'enquiries':
				$post_type = 'wpsc-enquiry';
				$enquiries = (array)get_posts( array( 
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $enquiries ) {
					foreach( $enquiries as $enquiry ) {
						if( isset( $enquiry->ID ) )
							wp_delete_post( $enquiry->ID, true );
					}
				}
				break;

			case 'credit-cards':
				$post_type = 'offline_payment';
				$credit_cards = (array)get_posts( array( 
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $credit_cards ) {
					foreach( $credit_cards as $credit_card ) {
						if( isset( $credit_card->ID ) )
							wp_delete_post( $credit_card->ID, true );
					}
				}
				break;

			case 'custom-fields':
				delete_option( 'wpsc_cf_data' );
				break;

			case 'preview-files':
				$post_type = 'wpsc-preview-file';
				$preview_files = (array)get_posts( array( 
					'post_type' => $post_type,
					'post_status' => 'inherit',
					'numberposts' => -1
				) );
				if( $preview_files ) {
					foreach( $preview_files as $preview_file ) {
						if( isset( $preview_file->ID ) )
							wp_delete_post( $preview_file->ID, true );
					}
				}
				break;

			/* WordPress */

			case 'posts':
				$post_type = 'post';
				$posts = (array)get_posts( array( 
					'post_type' => $post_type,
					'post_status' => wpsc_st_post_statuses(),
					'numberposts' => -1
				) );
				if( $posts ) {
					foreach( $posts as $post ) {
						if( isset( $post->ID ) )
							wp_delete_post( $post->ID, true );
					}
				}
				break;

			case 'post_categories':
				$term_taxonomy = 'category';
				$post_categories = get_terms( $term_taxonomy, array( 'hide_empty' => false ) );
				if( $post_categories ) {
					foreach( $post_categories as $post_category ) {
						if( $post_category->term_id ) {
							wp_delete_term( $post_category->term_id, $term_taxonomy );
							$wpdb->query( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = " . $post_category->term_id );
						}
						if( $post_category->term_taxonomy_id )
							$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->term_relationships . "` WHERE `term_taxonomy_id` = %d", $post_category->term_taxonomy_id ) );
					}
				}
				$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->term_taxonomy . "` WHERE `taxonomy` = '%s'", $term_taxonomy ) );
				break;

			case 'post_tags':
				$term_taxonomy = 'post_tag';
				$post_tags = get_terms( $term_taxonomy, array( 'hide_empty' => false ) );
				if( $post_tags ) {
					foreach( $post_tags as $post_tag ) {
						if( $post_tag->term_id ) {
							wp_delete_term( $post_tag->term_id, $term_taxonomy );
							$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->terms . "` WHERE `term_id` = %d", $post_tag->term_id ) );
						}
						if( $post_tag->term_taxonomy_id )
							$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->term_relationships . "` WHERE `term_taxonomy_id` = %d", $post_tag->term_taxonomy_id ) );
					}
				}
				$wpdb->query( $wpdb->prepare( "DELETE FROM `" . $wpdb->term_taxonomy . "` WHERE `taxonomy` = '%s'", $term_taxonomy ) );
				break;

			case 'links':
				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "links`" );
				break;

			case 'comments':
				$comments = get_comments();
				if( $comments ) {
					foreach( $comments as $comment ) {
						if( $comment->comment_ID )
							wp_delete_comment( $comment->comment_ID, true );
					}
				}
				break;

		}

	}

	function wpsc_st_pd_create_product_addons( $product, $import ) {

		if( isset( $product->aioseop_keywords ) || isset( $product->aioseop_description ) || isset( $product->aioseop_title ) || isset( $product->aioseop_titleatr ) || isset( $product->aioseop_menulabel ) ) {
			if( !isset( $product->aioseop_keywords ) )
				$product->aioseop_keywords = '';
			if( !isset( $product->aioseop_description ) )
				$product->aioseop_description = '';
			if( !isset( $product->aioseop_title ) )
				$product->aioseop_title = '';
			if( !isset( $product->aioseop_titleatr ) )
				$product->aioseop_titleatr = '';
			if( !isset( $product->aioseop_menulabel ) )
				$product->aioseop_menulabel = '';
			update_post_meta( $product->ID, '_aioseop_keywords', $product->aioseop_keywords );
			update_post_meta( $product->ID, '_aioseop_description', $product->aioseop_description );
			update_post_meta( $product->ID, '_aioseop_title', $product->aioseop_title );
			update_post_meta( $product->ID, '_aioseop_titleatr', $product->aioseop_titleatr );
			update_post_meta( $product->ID, '_aioseop_menulabel', $product->aioseop_menulabel );
		}
		return $product;

	}
	add_filter( 'wpsc_pd_create_product_addons', 'wpsc_st_pd_create_product_addons', null, 2 );

	function wpsc_st_pd_merge_product_addons( $product, $import, $product_data ) {

		if( isset( $product->aioseop_keywords ) && $product->aioseop_keywords || isset( $product->aioseop_description ) && $product->aioseop_description || isset( $product->aioseop_title ) && $product->aioseop_title || isset( $product->aioseop_titleatr ) && $product->aioseop_titleatr || isset( $product->aioseop_menulabel ) && $product->aioseop_menulabel ) {
			if( isset( $product->aioseop_keywords ) && $product->aioseop_keywords ) {
				if( $product_data->aioseop_keywords <> $product->aioseop_keywords ) {
					update_post_meta( $product->ID, '_aioseop_keywords', $product->aioseop_keywords );
					$product->updated = true;
				}
			}
			if( isset( $product->aioseop_description ) && $product->aioseop_description ) {
				if( $product_data->aioseop_description <> $product->aioseop_description ) {
					update_post_meta( $product->ID, '_aioseop_description', $product->aioseop_description );
					$product->updated = true;
				}
			}
			if( isset( $product->aioseop_title ) && $product->aioseop_title ) {
				if( $product_data->aioseop_title <> $product->aioseop_title ) {
					update_post_meta( $product->ID, '_aioseop_title', $product->aioseop_title );
					$product->updated = true;
				}
			}
			if( isset( $product->aioseop_titleatr ) && $product->aioseop_titleatr ) {
				if( $product_data->aioseop_titleatr <> $product->aioseop_titleatr ) {
					update_post_meta( $product->ID, '_aioseop_titleatr', $product->aioseop_titleatr );
					$product->updated = true;
				}
			}
			if( isset( $product->aioseop_menulabel ) && $product->aioseop_menulabel ) {
				if( $product_data->aioseop_menulabel <> $product->aioseop_menulabel ) {
					update_post_meta( $product->ID, '_aioseop_menulabel', $product->aioseop_menulabel );
					$product->updated = true;
				}
			}
		}
		return $product;

	}
	add_filter( 'wpsc_pd_merge_product_addons', 'wpsc_st_pd_merge_product_addons', null, 3 );

	function wpsc_st_get_session_id( $purchase_id ) {

		global $wpdb;

		$output = '';
		if( $purchase_id ) {
			$session_id_sql = $wpdb->prepare( "SELECT `sessionid` FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE `id` = %d LIMIT 1", $purchase_id );
			$session_id = $wpdb->get_var( $session_id_sql );
			if( $session_id )
				$output = $session_id;
		}
		return $output;

	}

	function wpsc_st_get_ip_address( $purchase_id ) {

		global $wpdb;

		$output = '-';
		if( $purchase_id ) {
			$ip_address = wpsc_get_meta( $purchase_id, 'ip_address', 'purchase_log' );
			if( $ip_address )
				$output = $ip_address;
		}
		return $output;

	}

	function wpsc_st_sales_ip_address() {

		$output = '';
		$ip_address = '-';
		if( wpsc_get_minor_version() >= '3.8.8' )
			$purchase_id = $_GET['id'];
		else
			$purchase_id = $_GET['purchaselog_id'];
		if( $purchase_id ) {
			$ip_address = wpsc_st_get_ip_address( $purchase_id );
			if( !$ip_address )
				$ip_address = '-';
		}
		$output = '<p><strong>' . __( 'IP Address: ', 'wpsc_st' ) . '</strong>' . $ip_address . '</p>';
		echo $output;

	}
	add_action( 'wpsc_billing_details_bottom', 'wpsc_st_sales_ip_address' );

	function wpsc_st_admin_product_category_form() {

		echo '111';

	}
	//add_action( 'wpsc_product_category_edit_form_fields', 'wpsc_st_admin_product_category_form', null, 11 );

	/* End of: WordPress Administration */

} else {

	/* Start of: Storefront */

	function wpsc_st_save_ip_address( $purchase_log_object, $session_id, $display_to_screen ) {

		if( $session_id ) {
			$purchase_id = wpsc_st_get_purchase_id( $session_id );
			if( $purchase_id )
				wpsc_update_meta( $purchase_id, 'ip_address', wpsc_st_capture_ip_address(), 'purchase_log' );

		}

	}
	add_action( 'wpsc_transaction_results_shutdown', 'wpsc_st_save_ip_address', 10, 3 );

	/* End of: Storefront */

}

/* Start of: Common */

function wpsc_st_get_purchase_id( $session_id ) {

	global $wpdb;

	$output = '';
	if( $session_id ) {
		$purchase_id_sql = $wpdb->prepare( "SELECT `id` FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE `sessionid` = '%s' LIMIT 1", $session_id );
		$purchase_id = $wpdb->get_var( $purchase_id_sql );
		$output = $purchase_id;
	}
	return $output;

}

function wpsc_st_fix_switch_themes() {

	wpsc_flush_theme_transients( true );

}
add_action( 'after_switch_theme', 'wpsc_st_fix_switch_themes', 11 );

/* End of: Common */
?>