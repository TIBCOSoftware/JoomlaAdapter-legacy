<?php
  /* @copyright Copyright © 2013, TIBCO Software Inc. All rights reserved.
 * @license GNU General Public License version 2; see LICENSE.txt
 */
?>
<?php

/**
 * Exports selected database tables and builds a Joomla extension installer
 * for TIBCO's OpenAPI.
 */
/*****************************************************
 * Configuration - Please update to suit your system *
 *****************************************************/

// path to MySQL bin directory
$mysql_path = '/usr/local/zend/mysql/bin';

// path to your mysql socket, if needed, otherwise set to empty string
$mysql_socket = '/usr/local/zend/mysql/tmp/mysql.sock'; 

// mysql host
$db_host	= 'localhost';

// mysql user
$db_user	= 'joomla';

// mysql password
$db_pass	= 'joomla';

// mysql database name to export from
$db_name	= 'asg_openapi2';

// Table prefix used in your Joomla installation
$db_pref	= 'openapi_';

// Joomla openAPI data package installer version number
$version 		= '1';

// path to the sql-scripts directory in your checked-pout version of the portal from the ASG SVN repo
$path			= '/Users/jmccandl/svn/portal/sql-scripts';


/*****************************************************
 * End Configuration - Do not modify below this line *
 *****************************************************/

// Made tmp folder same as base folder now
$sql_file 		= $path . '/install.mysql.utf8.sql';
$zip_file		= $path . "/file_openapi_db_v{$version}.zip";

if(file_exists($sql_file)) {
	exec('rm ' . $sql_file);
}

// tables to export
$tables 		= "{$db_pref}assets {$db_pref}categories {$db_pref}content {$db_pref}email_templates {$db_pref}extensions {$db_pref}js_ip_2_country {$db_pref}js_res_categories {$db_pref}js_res_category_filters {$db_pref}js_res_category_user {$db_pref}js_res_country {$db_pref}js_res_fields {$db_pref}js_res_fields_group {$db_pref}js_res_field_geo {$db_pref}js_res_field_multilevelselect {$db_pref}js_res_field_stepaccess {$db_pref}js_res_field_telephone {$db_pref}js_res_files {$db_pref}js_res_import {$db_pref}js_res_import_rows {$db_pref}js_res_moderators {$db_pref}js_res_record {$db_pref}js_res_record_category {$db_pref}js_res_record_values {$db_pref}js_res_sales {$db_pref}js_res_sections {$db_pref}js_res_subscribe {$db_pref}js_res_subscribe_cat {$db_pref}js_res_tags {$db_pref}js_res_tags_history {$db_pref}js_res_types {$db_pref}js_res_user_options {$db_pref}js_res_user_post_map {$db_pref}menu {$db_pref}menu_types {$db_pref}modules {$db_pref}modules_menu {$db_pref}schemas {$db_pref}usergroups {$db_pref}users {$db_pref}user_notes {$db_pref}user_profiles {$db_pref}user_usergroup_map {$db_pref}viewlevels";

// setup command for mysql export
$dump_command 	= "{$mysql_path}/mysqldump -u {$db_user} -p{$db_pass}";
if($mysql_socket != '') {
	// if using a mysql socket, add it to the command options
	$dump_command = $dump_command . " --socket={$mysql_socket}";
}
$dump_command = $dump_command . " {$db_name} {$tables} > {$sql_file}";

// Create mysql dump file
echo 'Executing dump command:' . $dump_command . '<br/>';
exec($dump_command);

// prepare sql file format required by the Joomla installer
// Replace the Joomla prefix with '#__' needed by the Joomla installer
$dump_file = file_get_contents($sql_file);
// write installer version to a tmp file
file_put_contents($sql_file . '.tmp', str_replace($db_pref,'#__',$dump_file));
unset($dump_file);

// Create the Joomla Installer
copy($path . '/file_openapi_db.zip', $zip_file);
$zip = new ZipArchive;
if ($zip->open($zip_file) === TRUE) {
	// add the tmp sql file to the archive under the correct name
	$zip->addFile($sql_file . '.tmp', '/admin/sql/install.mysql.utf8.sql');
	$zip->close();
	// remove the tmp sql file
	exec('rm ' . $sql_file . '.tmp');
} else {
	echo "<br/>Problem adding sql dump to zip archive.<br/>";
	// remove archive
	unlink($zip_file);
	// exit if there was a problem with the archiving, so as not to confuse the user with a success message
	exit;
}

// removed cleanup so as to keep the .sql file separate, in case merges are needed

echo "<br/>Joomla install package {$zip_file} created successfully.";