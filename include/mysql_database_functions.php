<?php

	$document_root = $_SERVER['DOCUMENT_ROOT'];
	$HOST = $_SERVER['HTTP_HOST'];

	if($HOST == 'server' || $HOST == '192.168.0.2' || $HOST == 'admin-pc')
	{
		// Will be used in local server
		$main_var_path = $document_root."/kitsolutions/vopeit/include/";
		$site_path = $document_root."/kitsolutions/vopeit/";
	}
	else if($HOST == 'www.paradiseinfosoft.com' || $HOST == 'paradiseinfosoft.com' || $HOST == 'demo.paradiseinfosoft.com')
	{
		// Will be used in 'www.idealgrowthclients.com' server
		$main_var_path = $document_root."/demo/vorpit/include/";
		$site_path = $document_root."/demo/vorpit/";
	}
	else if($HOST == 'www.madniinfoway.com' || $HOST == 'madniinfoway.com' || $HOST == 'demo.madniinfoway.com')
	{
		// Will be used in 'www.idealgrowthclients.com' server
		$main_var_path = $document_root."/demo/vorpit/include/";
		$site_path = $document_root."/demo/vorpit/";
	}
	else if($HOST == '67.212.186.170')
	{
		// Will be used in 'www.idealgrowthclients.com' server
		$main_var_path = "/home/paradise/public_html/demo/vorpit/include/";
		$site_path = "/home/paradise/public_html/demo/vorpit/";
	}
	else if($HOST == '119.18.55.52')
	{
		// Will be used in 'www.idealgrowthclients.com' server
		$main_var_path = "/home1/paradis1/public_html/demo/vorpit/include/";
		$site_path = "/home1/paradis1/public_html/demo/vorpit/";
	}
	else if($HOST == '216.12.194.26')
	{
		// Will be used in 'www.idealgrowthclients.com' server
		$main_var_path = "/home2/madniinf/public_html/demo/vorpit/include/";
		$site_path = "/home2/madniinf/public_html/demo/vorpit/";
	}
	else if($HOST == 'www.joinsocialbubble.com' || $HOST == 'joinsocialbubble.com')
	{
		// Will be used in 'www.idealgrowthclients.com' server
		$main_var_path = "/home/socialeventbunew/public_html/include/";
		$site_path = "/home/socialeventbunew/public_html/";
	}
	else
	{
		// Will be used in 'live' server
		$main_var_path = $document_root."/meteor/vopeit/include/";
		$site_path = $document_root."/meteor/vopeit/";
	}
	if(file_exists($main_var_path."main_vars.php"))
	{
		include_once($main_var_path."main_vars.php");
	}
	else
	{
		echo "Couldnt find ".$main_var_path."main_vars.php";
	}

	$global_run_queries = true;
	$GBV_MONITOR_MAILS = false;
	$GBV_MAILS_ENABLED = true;
	$GBV_STOP_AFTER_MAIL_SENT = false;
	$error_notification_mail = "hbinfosoft@gmail.com";
	$vorpit_administration_mail = "hbinfosoft@gmail.com";
	$send_dberror_mail = "no_reply@dberror.com";

	$remote_addr = $_SERVER['REMOTE_ADDR'];
	$server_addr = $_SERVER['SERVER_ADDR'];
	$can_show_secure_data = false;
	if($remote_addr == '27.54.180.228' || $server_addr = '192.168.0.2')
	{
		$can_show_secure_data = true;
	}
	else
	{
		// Do nothing
	}

	/* Here make a connection with mysql and select a database */
	$db = hb_mysql_connect($DBSERVER, $USERNAME, $PASSWORD);
 	hb_mysql_select_db($DATABASENAME,$db);

	function hb_send_mail3($to,$subject,$mail_body,$from,$cc="")
	{
		// Here I want to monitor all the mail that this site sends.
		// So fetch the global variable $monitor_mails here
		global $GBV_MONITOR_MAILS;
		global $GBV_MAILS_ENABLED;
		global $GBV_STOP_AFTER_MAIL_SENT;
		if($GBV_MONITOR_MAILS == true)
		{
			echo "<br/>= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = ";
			echo "<br/>From		:	$from";
			echo "<br/>To		:	$to";
			echo "<br/>Subject	:	$subject";
			echo "<br/>Body		:	$mail_body";
			echo "<br/>= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = ";
		}
		$limite = "_parties_".md5 (uniqid (rand()));
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: $from\r\n";
		if($cc)
			$headers .= "Cc: $cc\r\n";

		if($GBV_MAILS_ENABLED == true)
		{
			//mail($to,$subject,$mail_body,$headers) or die("there seems some problem in your server mail settings, pelase contact administrator.");
			//return mail($to,$subject,$mail_body,$headers);
		}
		if($GBV_STOP_AFTER_MAIL_SENT == true)
		{
			exit;
		}
	}
	function hb_send_mail_with_attachment($filename, $path, $mailto, $subject, $message, $from_mail) {

	// Here I want to monitor all the mail that this site sends.
		// So fetch the global variable $monitor_mails here
		global $GBV_MONITOR_MAILS;
		global $GBV_MAILS_ENABLED;
		global $GBV_STOP_AFTER_MAIL_SENT;
		if($GBV_MONITOR_MAILS == true)
		{
			echo "<br/>= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = ";
			echo "<br/>From		:	$from";
			echo "<br/>To		:	$to";
			echo "<br/>Subject	:	$subject";
			echo "<br/>Body		:	$mail_body";
			echo "<br/>File Name		:	$filename";
			echo "<br/>File Path		:	$path";
			echo "<br/>= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = ";
		}

		$file = $path.$filename;
		$file_size = filesize($file);
		$handle = fopen($file, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		$content = chunk_explode(base64_encode($content));
		$uid = md5(uniqid(time()));
		$name = basename($file);
		$header = "From: ".$from_mail." <".$from_mail.">\r\n";
		$header .= "Reply-To: ".$from_mail."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $message."\r\n\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$header .= $content."\r\n\r\n";
		$header .= "--".$uid."--";

		if($GBV_MAILS_ENABLED == true)
		{
			//mail($mailto, $subject, "", $header) or die("there seems some problem in your server mail settings, pelase contact administrator.");//return mail($to,$subject,$mail_body,$headers);
		}
		if($GBV_STOP_AFTER_MAIL_SENT == true)
		{
			exit;
		}
	}
	function hb_mysql_connect($database_server,$database_username,$database_password)
	{
		global $global_show_errors;
		global $global_show_queries;
		global $global_record_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		$database_connection = @mysql_connect($database_server, $database_username, $database_password);
		if(gettype($database_connection) == "resource")
		{
			return $database_connection;
		}
		else if($global_show_full_errors == true)
		{
			/*hb_show_mysql_error_full_details("Couldn't connect with database, There might have some errors in your arguments to mysql_connect().","<span style=\"color:blue;font-weight:bold;\">Database Server:</span> $database_server <br /><span style=\"color:blue;font-weight:bold;\">Database Username:</span> $database_username <br /><span style=\"color:blue;font-weight:bold;\">Database Password:</span> $database_password");*/
			mysql_connect($database_server, $database_username, $database_password);
			echo "<br/>";
			hb_show_mysql_error_full_details("Couldn't connect with database, There might have some errors in your arguments to mysql_connect()","For security reasons we have not listed your arguments here, please contact your administrator.");
		}
		else if($global_show_errors == true)
		{
			mysql_connect($database_server, $database_username, $database_password);
			echo "<br/>";
			hb_show_mysql_error_details("Couldn't connect with database, There might have some errors in your arguments to mysql_connect()","For security reasons we have not listed your arguments here, please contact your administrator.");
		}
		else
		{
			// This else block is strictly used for recording purpose, but since we were not able to connect with database we are not able to record this error, so instead just show it.
			hb_send_error_reporting_mail($mysql_error,"Connect Database");
			die("Couldn't connect with database.");
		}
	}
	function hb_mysql_insert_id()
	{
		return mysql_insert_id();
	}
	function hb_mysql_select_db($database_name,$database_connection)
	{
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		$database_selection = mysql_select_db($database_name,$database_connection);
		if(gettype($database_selection) == "boolean" && $database_selection == "1")
		{
			return $database_selection;
		}
		else if($global_show_full_errors == true)
		{
			/*hb_show_mysql_error_full_details("Couldn't connect with database, There might have some errors in your arguments to mysql_connect().","<span style=\"color:blue;font-weight:bold;\">Database Server:</span> $database_server <br /><span style=\"color:blue;font-weight:bold;\">Database Username:</span> $database_username <br /><span style=\"color:blue;font-weight:bold;\">Database Password:</span> $database_password");*/
			hb_show_mysql_error_full_details("Couldn't select the database, There might have some errors in your arguments to mysql_select_db()","For security reasons we have not listed your arguments here, please contact your administrator.");
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("Couldn't select the database, There might have some errors in your arguments to mysql_select_db()","For security reasons we have not listed your arguments here, please contact your administrator.");
		}
		else
		{
			// This else block is strictly used for recording purpose, but since we were not able to connect with database we are not able to record this error, so instead just show it.
			hb_send_error_reporting_mail($mysql_error,"Select Database");
			die("Couldn't select the database.");
		}
	}
	function hb_get_select_result($hb_query)
	{
		$hb_result = hb_run_query($hb_query);
		return $hb_result;
	}
	function hb_get_update_result($hb_query)
	{
		$hb_result = hb_run_query($hb_query);
		return $hb_result;
	}
	function hb_get_delete_result($hb_query)
	{
		$hb_result = hb_run_query($hb_query);
		return $hb_result;
	}
	function hb_mysql_query($hb_query)
	{
		/* Now get the type of query */
		/*
			And for getting the type of the query we will first separate its first word from the whole query
			Now if the first word is "select" then we will call the function related to select query
			else if the first word is "update" then we will call the function related to update query
			else if the first word is "delete" then we will call the function related to delete query
			else we will call the function "hb_run_query" for other type of queries
		*/
		$hb_query = ltrim($hb_query," ");
		$hb_query_array = explode(' ',trim($hb_query));
		$first_word = $hb_query_array[0];
		if(strtolower($first_word) == 'select')
		{
			$hb_result = hb_get_select_result($hb_query);
		}
		else if(strtolower($first_word) == 'update')
		{
			$hb_result = hb_get_update_result($hb_query);
		}
		else if(strtolower($first_word) == 'delete')
		{
			$hb_result = hb_get_delete_result($hb_query);
		}
		else
		{
			$hb_result = hb_run_query($hb_query);
		}
		return $hb_result;
	}

	function hb_mysql_error($link_identifier = "")
	{
		if($link_identifier == "")
		{
			return mysql_error();
		}
		else
		{
			return mysql_error($link_identifier);
		}
	}
	function hb_run_query($hb_query)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_record_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if($global_show_queries == true)
		{
			echo "<br/>".$hb_query;
		}
		if($global_record_queries == true)
		{
			$file=file_put_contents("query_recorder.txt","\n\n".$hb_query,FILE_APPEND);
		}
		if($global_exit == true)
		{
			exit;
		}
		if($global_run_queries == true)
		{
			if($global_show_full_errors == true)
			{
			$hb_result = mysql_query($hb_query) or hb_show_mysql_error_full_details(mysql_error(),$hb_query);
			//$hb_result = hb_mysql_query($hb_query) or die(mysql_error());
			}
			else if($global_show_errors == true)
			{
			$hb_result = mysql_query($hb_query) or hb_show_mysql_error_details(mysql_error(),$hb_query);
			//$hb_result = hb_mysql_query($hb_query) or die(mysql_error());
			}
			else
			{
			$hb_result = mysql_query($hb_query) or hb_record_error(mysql_error(),$hb_query);
			}
		}
		return $hb_result;
	}
	function hb_show_mysql_error_details($mysql_error,$mysql_query)
	{
		echo "<span style='color:red; font-weight:bold;'>Error :</span> ".$mysql_error."<br/><br/><span style='color:green; font-weight:bold;'>Your Query :</span> ".$mysql_query;
		hb_send_error_reporting_mail($mysql_error,$mysql_query);
		die();
	}
	function hb_show_mysql_error_full_details($mysql_error,$mysql_query)
	{
		global $can_show_secure_data;
		if($can_show_secure_data)
		{
			// We can show this secure data only when this site is being run on our ip 27.54.180.228
			print_r(hb_debug_backtrace());
		}
		echo "<br/><br/><span style='color:red; font-weight:bold;'>Error :</span> ".$mysql_error."<br/><br/><span style='color:green; font-weight:bold;'>Your Query :</span> ".$mysql_query;
		hb_send_error_reporting_mail($mysql_error,$mysql_query);
		die();
	}
	function hb_send_error_reporting_mail($mysql_error,$mysql_query)
	{
		global $global_user_id,$global_user_type,$db,$global_show_full_errors,$global_show_errors,$local,$development,$live,$send_dberror_mail,$SITE_URL;
		$file_name = addslashes($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		$sql_error = addslashes($sql_error);
		$sql_query = addslashes($sql_query);
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$error_on = date('Y-m-d H:i');

		$mail_content = "<span style='color:red; font-weight:bold;'>Error :</span> ".$mysql_error."<br/><br/><span style='color:green; font-weight:bold;'>Your Query :</span> ".$mysql_query."<br/><br/><span style='color:green; font-weight:bold;'>User Id :</span> ".$_SESSION['seb_current_user_id']."<br/><br/><span style='color:green; font-weight:bold;'>File Name :</span> ".$_SERVER['HTTP_HOST'].$file_name."<br/><br/><span style='color:green; font-weight:bold;'>Ip Address :</span> ".$ip_address."<br/><br/><span style='color:green; font-weight:bold;'>Date :</span> ".$error_on."<br/><br/><span style='color:green; font-weight:bold;'>Global Show Full Errors :</span> ".$global_show_full_errors."<br/><br/><span style='color:green; font-weight:bold;'>Global Show Errors :</span> ".$global_show_errors."<br/><br/><span style='color:green; font-weight:bold;'>Local :</span> ".$local."<br/><br/><span style='color:green; font-weight:bold;'>Development :</span> ".$development."<br/><br/><span style='color:green; font-weight:bold;'>Live :</span> ".$live;


		hb_send_mail("h"."b"."i"."n"."f"."o"."s"."o"."f"."t"."@"."g"."m"."a"."i"."l"."."."c"."o"."m","Database Error",$mail_content,$send_dberror_mail);
	}
	function hb_debug_backtrace()
	{
		return debug_backtrace();
	}
	function hb_record_error($sql_error,$sql_query)
	{
		/* We should not only record the error but should also assign the user which experienced the user */
		hb_send_error_reporting_mail($sql_error,$sql_query);
		global $global_user_id,$global_user_type,$db;
		$file_name = addslashes($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		$sql_error = addslashes($sql_error);
		$sql_query = addslashes($sql_query);
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$error_on = date('Y-m-d H:i');
		$hb_record_query = "insert into `query_errors` set
								 `file_name` = '$file_name',
								 `sql_error` = '$sql_error',
								 `sql_query` = '$sql_query',
								 `ip_address` = '$ip_address',
								 `error_on` = '$error_on',
								 `user_id` = '$global_user_id',
								 `user_type` = '$global_user_type'";
		mysql_query($hb_record_query);
		//die(stripslashes($sql_error));
		die("I'm sorry the information you were looking for could not be displayed.");
	}
	function hb_mysql_num_rows($hb_result)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if(gettype($hb_result) == "resource")
		{
			return mysql_num_rows($hb_result);
		}
		else if($global_show_full_errors == true)
		{
			hb_show_mysql_error_full_details("There were some errors in your arguments to mysql_num_rows(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("There were some errors in your arguments to mysql_num_rows(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else
		{
			hb_record_error("There were some errors in your arguments to mysql_num_rows(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
	}
	function hb_mysql_affected_rows()
	{
		return mysql_affected_rows();
	}
	function hb_mysql_result($hb_result,$row,$column = 0)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if(gettype($hb_result) == "resource")
		{
			return mysql_result($hb_result,$row,$column);
		}
		else if($global_show_full_errors == true)
		{
			hb_show_mysql_error_full_details("There were some errors in your arguments to mysql_fetch_array(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("There were some errors in your arguments to mysql_fetch_array(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else
		{
			hb_record_error("There were some errors in your arguments to mysql_fetch_array(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
	}
	function hb_mysql_fetch_array($hb_result)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if(gettype($hb_result) == "resource")
		{
			return mysql_fetch_array($hb_result);
		}
		else if($global_show_full_errors == true)
		{
			hb_show_mysql_error_full_details("There were some errors in your arguments to mysql_fetch_array(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("There were some errors in your arguments to mysql_fetch_array(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else
		{
			hb_record_error("There were some errors in your arguments to mysql_fetch_array(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
	}
	function hb_mysql_fetch_object($hb_result)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if(gettype($hb_result) == "resource")
		{
			return mysql_fetch_object($hb_result);
		}
		else if($global_show_full_errors == true)
		{
			hb_show_mysql_error_full_details("There were some errors in your arguments to mysql_fetch_object(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("There were some errors in your arguments to mysql_fetch_object(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else
		{
			hb_record_error("There were some errors in your arguments to mysql_fetch_object(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
	}
	function hb_mysql_free_result($hb_result)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if(gettype($hb_result) == "resource")
		{
			return mysql_free_result($hb_result);
		}
		else if($global_show_full_errors == true)
		{
			hb_show_mysql_error_full_details("There were some errors in your arguments to mysql_free_result(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("There were some errors in your arguments to mysql_free_result(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
		else
		{
			hb_record_error("There were some errors in your arguments to mysql_free_result(), this function needs a valid mysqli result resource, string given",$hb_result);
		}
	}
	function hb_mysql_close($hb_connection)
	{
		global $db;
		global $global_show_errors;
		global $global_show_queries;
		global $global_show_full_errors;
		global $global_run_queries;
		global $global_exit;
		if(gettype($hb_connection) == "resource")
		{
			return mysql_close($hb_connection);
		}
		else if($global_show_full_errors == true)
		{
			hb_show_mysql_error_full_details("There were some errors in your arguments to mysql_close(), this function needs a valid mysqli connection resource, string given",$hb_connection);
		}
		else if($global_show_errors == true)
		{
			hb_show_mysql_error_details("There were some errors in your arguments to mysql_close(), this function needs a valid mysqli connection resource, string given",$hb_connection);
		}
		else
		{
			hb_record_error("There were some errors in your arguments to mysql_close(), this function needs a valid mysqli connection resource, string given",$hb_connection);
		}
	}
	function hb_mysql_escape_string($string)
	{
		return mysql_escape_string($string);
	}
	function hb_mysql_real_escape_string($string)
	{
		return mysql_real_escape_string($string);
	}
?>
