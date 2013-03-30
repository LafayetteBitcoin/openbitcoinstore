<?php
/**
 * This file supports formatting and outputting of styled emails
 */


//TODO: support WPEC subjects being modified slightly by testing for presence of subjects, rather than exact matches

global $ecse_email_content;
global $ecse_email_subject;
global $ecse_email_type;



/* TEMPLATE TAGS FOR THE STYLE TEMPLATES */

/**
 * Returns the content of the email as formatted by WP E-Commerce.
 * @return String as generated by WP e-Commerce
 */
function ecse_get_email_content() {
	global $ecse_email_content;
	if(!is_string($ecse_email_content)) $ecse_email_content=''; //just to make sure it's a string
	return $ecse_email_content;
}

/**
 * Returns the subject of the email as formatted by WP E-Commerce.
 * @return String as generated by WP e-Commerce
 */
function ecse_get_email_subject() {
	global $ecse_email_subject;
	if(!is_string($ecse_email_subject)) $ecse_email_subject=''; //just to make sure it's a string
	return $ecse_email_subject;
}


/* TEMPLATE TAGS FOR THE CONTENT TEMPLATES */

function ecse_get_the_product_list() {
	return ECSE_mail::get_product_rows_part();
}

function ecse_get_the_totals() {
	return ECSE_mail::get_totals_part();
}

function ecse_get_the_addresses() {
	return ECSE_mail::get_address_part();
}



/* CONDITIONAL TAGS FOR USE IN ANY EMAIL TEMPLATE */

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_purchase_report_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject==__( 'Purchase Report', 'wpsc' ));
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_purchase_receipt_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject==__( 'Purchase Receipt', 'wpsc' ));
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_order_pending_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject==__( 'Order Pending', 'wpsc' ));
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_order_pending_payment_required_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject==__( 'Order Pending: Payment Required', 'wpsc' ));
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_tracking_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject==get_option( 'wpsc_trackingid_subject' ));
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_unlocked_file_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject==__( 'The administrator has unlocked your file', 'wpsc' ));
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_out_of_stock_email() {
	global $ecse_email_subject;
	//this is a bit more involved than the rest, because WPEC uses a wildcard in the internationalisation
	$needle = __('%s is out of stock', 'wpsc');
	$needle = str_replace('%s ', '', $needle);
	$haystack = 'x'.$ecse_email_subject;
	return (stripos($haystack, $needle)>0);
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_test_email() {
	global $ecse_email_subject;
	return ($ecse_email_subject=='ECSE test email');
}

/**
 * Conditional tag
 * @return bool
 */
function ecse_is_other_email() {
	global $ecse_email_type;
	return ( isset($ecse_email_type) && ($ecse_email_type == 'other email') ) ;
}

























class ECSE_mail {
	
	
	/* GLOBAL SETTERS */
	// Use at beginning of application of style, before style template heirarchy calls.
	
	static function set_content($content) {
		global $ecse_email_content;
		$ecse_email_content = $content;
	}
	
	static function set_subject($subject) {
		global $ecse_email_subject;
		$ecse_email_subject = $subject;
	}
	
	/**
	 * Set the email type. Type is only used to check for 'other email', the catch-all.
	 */
	static function set_type_other() {
		global $ecse_email_type;
		$ecse_email_type = 'other email';
	}
	
	
	
	/* INTELLIGENCE */
	
	/**
	 * Decide whether or not to modify mail, given an email subject and recipient
	 * @param string $subject
	 * @param string $to (email address)
	 * @return bool
	 */
	static function should_modify_mail($subject,$to) {
		
		$returner = false;
		
		if(!self::is_excempt($subject)) {
		
			if(get_option('ecse_is_active')) {
				$user_can_receive = true;
			} else {
				$user_can_receive = false;
				//but wait, admin recipients CAN be styled!
				require_once(ABSPATH . WPINC . '/registration.php');
				$to_user_ID = email_exists($to);
				if( $to_user_ID && user_can($to_user_ID,'activate_plugins') ) $user_can_receive = true;
			}
		
			switch($subject) {
				case __( 'Purchase Report', 'wpsc' ):
				case __( 'Purchase Receipt', 'wpsc' ):
				case __( 'Order Pending', 'wpsc' ):
				case __( 'Order Pending: Payment Required', 'wpsc' ):
				case get_option( 'wpsc_trackingid_subject' ):
				case __( 'The administrator has unlocked your file', 'wpsc' ):
		
					if($user_can_receive) $returner=true;
					break;
		
				case 'ECSE test email':
		
					//even with styling turned off, test emails can be sent to anyone, not just admins
					$returner=true;
					break;
		
				default:
					if($user_can_receive && get_option('ecse_is_other_active') ) $returner=true;
			}
		
		}
		
		return $returner;
	}

	/**
	 * Determine whether subject line is in the excempt list
	 * @param string $subject
	 * @return bool
	 */
	static function is_excempt($subject) {
		$my_list = get_option('ecse_subjects_to_ignore');
		$returner = false;
		if(!empty($my_list)) {
			$my_list = unserialize($my_list);
			$my_list[] = 'ECSE plugin wish'; //never style plugin wishes.
			foreach($my_list as $row) {
				if( trim($subject) == trim($row) ) $returner=true;
			}
		}
		return $returner;
	}
	
	
	
	/* TEMPLATE ACCESS */
	
	/**
	 * Output the style template, based on the template heirarchy.
	 * Requires the global setters be used first, so that email type can be determined.
	 * If no style template file can be found, outputs an empty string.
	 * @return none.
	 */
	static function the_style_part() {
		/*
		 *
		* 															wpsc-email_style_unlocked_file.php
		* 															wpsc-email_style_tracking.php
		* 															wpsc-email_style_order_pending_payment_required.php
		* 			wpsc-email_style_out_of_stock.php				wpsc-email_style_order_pending.php
		* 			wpsc-email_style_purchase_report.php			wpsc-email_style_purchase_receipt.php
		*
		* 						|												|
		*
		* 			wpsc-email_style_manager.php					wpsc-email_style_customer.php
		* 															(includes any uncaught emails such as "test" and "other")
		*
		* 									\							/
		*
		* 										wpsc-email_style.php
		*
		*/
	
		$base = 'wpsc';
		$name = 'email_style';
		$pref = $base.'-'.$name;
		$suf = '';
	
	
		//first, manager email templates
		if(ecse_is_purchase_report_email()) {
			if( ecse_is_purchase_report_email() && (locate_template($pref.'_purchase_report.php')!='') ) {
				$suf = '_purchase_report';
			} elseif( ecse_is_out_of_stock_email() && (locate_template($pref.'_out_of_stock.php')!='') ) {
				$suf = '_out_of_stock';
				
				//fallback manager
			} elseif( locate_template($pref.'_manager.php')!='' ) {
				$suf = '_manager';
			}
	
				
			//next, customer/public email templates
		} else {
			if(ecse_is_purchase_receipt_email() && (locate_template($pref.'_purchase_receipt.php')!='') ) {
				$suf = '_purchase_receipt';
			} elseif(ecse_is_order_pending_email() && (locate_template($pref.'_purchase_order_pending.php')!='')) {
				$suf = '_order_pending';
			} elseif(ecse_is_order_pending_payment_required_email() && (locate_template($pref.'_purchase_order_pending_payment_required.php')!='')) {
				$suf = '_order_pending_payment_required';
			} elseif(ecse_is_tracking_email() && (locate_template($pref.'_purchase_tracking.php')!='')) {
				$suf = '_tracking';
			} elseif(ecse_is_unlocked_file_email() && (locate_template($pref.'_unlocked_file.php')!='')) {
				$suf = '_unlocked_file';
	
				//fallback customer, including test and all other emails
			} elseif( locate_template($pref.'_customer.php')!='' ) {
				$suf = '_customer';
			}
		}
	
		//general fallback goes to wpsc-email_style.php template because $suf is empty
	
		get_template_part($base,$name.$suf);
	}
	
	/**
	 * Get replacement content for a type of WPEC email
	 * @param string $type {'receipt'}
	 * @param int $purch_id
	 * @return string (html) or (empty)
	 */
	static function get_content_part($type, $purch_id) {
		
		$returner='';
		require_once 'purchase.class.php';
		
		switch($type) {
			case 'receipt':
				
				if( ECSE_purchase::get_purchase($purch_id)!=null ) {
					ob_start();
					get_template_part('wpsc','email_content-receipt'); //the template page should output some stuff, and probably include some uses of the ECSE_purchase class.
					$returner = ob_get_clean();
				}
				
		}
		
		return $returner;
	}
	
	/**
	 * Output result of same call on get_content_part().
	 * If no content replacement can be found, outputs an empty string.
	 * @param string $type
	 * @param int $purch_id
	 */
	static function the_content_part($type,$purch_id) {
		echo self::get_content_part($type, $purch_id);
	}
	
	/**
	 * To be used within purchase transaction emails to get the product rows.
	 * @return string html the product rows
	 */
	static function get_product_rows_part() {
		
		ob_start();
		
		while(ECSE_purchase::have_products()) { ECSE_purchase::the_product();
			get_template_part('wpsc','email_content_part-product_row'); //the theme file
		}
		
		return ob_get_clean();
	}
	
	/**
	 * To be used within purchase transaction emails to get the totals.
	 * @return string html the totals
	 */
	static function get_totals_part() {
	
		ob_start();
	
		get_template_part('wpsc','email_content_part-totals'); //the theme file
	
		return ob_get_clean();
	}
	
	/**
	 * To be used within purchase transaction emails to get the shipping & billing addresses.
	 * @return string html the addresses
	 */
	static function get_address_part() {
	
		ob_start();
	
		get_template_part('wpsc','email_content_part-addresses'); //the theme file
	
		return ob_get_clean();
	}
	
	
	
	
	
	/* STYLE & CONTENT APPLICATION */
	
	/**
	 * Filter. Modify the variables of an email, to add style wrapping and convert to HTML.
	 * Only modifies the email if a style template is found. Content template is optional.
	 * Note that content replacement functionality in this plugin is not enough to convert the email to HTML, this function is needed to modify the email's header etc.
	 * Used by self::modify_mail_operation(), which applies rules before deciding to apply style.
	 * Used during wp_mail filter.
	 * @param $vars - refer to the wp_mail filter
	 * @return (array) refer to the wp_mail filter
	 */
	static function filter_for_style($vars) {
		extract($vars);
			
		ECSE_mail::set_content($message);
		ECSE_mail::set_subject($subject);
		
		//content replacement
		if( (ecse_is_purchase_receipt_email() || ecse_is_order_pending_email() || ecse_is_order_pending_payment_required_email() ) && (locate_template('wpsc-email_content-receipt.php')!='') ) {
			$purch_id = self::read_pid_tag($message);
			if($purch_id!=0) {
				$replacement_content = ECSE_mail::get_content_part('receipt', $purch_id);
				if(!empty($replacement_content)) ECSE_mail::set_content($replacement_content);
			}
		}
	
		//keep line-breaks in wp-e-commerce & wordpress content when moving to HTML (not applicable when using content replacement)
		if( !isset($replacement_content) && ( (stripos($message,'<br') + stripos($message,'<body') + stripos($message,'<table') + stripos($message,'<div')) ==false) ) {
			// There is no HTML or BR linebreaks, so probably originally a plain-text content.
			// Let's convert all plain-text linebreaks to html linebreaks.
			// We needed to check, because we don't want to double-linebreak the email!
			ECSE_mail::set_content( str_ireplace("\n",'<br />',$message) ); 
		}
		
		//do the styling
		ob_start();
		self::the_style_part(); //the template file should output some stuff, and probabaly include a call to ecse_get_email_content()
		$message_formatted = ob_get_clean();
		if(!empty($message_formatted)) {
			$message=$message_formatted;
			//TODO: make sure content-type isn't already specified in the header. Right now, I don't think e-commerce sets a content-type header.
			$ecse_charset = get_option('ecse_charset'); //not sure if anyone still uses this. Legacy since fixed charset mangling.
			if(empty($ecse_charset)) $ecse_charset = get_bloginfo( 'charset' ); //by default, WP assigns the charset of the blog to the emails. Not sure we need to do this. Not even sure anyone would want to override it.
			if(empty($ecse_charset)) $ecse_charset = 'UTF-8';
			$headers .= "Content-Type: text/html; charset=".$ecse_charset.";\r\n";
			if(ecse_is_test_email() || ecse_is_other_email()) { //set the 'from' to be same as WPEC emails. Note that this can still be overridden by plugins and WP itself, like in user registrations.
				$reply_address = get_option( 'return_email' );
				$reply_name = get_option( 'return_name' );
				if(!empty($reply_address) && !empty($reply_name)) $headers .= "from: ".$reply_name." <".$reply_address.">\r\n";
			}
		}
	
		//clean up to reduce opportunities for data leakage outside the formatting template
		ECSE_mail::set_content(''); 
		ECSE_mail::set_subject('');

		return compact( 'to', 'subject', 'message', 'headers', 'attachments' );
	}
	
	/**
	 * Filter that adds a temporary tag, storing the purchase ID for use in content rewriting.
	 * @param string $message The body of the email
	 * @param string $report_id The report_id snippet generated by WPEC during transactions, or a purchase number on it's own as a string
	 * @return string the filtered message body with HTML-commented pid inserted
	 */
	static function add_pid_tag($message,$report_id) {
		
		//narrow down the purchase ID from the report_id string
		$purch_id = intval(preg_replace("/[^0-9]/", '', $report_id));
	
		$message = '<!-- !ecse! '.$purch_id.' !ecse! -->'.$message;
	
		return $message;
	}
	
	/**
	 * Extract the purchase ID from the pid tag
	 * @param unknown_type $message
	 * @return int purchase_id (or) 0 if not found
	 */
	static function read_pid_tag($message) {
		$pid_start = stripos($message, '<!-- !ecse!') + 12;
		$pid_end = stripos($message, '!ecse! -->', $purch_id_start+1) - 1;
		$id = substr($message, $pid_start, $pid_end-$pid_start);
		return intval(preg_replace("/[^0-9]/", '', $id));
	}
	
	/**
	 * Filter that strips the temporary tag added with add_pid_tag().
	 * Used when we've added our temporary tag but aren't applying style
	 * @param unknown_type $message
	 */
	static function strip_pid_tag($message) {
		$pid_start = stripos($message, '<!-- !ecse!');
		$pid_end = stripos($message, '!ecse! -->', $pid_start+1) + 9;
		if(stripos($message, '!ecse! -->')>0) $message = substr_replace($message, '', $pid_start, $pid_end-$pid_start);
		return $message;
	}
	
	
	
	/* CONTROL */
	
	/**
	 * Decide whether or not to style emails. Apply style if so. Should only be called by the wp_mail filter.
	 * @param array $vars - refer to the wp_mail filter
	 * @return array - refer to the wp_mail filter
	 */
	static function modify_mail_operation($vars) {
		extract($vars);
	
		if(self::should_modify_mail($subject, $to)) {
			//just so we know if it's an "other" email.
			switch($subject) {
				case __( 'Purchase Report', 'wpsc' ):
				case __( 'Purchase Receipt', 'wpsc' ):
				case __( 'Order Pending', 'wpsc' ):
				case __( 'Order Pending: Payment Required', 'wpsc' ):
				case get_option( 'wpsc_trackingid_subject' ):
				case __( 'The administrator has unlocked your file', 'wpsc' ):
				case 'ECSE test email':
					break;
				default:
					self::set_type_other();
			}
			//Apply the style!
			extract(self::filter_for_style($vars));
		} 
		
		$message = self::strip_pid_tag($message);
	
		return compact( 'to', 'subject', 'message', 'headers', 'attachments' );
	}
	
	/**
	 * Output a preview email. Used in the options page via page load.
	 * @param array $options containing any or all of the defaults to be overridden
	 * @return none
	 */
	static function render_preview($options = array()) {
		require_once 'purchase.class.php';
		$pid = ECSE_purchase::get_recent_purchase_id();
		$defaults = array(
				'to'			=> '',
				'subject'		=> 'ECSE test email',
				'message'		=> self::add_pid_tag('[email content as generated]', $pid),
				'headers'		=> '',
				'attachments'	=> ''
		);
		$vars = array_merge($defaults,$options);
	
		if($subject=='other email') self::set_type_other();
	
		//apply style
		$vars = self::filter_for_style($vars);
		
		//remove the pid tag added during the 'wpsc_email_message' filter
		//not needed for the browser preview, because even if the styling didn't happen, browsers still hide HTML comments
		//$vars['message'] = ECSE_mail::strip_pid_tag($vars['message']);
		
		echo $vars['message'];
	
	}
	
}






?>