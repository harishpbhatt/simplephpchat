<?php
	error_reporting(E_ERROR);
	session_start();
	if(isset($_REQUEST["sender_id"]) && isset($_REQUEST["receiver_id"]) && isset($_REQUEST["contact_id"]) && isset($_REQUEST["message"]))
	{
		include('include/connect.php');
		$ccm_sender_id = $_REQUEST["sender_id"];
		$ccm_receiver_id = $_REQUEST["receiver_id"];

		$ccm_template_id			=	"1";
		$ccm_contact_id				=	$_REQUEST["contact_id"];
		$ccm_receiver_id			=	$_REQUEST["receiver_id"];
		$ccm_receiver_type			=	"tbl_customers";
		$ccm_subject				=	"0";
		$ccm_message				=	$_REQUEST["message"];
		$ccm_sender_id				=	$_REQUEST["sender_id"];
		$ccm_sender_type			=	"tbl_customers";
		$ccm_related_job_id			=	"0";
		$ccm_table_affected			=	"0";
		$ccm_operation_performed	=	"0";
		$ccm_id_affected			=	"0";
		$ccm_last_chat_message_id = hb_send_vorpit_chat_message(__LINE__,__FILE__,$ccm_contact_id,$ccm_template_id,$ccm_receiver_id,$ccm_receiver_type,$ccm_subject,$ccm_message,$ccm_sender_id,$ccm_sender_type,$ccm_related_job_id,$ccm_table_affected,$ccm_operation_performed,$ccm_id_affected);

		$ccm_contact_id = hb_get_contact_id($ccm_sender_id,$ccm_sender_type,$ccm_receiver_id,$ccm_receiver_type);
		if(hb_is_sender_of_contact($ccm_contact_id,$ccm_receiver_id,$ccm_receiver_type))
		{
			$ccm_unread_messages_list = hb_get_list("tbl_contacts","sender_unread_messages_id","id",$ccm_contact_id);
			$ccm_unread_messages_list = hb_filter_csl_for_query(hb_insert_into_csl($ccm_unread_messages_list,$ccm_last_chat_message_id));
			hb_SetValue("tbl_contacts","sender_unread_messages_id","id",$ccm_contact_id,$ccm_unread_messages_list);

			$ccm_unread_messages_list = hb_get_list("tbl_contacts","receiver_unread_messages_id","id",$ccm_contact_id);
			$ccm_unread_messages_list = hb_filter_csl_for_query(hb_insert_into_csl($ccm_unread_messages_list,$ccm_last_chat_message_id));
			hb_SetValue("tbl_contacts","receiver_unread_messages_id","id",$ccm_contact_id,$ccm_unread_messages_list);
		}
		else
		{
			$ccm_unread_messages_list = hb_get_list("tbl_contacts","receiver_unread_messages_id","id",$ccm_contact_id);
			$ccm_unread_messages_list = hb_filter_csl_for_query(hb_insert_into_csl($ccm_unread_messages_list,$ccm_last_chat_message_id));
			hb_SetValue("tbl_contacts","receiver_unread_messages_id","id",$ccm_contact_id,$ccm_unread_messages_list);

			$ccm_unread_messages_list = hb_get_list("tbl_contacts","sender_unread_messages_id","id",$ccm_contact_id);
			$ccm_unread_messages_list = hb_filter_csl_for_query(hb_insert_into_csl($ccm_unread_messages_list,$ccm_last_chat_message_id));
			hb_SetValue("tbl_contacts","sender_unread_messages_id","id",$ccm_contact_id,$ccm_unread_messages_list);
		}
	}
	else
	{

			$HOST = $_SERVER['HTTP_HOST'];
			if($HOST == 'server' || $HOST == '192.168.0.2' || $HOST == 'admin-pc')
			{
				$DBSERVER = "localhost";
				$DATABASENAME = "vopeit";
				$USERNAME = "root";
				$PASSWORD = "";
				$local = true;
				$development = false;
				$live = false;
			}
			else if($HOST == 'www.paradiseinfosoft.com' || $HOST == 'paradiseinfosoft.com' || $HOST == 'demo.paradiseinfosoft.com')
			{
				$DBSERVER = "67.212.186.170";
				$DATABASENAME = "paradise_vorpit";
				$USERNAME = "paradise_vorpit";
				$PASSWORD = "7tVhLusP3,;i";
				$local = false;
				$development = true;
				$live = false;
			}
			else if($HOST == '67.212.186.170')
			{
				$DBSERVER = "67.212.186.170";
				$DATABASENAME = "paradise_vorpit";
				$USERNAME = "paradise_vorpit";
				$PASSWORD = "7tVhLusP3,;i";
				$local = false;
				$development = true;
				$live = false;
			}
			else if($HOST == 'www.madniinfoway.com' || $HOST == 'madniinfoway.com' || $HOST == 'demo.madniinfoway.com')
			{
				$DBSERVER = "localhost";
				$DATABASENAME = "madniinf_vorpit";
				$USERNAME = "madniinf_vorpit";
				$PASSWORD = "7tVhLusP3,;i";
				$local = false;
				$development = true;
				$live = false;
			}
			else if($HOST == '216.12.194.26')
			{
				$DBSERVER = "216.12.194.26";
				$DATABASENAME = "madniinf_vorpit";
				$USERNAME = "madniinf_vorpit";
				$PASSWORD = "7tVhLusP3,;i";
				$local = false;
				$development = true;
				$live = false;
			}
			else if($HOST == '119.18.55.52')
			{
				$DBSERVER = "localhost";
				$DATABASENAME = "paradis1_vorpit";
				$USERNAME = "paradis1_cmnuser";
				$PASSWORD = "Z=;4qNa9snsT";
				$local = false;
				$development = true;
				$live = false;
			}
			else if($HOST == 'www.joinsocialbubble.com' || $HOST == 'joinsocialbubble.com')
			{
				$DBSERVER = "localhost";
				$DATABASENAME = "joinsocialbubble";
				$USERNAME = "jsbUser";
				$PASSWORD = "jsbPassword";
				$local = false;
				$development = false;
				$live = true;
			}
			else
			{

				$DBSERVER = "localhost";
				$DATABASENAME = "vopeit";
				$USERNAME = "root";
				$PASSWORD = "root";
				$local = false;
				$development = false;
				$live = true;	//	 This variable is used in hb_filter_var() function
			}
		function GetValue($table,$field,$where,$condition)
		{
			if($table != '' && $field != '' && $where != '' && $condition != '')
			{
				$qry="SELECT $field from $table where $where='$condition'";
				$res=mysql_query($qry);
				if(mysql_affected_rows()>0)
				{
						$row=mysql_fetch_array($res);
						return htmlspecialchars_decode(stripslashes($row[$field]));
				}
				else
				{
						return "";
				}
			}
		}
		include_once("include/csl_specific_functions.php");
	}

	/* if user is not online you should do nothing */
	if(!(isset($_SESSION['seb_current_user_id'])))
	{
		$response["response_code"] = "UserIsOffline";
		$response["response_text"] = "User Is Offline";
		$_response_in_json = json_encode($response);
		echo $_response_in_json;
		exit;
	}

	$global_user_id = $_SESSION['seb_current_user_id'];
	$global_user_type = "tbl_customers";

	$server_timeout = 30;
	$interval_for_manual_end = 5;
	//set_time_limit($server_timeout);

			/*$DBSERVER = "localhost";
			$DATABASENAME = "vopeit";
			$USERNAME = "root";
			$PASSWORD = "";*/

			/*$DBSERVER = "67.212.186.170";
			$DATABASENAME = "paradise_vorpit";
			$USERNAME = "paradise_vorpit";
			$PASSWORD = "7tVhLusP3,;i";*/

			/*$DBSERVER = "216.12.194.26";
			$DATABASENAME = "madniinf_vorpit";
			$USERNAME = "madniinf_vorpit";
			$PASSWORD = "7tVhLusP3,;i";*/

			mysql_connect($DBSERVER,$USERNAME,$PASSWORD) or die(mysql_error());
			mysql_select_db($DATABASENAME) or die(mysql_error());

	$i = 1;

	$run_for = 1;
	while($run_for)
	{
		$run_for = 0;
		//sleep(1);
		clearstatcache();
		//echo "<br/>".;
		if($i++ > ($server_timeout - $interval_for_manual_end))
		{
			$response["response_code"] = "Failure";
			$response["response_text"] = ($server_timeout - $interval_for_manual_end)." seconds passed";
			$_response_in_json = json_encode($response);
			echo $_response_in_json;
			exit;
		}
		else
		{
				/* first section of chat messages started */
				$friends_where_I_am_sender_query = "select `id`,`sender_unread_messages_id` as `unread_messages`,`sender_read_messages_id` as `read_messages`,`receiver_id` as `customer_id`,`receiver_login_status` as `customer_login_status`,'sender' as `prefix_of_fields` from `tbl_contacts` where `sender_id` = '$global_user_id' and `sender_type` = '$global_user_type' and `contact_status` = 'Accepted'";
				$friends_where_I_am_receiver_query = "select `id`,`receiver_unread_messages_id` as `unread_messages`,`receiver_read_messages_id` as `read_messages`,`sender_id` as `customer_id`,`sender_login_status` as `customer_login_status`,'receiver' as `prefix_of_fields` from `tbl_contacts` where `receiver_id` = '$global_user_id' and `receiver_type` = '$global_user_type' and `contact_status` = 'Accepted'";
				$friends_query = $friends_where_I_am_sender_query." union ".$friends_where_I_am_receiver_query;
				$friends_result = mysql_query($friends_query) or die(mysql_error());
				$friends_total = mysql_num_rows($friends_result);
				$friends_count = -1;

				$response_array = array();
				$response_array2 = array();
				$ccm_unread_messages_array_with_details = array();
				$send_response = "no";

				if($friends_total)
				{
					while($friends_data = mysql_fetch_array($friends_result))
					{
						$friends_count++;
						$contact_id = $friends_data["id"];
						$customer_id = $friends_data["customer_id"];
						$customer_chat_window_status_in_browser = $_REQUEST["chat_window_".$customer_id."_status"];

						/* store the above values in session for better navigatin system */
						$_SESSION["windows"]["window_".$customer_id] = array($customer_id,$contact_id,$customer_chat_window_status_in_browser);

						$unread_messages = hb_filter_csl_for_query($friends_data["unread_messages"]);
						$read_messages = hb_filter_csl_for_query($friends_data["read_messages"]);
						if($read_messages != "")
						{
							$messages_array = explode(",",$read_messages);
							$id_of_last_message_stored_in_this_database = $messages_array[(count($messages_array) - 1)];
						}
						else
						{
							$messages_array = array();
							$id_of_last_message_stored_in_this_database = "";
						}
						if(isset($_REQUEST["id_of_last_message_stored_in_this_browser_".$customer_id]))
						{
							$id_of_last_message_stored_in_this_browser = $_REQUEST["id_of_last_message_stored_in_this_browser_".$customer_id];
						}
						else
						{
							$id_of_last_message_stored_in_this_browser = "";
						}

						if($unread_messages != "")
						{
							$unread_messages_array = explode(",",$unread_messages);
							$unread_messages_count = count($unread_messages_array);
						}
						else
						{
							$unread_messages_array = array();
							$unread_messages_count = "0";
						}

						$prefix_of_fields = $friends_data["prefix_of_fields"];

						$messages_count = 0;
						$messages_query = "";
						/* multi browser coding started */
						if($id_of_last_message_stored_in_this_browser > -1 && $id_of_last_message_stored_in_this_browser != "")
						{
								// conntrol will come here if the window will be maximized by user
									if($id_of_last_message_stored_in_this_database > $id_of_last_message_stored_in_this_browser)
									{
										if($read_messages != "")
										{
										/* getting message text coding started */
											$messages_query = " select `tbl_chat_messages`.`id`,`tbl_chat_messages`.`message`,`tbl_chat_messages`.`sender_id`,'read' as `read_status` from `tbl_chat_messages` where `id` in ($read_messages) and `id` > '".$id_of_last_message_stored_in_this_browser."'";
										}
									}
						}
						if($unread_messages != "")
						{
							if($id_of_last_message_stored_in_this_browser == "")
							{
								if($read_messages != "")
								{
								/* getting message text coding started */
									$messages_query = " select `tbl_chat_messages`.`id`,`tbl_chat_messages`.`message`,`tbl_chat_messages`.`sender_id`,'read' as `read_status` from `tbl_chat_messages` where `id` in ($read_messages) ";
								}
							}
							if($messages_query != "")
							{
								$messages_query .= " union ";
							}

							$messages_query .= " select `tbl_chat_messages`.`id`,`tbl_chat_messages`.`message`,`tbl_chat_messages`.`sender_id`,'unread' as `read_status` from `tbl_chat_messages` where `id` in ($unread_messages)";
						}
									if($messages_query != "")
									{
										$messages_result = mysql_query($messages_query) or die($messages_query." ".mysql_error());
										$messages_total = mysql_num_rows($messages_result);
										$messages_count = 0;
										if($messages_total > 0)
										{
											$response_array2 = array();
											while($messages_data = mysql_fetch_array($messages_result))
											{
												$id_of_last_message_stored_in_this_browser = $messages_data['id'];
												$read_status = $messages_data['read_status'];
												$response_array2[$messages_count] = array("sender_id" =>$messages_data['sender_id'],"message" =>$messages_data['message'],"type" =>$read_status);
												$messages_count++;
											}

											if($unread_messages_count > 0)
											{
												if($prefix_of_fields != "")
												{
													$update_query = "update `tbl_contacts` set `".$prefix_of_fields."_unread_messages_id` = '',`".$prefix_of_fields."_read_messages_id` = concat(`".$prefix_of_fields."_read_messages_id`,',$unread_messages,') where `id` = '$contact_id' limit 1";
													$update_result = mysql_query($update_query) or die(mysql_error());
												}
											}


											// we are passing this response for letting the page know that there are some new messages for given user
											$response_array[$friends_count] = array("customer_id"=>$customer_id,"contact_id"=>$contact_id,"messages"=>$response_array2,"id_of_last_message_stored_in_this_browser"=>$id_of_last_message_stored_in_this_browser);
											$send_response = "yes";
											// now we will also include the message text for this user in this same script
										}
									}
								/* getting message text coding ended */

						/* multi browser coding ended */

					}
				}
				/* first section of chat messages ended */

				/* first section of inbox messages started */
				$ccm_unread_messages_list = hb_filter_csl_for_query(hb_get_list("tbl_customers","unread_messages_id","id",$global_user_id));
				if($ccm_unread_messages_list != "")
				{
					$ccm_unread_messages_array = explode(",",$ccm_unread_messages_list);
					$ccm_unread_messages_count = count($ccm_unread_messages_array);
					if($ccm_unread_messages_count > 0)
					{
						if(is_array($ccm_unread_messages_array) && (!(empty($ccm_unread_messages_array))))
						{
							$ccm_unread_messages_query = "select `id`,`subject` from `tbl_messages` where 1 and `id` in ($ccm_unread_messages_list)";
							$ccm_unread_messages_result = mysql_query($ccm_unread_messages_query) or die(mysql_error());
							$ccm_unread_messages_total = mysql_num_rows($ccm_unread_messages_result);
							$ccm_unread_messages_count = 0;
							if($ccm_unread_messages_total > 0)
							{
								include('include/connect.php');
								$ccm_unread_messages_array_with_details = array();
								while($ccm_unread_messages_data = mysql_fetch_array($ccm_unread_messages_result))
								{
									$ccm_unread_messages_count++;
									$ccm_unread_messages_array_with_details[] = array("count"=>$ccm_unread_messages_count,"id"=>$ccm_unread_messages_data["id"],"subject"=>hb_replace_replaceable_names($ccm_unread_messages_data["subject"]));
								}
							}
						}
						$send_response = "yes";
					}
				}
				/* first section of inbox messages ended */

				if($send_response == "yes")
				{
						$response["response_code"] = "Success";
						//$response["response_text"] = "$last_message_id inserted ----- ".($server_timeout - 5)." seconds passed";
						$response["response_text"] = $response_array;
						$response["response_text_for_unread_inbox_messages"] = $ccm_unread_messages_array_with_details;
						$_response_in_json = json_encode($response);
						echo $_response_in_json;
						exit;
				}
				else
				{
						$response["response_code"] = "Failure";
						//$response["response_text"] = "$last_message_id inserted ----- ".($server_timeout - 5)." seconds passed";
						$response["response_text"] = "Dont worry";
						$response["response_text_for_unread_inbox_messages"] = $ccm_unread_messages_array_with_details;
						$_response_in_json = json_encode($response);
						echo $_response_in_json;
						exit;
				}
		}
	}

?>
