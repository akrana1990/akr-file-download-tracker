<?php
/**
 * Created by PhpStorm.
 * User: ANKIT
 * Date: 6/17/2015
 * Time: 10:52 PM
 */

global $wpdb;
$table_name = $wpdb->prefix . "file_downloader";
$sql="DROP TABLE $table_name";
$wpdb->query($sql);


delete_option('afdt_email_options');
