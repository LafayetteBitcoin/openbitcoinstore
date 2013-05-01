<?php

class AsssuConfig {

	public static 
		$collection = array();
	
	public 				// API variables
		$site_url, 		// string
		$access_key, 	// string
		$secret_key, 	// string
		$bucket_name, 	// string
		$bucket_subdir, // string
		$exclude,		// string - file extensions comma separated
		$use_ssl, 		// boolean
		$cron_interval, // integer
		$cron_limit, 	// integer
		$mode; 			// string - hidden_forced, optional, normal

	public
		$excludes = array(),
		$upload_basedir,
		$upload_baseurl,
		$db_prefix,
		$db_table,
		$use_predefined,
		$endpoint;
	
	public function __construct($site_url, array $options) {
		if (strpos($site_url, '://') !== false)
			$site_url = substr($site_url, strpos($site_url, '://') + 3);
		$this->site_url = trim($site_url, '/');
		$this->access_key = $options['access_key'];
		$this->secret_key = $options['secret_key'];
		$this->bucket_name = $options['bucket_name'];
		$this->bucket_subdir = $options['bucket_subdir'];
		$this->exclude = $options['exclude'];
		$this->use_ssl = $options['use_ssl'];
		$this->cron_interval = $options['cron_interval'];
		$this->cron_limit = $options['cron_limit'];
		$this->mode = $options['mode'];
		
		foreach (preg_split('/[\s,]+/', $this->exclude) as $e)
			if (!empty($e))
				$this->excludes[] = '/(.*)'.$e.'$/';
		if (!in_array('/.htaccess/', $this->excludes))
			$this->excludes[] = '/.htaccess/';

		self::$collection[$this->site_url] = $this;
	}

	public function getSafeSecretKey() {
	    if (empty($this->secret_key))
	        return '';
		return substr($this->secret_key, 0, 5).'*****'.substr($this->secret_key, -5);
	}

	public static function getConfig($site_url) {
		if (isset(self::$collection[$site_url]))
			return self::$collection[$site_url];
		return null;
	}
}

class AsssuCron {
	
	public $plugin;

	public function __construct($plugin) {
		$this->plugin = $plugin;
	}

	function hook() {
		if (!$this->plugin->enabled)
			return;

		ignore_user_abort(true);
		set_time_limit(0);
		
	    $this->check_htaccess();

		$c = $this->plugin->config;
		$adapter = $this->sss_adapter();
		$buckets = @$adapter->listBuckets();
		if (!is_array($buckets) || !in_array($c->bucket_name, $buckets))
			return;

    	list($limit, $files) = $this->find_files($c->upload_basedir, $c->cron_limit);
		foreach ($files as $file) {
			$should_upload = true;
			$status = 'error';
			$local_path = $c->upload_basedir.$file;
			$amazon_path = trim($file, '/');
		    if (strpos($amazon_path, '+') !== false) {
		    	$this->plugin->special_rewrite_db($amazon_path);
		    	$amazon_path = str_replace('+', '-', $amazon_path);
		    }
			if (!empty($c->bucket_subdir))
			    $amazon_path = $c->bucket_subdir.'/'.$amazon_path;
		    	
			$info = @$adapter->getObjectInfo($c->bucket_name, $amazon_path, true);
			if (!empty($info)) {
				if ($info['size'] !== filesize($local_path)) {
					if ($adapter->deleteObject($c->bucket_name, $amazon_path) === false)
						$should_upload = false;
				} else {
					$should_upload = false;
					$status = 'done';
				}
			}
			if ($should_upload && $adapter->putObjectFile($local_path, $c->bucket_name, $amazon_path, S3::ACL_PUBLIC_READ ) === TRUE)
				$status = 'done';
				
			if ($status === 'done')
			    unlink($local_path);
			
			print 'Processing: '.$amazon_path.' Status: '.$status.'. <br />'."\n";
		}
	}
	
	function sss_adapter() {
	    if (isset($this->adapter))
	        return $this->adapter;
	        
        $c = $this->plugin->config;
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'tpyo-amazon-s3-php-class'.DIRECTORY_SEPARATOR.'S3.php';
    	$adapter = new S3($c->access_key, $c->secret_key, $c->use_ssl, $c->endpoint);
	    $this->adapter = $adapter;
	    return $adapter;
	}
	
	function check_htaccess() {
		$c = $this->plugin->config;
	    $amazon_path = 'http://'.$c->bucket_name.'.'.$c->endpoint.'/';
        $amazon_path_ssl = 'https://'.$c->endpoint.'/'.$c->bucket_name.'/';
	    if (!empty($c->bucket_subdir)) {
	        $amazon_path .= $c->bucket_subdir.'/';
	        $amazon_path_ssl .= $c->bucket_subdir.'/';
        }
        $asw_path = plugins_url( 'asssu-special-rewrite.php' , __FILE__ );
        if ($_SERVER['HTTP_HOST'] === 'localhost')
        	$asw_path = str_replace('home/a/lib/amazon-s3-uploads/trunk', 'amazon-s3-uploads', $asw_path);
        $asw_path = substr($asw_path, strpos($asw_path, $_SERVER['HTTP_HOST']) + strlen($_SERVER['HTTP_HOST']));

	    $htaccess_file = $c->upload_basedir.DIRECTORY_SEPARATOR.'.htaccess';
	    $htaccess_contents = 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} (.+)\+(.+)
RewriteRule ^(.*)$ '.$asw_path.' [L]
RewriteCond %{HTTPS} off
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ '.$amazon_path.'$1 [QSA,L]
RewriteCond %{HTTPS} on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ '.$amazon_path_ssl.'$1 [QSA,L]';
	    if (is_file($htaccess_file))
	        $htaccess = file_get_contents($htaccess_file);
        if (!isset($htaccess) || $htaccess !== $htaccess_contents)
            file_put_contents($htaccess_file, $htaccess_contents);
        return true;
	}
	
	function find_files($path, $limit, $dir='') {
		$out = array();
	    $dir_path = $path.$dir;
	    if ($handle = opendir($dir_path)) {
            while (false !== ($entry = readdir($handle)) && $limit > 0) {
                if (!in_array($entry, array('.', '..'))) {
                    $entry_path = $dir_path.'/'.$entry;
                    if (is_file($entry_path)) {
	            		$exclude = false;
	                	foreach ($this->plugin->config->excludes as $e)
	                		if (preg_match($e, $entry))
	            				$exclude = true;
	    				if (!$exclude) {
	                    	$out[] = $dir.'/'.$entry;
	                    	$limit--;
	                    }
                    } else {
                    	list($limit, $files) = $this->find_files($path, $limit, $dir.'/'.$entry);
                        $out = array_merge($out, $files);
                    }
                }
            }
            closedir($handle);
        }
        return array($limit, $out);
	}
}

class AsssuPlugin {

	public function __construct() {
		global $wpdb;
		$this->configure($wpdb);
		$this->cron = new AsssuCron($this);
		
		register_activation_hook(plugin_basename(__FILE__), array(&$this, 'activation'));
		register_deactivation_hook(plugin_basename(__FILE__), array(&$this, 'deactivation'));
		if ($this->config->mode !== 'hidden_forced')
			add_action('admin_menu', array(&$this, 'admin_menu'));
		
		if (isset($_GET['page']) && $_GET['page'] === 'asssu-options')
			ob_start();
			
		if ($this->enabled) {
			add_action('delete_attachment', array(&$this, 'delete_attachment'));
			add_filter('cron_schedules', array(&$this, 'cron_schedules'));
		    $prefix = $this->config->db_prefix;
			if (!wp_next_scheduled($prefix.'asssu_cron_hook'))
				wp_schedule_event(time(), $prefix.'asssu_cron_schedule', $prefix.'asssu_cron_hook');
			add_action($prefix.'asssu_cron_hook', array(&$this->cron, 'hook'));
		} elseif (wp_next_scheduled($prefix.'asssu_cron_hook'))
			wp_clear_scheduled_hook($prefix.'asssu_cron_hook');
	}

	function cron_schedules($param) {
		return array($this->config->db_prefix.'asssu_cron_schedule' => array(
		    'interval' => $this->config->cron_interval, 
		    'display' => 'Amazon S3 Uploads Cron Schedule'
		));
	}

    function configure($wpdb) {
		$this->db = $wpdb;
		$this->enabled = false;

		$site_url = get_option('siteUrl');
		$site_url = trim(substr($site_url, strpos($site_url, '://') + 3), '/');
		$config = AsssuConfig::getConfig($site_url);
		
		if (!is_null($config))
			if ($config->model === 'optional' && !(bool) get_option('asssu_use_predefined', 1))
				$config = null;
			else
				$this->enabled = true;

		if (is_null($config)) {
			$config = new AsssuConfig($site_url, array(
				'access_key' => get_option('asssu_access_key'),
				'secret_key' => get_option('asssu_secret_key'),
				'bucket_name' => get_option('asssu_bucket_name'),
				'bucket_subdir' => get_option('asssu_bucket_subdir'),
				'exclude' => get_option('asssu_exclude'),
				'use_ssl' => (bool) get_option('asssu_use_ssl', 0),
				'cron_interval' => get_option('asssu_cron_interval', 300),
				'cron_limit' => get_option('asssu_cron_limit', 20),
				'mode' => 'normal'
			));

			if (get_option('asssu_enabled', 'inactive') === 'active')
				$this->enabled = true;
		}
		
		$wp_upload_dir = wp_upload_dir();
		$config->upload_basedir = $wp_upload_dir['basedir'];
		$config->upload_baseurl = $wp_upload_dir['baseurl'];
		$config->db_prefix = $wpdb->prefix;
		$config->db_table = 'asssu_endpoints';
		$config->use_predefined = (bool) get_option('asssu_use_predefined', 1);
		$this->config = $config;
		$this->config->endpoint = $this->get_endpoint();
		
		if (empty($this->config->endpoint)) {
		    $error_log = array('endpoint could not be found');
		    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'tpyo-amazon-s3-php-class'.DIRECTORY_SEPARATOR.'S3.php';
		    $adapter = new S3($config->access_key, $config->secret_key, $config->use_ssl);
		    if (empty($adapter))
		        $error_log[] = 'could not connect to S3';
		    $buckets = $adapter->listBuckets();
		    if (empty($buckets))
		        $error_log[] = 'no buckets found';
	        if (!in_array($config->bucket_name, $buckets))
		        $error_log[] = 'selected bucket not found';
	        $location = $adapter->getBucketLocation($config->bucket_name);
		    $error_log[] = 'bucket location '.$location;
		    $this->error_log($error_log);
		}
    }

	function options() {
		$c = $this->config;
		if (isset($_POST['Submit'])) {
			if (function_exists('current_user_can') && !current_user_can('manage_options'))
				die(__('Nice try...'));

	    	$this->check_db_table();
			
			if ($c->mode === 'optional' && isset($_POST['use_predefined'])) {
				update_option('asssu_use_predefined', 1);
				$msg = 'Settings saved. Plugin is active.';
			} else {
				update_option('asssu_use_predefined', 0);
				update_option('asssu_enabled', 'inactive');
				update_option('asssu_access_key', $_POST['access_key']);
				if ($_POST['secret_key'] === 'not_used')
					$_POST['secret_key'] = get_option('asssu_secret_key');
				update_option('asssu_secret_key', $_POST['secret_key']);
				update_option('asssu_bucket_name', $_POST['bucket_name']);
				update_option('asssu_bucket_subdir', trim($_POST['bucket_subdir'], '/'));
				update_option('asssu_exclude', $_POST['exclude']);
				update_option('asssu_use_ssl', isset($_POST['use_ssl']) ? 1 : 0);
				update_option('asssu_cron_interval', $_POST['cron_interval']);
				update_option('asssu_cron_limit', $_POST['cron_limit']);
				
				if ($this->check_sss($_POST['access_key'], $_POST['secret_key'], isset($_POST['use_ssl']), $_POST['bucket_name']) === false) {
					$msg = 'Connection to S3 failed. Plugin not active.';
				} else {
					update_option('asssu_enabled', 'active');
					$msg = 'Settings saved. Plugin is active.';
				}
			}
			ob_end_clean();
			wp_redirect('plugins.php?page=asssu-options&msg='.urlencode($msg));
			exit();
		}
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'asssu-options.php';
	}

	function check_sss($access_key, $secret_key, $use_ssl, $bucket_name) {
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'tpyo-amazon-s3-php-class'.DIRECTORY_SEPARATOR.'S3.php';
		$adapter = new S3($access_key, $secret_key, $use_ssl);
		$buckets = @$adapter->listBuckets();
		if (is_array($buckets) && in_array($bucket_name, $buckets)) {
			return true;
		}
		return false;
	}
	
	function get_endpoint($skip_db=false) {
	    $this->check_db_table();
		$c = $this->config;
		$endpoints = $this->db->get_results('SELECT * FROM '.$c->db_table, ARRAY_A);
		foreach ($endpoints as $e) 
			if ($e['site_url'] === $c->site_url)
				$endpoint = $e;

		if (!$skip_db && isset($endpoint) && !is_null($endpoint))
			return $endpoint['location'];
		
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'tpyo-amazon-s3-php-class'.DIRECTORY_SEPARATOR.'S3.php';
		$adapter = new S3($c->access_key, $c->secret_key, $c->use_ssl);
		$buckets = @$adapter->listBuckets();
		if (is_array($buckets) && in_array($c->bucket_name, $buckets)) {
		    $bucket_location = $adapter->getBucketLocation($c->bucket_name);
		    switch ($bucket_location) {
		        case 'US':       		$e = 's3'; break;
		        case 'us-west-2':       $e = 's3-us-west-2'; break;
		        case 'us-west-1':       $e = 's3-us-west-1'; break;
		        case 'EU':              $e = 's3-eu-west-1'; break;
		        case 'eu-west-1':       $e = 's3-eu-west-1'; break;
		        case 'ap-southeast-1':  $e = 's3-ap-southeast-1'; break;
		        case 'ap-northeast-1':  $e = 's3-ap-northeast-1'; break;
		        case 'sa-east-1':       $e = 's3-sa-east-1'; break;
		        default: 				return null;
		    }
		    $e .= '.amazonaws.com';
		    if (!isset($endpoint)) {
	    		$query = 'INSERT INTO '.$c->db_table.' (site_url, location) VALUES("'.$c->site_url.'", "'.$e.'")';
		    	$this->db->query($query);
		    } else if ($endpoint['location'] !== $e) {
		    	$query = 'UPDATE '.$c->db_table.'SET location = "'.$e.'" WHERE site_url = "'.$c->site_url.'"';
		    	$this->db->query($query);
		    }
			return $e;
		}
		return null;
	}

	public function delete_attachment($post_id) {
		$c = $this->config;
		$files = array();
		
		$query = 'SELECT * FROM '.$c->db_prefix.'postmeta WHERE post_id  = "'.$post_id.'"';
		$results = $this->db->get_results($query, ARRAY_A);
		foreach ($results as $result) {
			// finding all file sizes
			if ($result['meta_key'] === '_wp_attachment_metadata') {
				$meta_value = unserialize($result['meta_value']);
				$files[] = $meta_value['file'];
				$dir = substr($meta_value['file'], 0, strrpos($meta_value['file'], '/') + 1);
				foreach ($meta_value['sizes'] as $size)
					$files[] = $dir.$size['file'];
			}
		}
		
	    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'tpyo-amazon-s3-php-class'.DIRECTORY_SEPARATOR.'S3.php';
		$adapter = $this->cron->sss_adapter();
		foreach (array_unique($files) as $amazon_path) {
			if (!empty($c->bucket_subdir))
			    $amazon_path = $c->bucket_subdir.'/'.$amazon_path;
			$info = $adapter->getObjectInfo($c->bucket_name, $amazon_path, true);
			if (!empty($info))
				$delete = $adapter->deleteObject($c->bucket_name, $amazon_path);
		}
	}
	
	function special_rewrite_db($file) {
		$c = $this->config;
		$files = array();
		
		// db search
		$new_file = $file;
		$new_file = substr($new_file, strrpos($new_file, '/') + 1); // removing directories
		$new_file = preg_replace('/-(\d+)x(\d+)./', '.', $new_file); // removing thumbnail size
		$new_file = substr($new_file, 0, strrpos($new_file, '.')); // removing extension
		$query = 'SELECT * FROM '.$c->db_prefix.'postmeta WHERE meta_value like \'%'.$new_file.'%\'';
		$results = $this->db->get_results($query, ARRAY_A);
		foreach ($results as $result) {
			// finding all file sizes
			if ($result['meta_key'] === '_wp_attachment_metadata') {
				$meta_value = unserialize($result['meta_value']);
				$files[] = $meta_value['file'];
				$dir = substr($meta_value['file'], 0, strrpos($meta_value['file'], '/') + 1);
				foreach ($meta_value['sizes'] as $size)
					$files[] = $dir.$size['file'];
			}
			// updating db, replacing + with - in filename
			$result['meta_value'] = str_replace($new_file, str_replace('+', '-', $new_file), $result['meta_value']);
			$query = 'UPDATE '.$c->db_prefix.'postmeta SET meta_value = "'.addslashes($result['meta_value']).'" WHERE meta_id = "'.$result['meta_id'].'"';
			$this->db->query($query);
		}
		return $files;
	}
	
	function special_rewrite($request_file) {
		$c = $this->config;
		
		// db search
		$files = $this->special_rewrite_db($request_file);
		
		// amazon s3 search
	    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'tpyo-amazon-s3-php-class'.DIRECTORY_SEPARATOR.'S3.php';
		$adapter = $this->cron->sss_adapter();
		foreach (array_unique($files) as $amazon_path) {
			if (!empty($c->bucket_subdir))
			    $amazon_path = $c->bucket_subdir.'/'.$amazon_path;
			    
			$info = $adapter->getObjectInfo($c->bucket_name, $amazon_path, true);
			if (!empty($info)) {
				// updating amazon s3, replacing + with - in filename
				$new_amazon_path = str_replace('+', '-', $amazon_path);
				$check = $adapter->getObjectInfo($c->bucket_name, $new_amazon_path, true);
				if (!empty($check))
					$delete = $adapter->deleteObject($c->bucket_name, $new_amazon_path);
				$copy = $adapter->copyObject($c->bucket_name, $amazon_path, $c->bucket_name, $new_amazon_path, S3::ACL_PUBLIC_READ);
				$delete = $adapter->deleteObject($c->bucket_name, $amazon_path);
				if (!$copy)
					$this->error_log('unable to copy '.$amazon_path.' to '.$new_amazon_path);
				if (!$delete)
					$this->error_log('unable to delete '.$amazon_path);
			} else {
				$this->error_log('unable to get info for '.$amazon_path);
			}
		}
		header('Location: '.str_replace('+', '-', $request_file));
		exit();
	}
	
	function check_db_table() {
		$columns = @$this->db->get_results('SHOW COLUMNS FROM `'.$this->config->db_table.'`', ARRAY_A);
		if ($columns !== false) {
		    $site_url = false;
		    $location = false;
		    foreach ($columns as $k => $v)
		        if ($v['Field'] === 'site_url')
		            $site_url = true;
	            else if ($v['Field'] === 'location')
		            $location = true;
            if (!$site_url || !$location)
                $this->db->query('DROP TABLE `'.$this->config->db_table.'`');
		}
		$query = 'CREATE TABLE IF NOT EXISTS `'.$this->config->db_table.'` (
		  `id` varchar(32) NOT NULL,
		  `site_url` varchar(255) NOT NULL,
		  `location` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM;';
		$this->db->query($query);
	}

	function activation() {

	}

	function deactivation() {
	
	}

	function admin_menu() {
		if (function_exists('add_plugins_page')) {
			add_plugins_page(
			    __('Amazon S3 Uploads'), 
			    __('Amazon S3 Uploads'), 
			    'manage_options', 
			    'asssu-options', 
			    array(&$this, 'options')
			);
		}
	}
	
	function error_log($message) {
		if (is_string($message))
			$message = array($message);
		$error_log = '';
		$error_log_file = dirname(__FILE__).'/asssu-errorlog.txt';
		if (is_file($error_log_file))
			$error_log = file_get_contents($error_log_file);
		$error_log .= implode("\n", $message);
	    if (file_put_contents($error_log_file, $error_log) !== true)
	        error_log(implode("\n", $message));
	}
}
