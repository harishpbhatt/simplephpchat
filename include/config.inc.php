<?php
	//...........Local Settings.................
	include_once("mysql_database_functions.php");

	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
	//ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
	ini_set("error_reporting", E_ALL & ~E_DEPRECATED & ~E_NOTICE);
	//..........database connection............
	//...........define site name & important variables.............
	$SITE_NAME="Domain Market";
	$NEW_LINE = "
";

	$ADMIN_URL=$SITE_URL."cms/";

	$Admintitle="Web Management | Administrator";
	$SITE_TITLE="Web Management | ";
?>
