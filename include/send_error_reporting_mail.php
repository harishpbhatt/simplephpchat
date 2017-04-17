<?php
	include_once("connect.php");
	$error_text = $_REQUEST["error_text"];
	$query_text = $_REQUEST["query_text"];
	hb_send_error_reporting_mail($error_text,$query_text);
?>