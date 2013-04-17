<?php
/*
Plugin Name: Amazon S3 Uploads
Plugin URI: http://wordpress.org/extend/plugins/amazon-s3-uploads/
Author: Artem Titkov
Author URI: https://profiles.google.com/117859515361389646005
Description: Moves your uploads to Amazon S3 via cron jobs.
Version: 1.09
*/

require_once dirname(__FILE__).'/asssu-models.php';
$asssu = new AsssuPlugin();
