<?php if($global_user_id > 0){ ?>
	<style type="text/css">
	.chat_panel
	{
		width: auto;
		float:right;
		position:fixed;
		bottom:1px;
		right:1px;
	}
	.chat_window
	{
		width:200px;
		float:right;
		position:relative;
		bottom:0px;
		right:0px;
		margin-right:5px;
		margin-bottom:5px;
		z-index:100000;
	}
	.heading
	{
		width:95%;
		float:left;
		background-color:#227FCD;
		padding:2.5%;
		color:#FFFFFF;
		font-weight:bold;
	}
	.heading_left
	{
		width:86%;
		float:left;
		text-align:left;
		cursor:pointer;
	}	
	.heading_right_minimize
	{
		width:auto;
		float:left;
		text-align:right;
		cursor:pointer;
		margin-right:5px;
	}		
	.heading_right_close
	{
		width:auto;
		float:left;
		text-align:right;
		cursor:pointer;
	}
	.chat_body
	{
		width:95%;
		float:left;
		background-color:#EDEFF4;
		padding:2.5%;
		color:#000000;
		font-weight:bold;
		max-height:200px;
		min-height:196px;
		overflow-y: scroll;
	}
	.inbox_area
	{
		width:100%;
		float:left;
		text-align:left;
	}
	.sentbox_area
	{
		width:100%;
		float:left;
		text-align:right;
	}
	.chat_send_message
	{
		width:95%;
		float:left;
		background-color:#ffffff;
		padding:2.5%;
		color:#000000;
		font-weight:bold;
		overflow-y: scroll;
	}
	.inbox_area{margin:3px 0px;}
	.inbox_area img{ float:left;}
	.sentbox_area img{ float:right;}
	
	.sentbox_area{margin:3px 0px;}

	.msg{background:#F8F8F8; padding:2px 5px; font-size:12px; font-family:Century Gothic; font-weight:normal; margin-left:10px; border:1px solid #CCCCCC; border-radius:2px; vertical-align:top; max-width:70%; float:left; width:auto;}
	.msg1{background:#CCCCCC; padding:2px 5px; font-size:12px; font-family:Century Gothic;font-weight:normal;float:right; margin-right:10px; border:1px solid #CCCCCC; border-radius:2px; vertical-align:top; max-width:70%; float:right;width:auto;}
	.online img{width:15%; float:left;}
	.online img{width:15%; float:left;}
	.user_name{width:80%; margin-left:5%; line-height:25px;}
	.status{font-size:10px; float:right; width:80%; font-family:arial}
	.online,.heading_left,.offline{font-family:"Century Gothic";}
	.online{font-weight:normal; line-height:23px; font-size:12px;}
	.offline{font-weight:normal; line-height:23px; font-size:12px;}
	.receiver_name_class
	{
		color:#FFFFFF;
		text-decoration:none;
	}
	.receiver_name_class:hover
	{
		color:#FFFFFF;
		text-decoration:underline;
	}
	.heading{font-weight:normal;}
</style>
<script language="javascript">
	function hb_something_clicked($chat_window_area,$chat_window_id)
	{
		if($chat_window_area != "" && $chat_window_id != "")
		{
			//alert($chat_window_area);
			//alert($chat_window_id);
			
			// If chat_head gets clicked then we should toggle the display of chat_body
			if($chat_window_area == "chat_head_right_minimize" || $chat_window_area == "chat_head_left")
			{
				$("#chat_body_"+$chat_window_id).toggle();
				$("#chat_send_message_"+$chat_window_id).toggle();
				$("#chat_window_"+$chat_window_id+"_status").val("minimized");
				if($("#chat_window_"+$chat_window_id+"_status").val() == "minimized")
				{
					$("#chat_window_"+$chat_window_id+"_status").val("maximized");					
				}
				else
				{
					$("#chat_window_"+$chat_window_id+"_status").val("minimized");					
				}
			}
			if($chat_window_area == "chat_head_right_close")
			{ 
				if($("#chat_window_"+$chat_window_id+"_status").val() == "maximized" || $("#chat_window_"+$chat_window_id+"_status").val() == "minimized")
				{
					$("#chat_widnow_"+$chat_window_id).toggle();
					$("#chat_widnow_"+$chat_window_id+"_container").toggle();
					$("#chat_window_"+$chat_window_id+"_status").val("hidden");							
				}
				else
				{
					console.log("The window which you are trying to hide is NEITHER IN 'maximized' mode NOR IN 'minimized' mode, there must be some problem in given code.");
				}
			}
			
			$("#chat_heading_"+$chat_window_id).css("background-color","#227FCD");
			
			/* as soon as something gets clicked we soon should called the message function for making sure that the update status of window gets updated in session also */
			hb_ajax_get_online_friends_list();
		}
		else
		{
			apprise("Please pass valid chat window id.");
		}
	}
	
	var hb_ajax_get_online_friends_list_status = "idle";
	function hb_send_chat_message($chat_window_id,$contact_id,ev)
	{	
		hb_ajax_get_online_friends_list_status = "idle";
		try
		{		
			if(ev.keyCode == 13){
			
			$sender_id = $("#sender_id_"+$chat_window_id).val();
			$receiver_id = $("#receiver_id_"+$chat_window_id).val();
			$message = $("#message_"+$chat_window_id).val();				
			
					try
					{
						if(hb_ajax_get_online_friends_list_status == "idle")
						{
							hb_ajax_get_online_friends_list_status = "running";
							$online_form_data = $( "#online_form" ).serialize();
							$online_form_data = $online_form_data + "&sender_id="+$sender_id+"&receiver_id="+$receiver_id+"&message="+$message+"&contact_id="+$contact_id;
							
								$.ajax({
								url: "ajax_get_online_friends.php",
								type: "POST",
								data: $online_form_data,
								statusCode: {
									404: function() {
									
										// make a function free
										hb_ajax_get_online_friends_list_status = "idle";
										
										alert("Service '"+$service_url+"' not found.");
									}
								},
							}).done(function(data) {
									$("#message_"+$chat_window_id).val("");	
									$response_in_json = data;
									$response_in_object = jQuery.parseJSON($response_in_json);
									if($response_in_object.response_code == "Success")
									{
											$.each( $response_in_object.response_text, function( key, $leads_data ) {
												//hb_open_chat_window('maximized',$leads_data.customer_id,$leads_data.contact_id);
												
												if($("#chat_window_"+$leads_data.customer_id+"_status").val() == "maximized")
												{
													// Do nothing													
													$("#message_"+$leads_data.customer_id).focus();
												}
												else if($("#chat_window_"+$leads_data.customer_id+"_status").val() == "closed")
												{
													// make it maximized
													hb_open_chat_window('maximized',$leads_data.customer_id,$leads_data.contact_id,'<?php echo $dont_call_hb_ajax_get_online_friends_list; ?>');
												}
												else if($("#chat_window_"+$leads_data.customer_id+"_status").val() == "hidden")
												{
													// make it maximized
													$("#chat_widnow_"+$leads_data.customer_id).toggle();													
													$("#chat_widnow_"+$leads_data.customer_id+"_container").toggle();										
													$("#chat_window_"+$leads_data.customer_id+"_status").val("maximized");
													$("#message_"+$leads_data.customer_id).focus();
												}
												$.each( $leads_data.messages, function( key, $messages_data ) {
												$receiver_image_path = $("#receiver_image_path_"+$leads_data.customer_id+"").val();
													if($messages_data.sender_id == $leads_data.customer_id)
													{
														$("#chat_body_"+$leads_data.customer_id+"").append('<div class="inbox_area"><a href="user_detail.php?user_id='+$leads_data.customer_id+'" class="receiver_name_class"><img src="customer_images/'+$receiver_image_path+'" width="25" height="25" /></a><span class="msg">'+$messages_data.message+'</span></div>');
														document.getElementById("chat_message_audio").play();
														$("#chat_heading_"+$leads_data.customer_id).css("background-color","#ffa500");
													}
													else
													{
														$global_user_image = "<?php echo $global_user_image_dir.$global_user_image_path; ?>";
														$("#chat_body_"+$leads_data.customer_id+"").append('<div class="sentbox_area"><a class="receiver_name_class" href="user_detail.php?user_id=2"><img height="25" width="25" src="'+$global_user_image+'"></a><span class="msg1">'+$messages_data.message+'</span></div>');
													}
												});	
												$("#id_of_last_message_stored_in_this_browser_"+$leads_data.customer_id).val($leads_data.id_of_last_message_stored_in_this_browser);				
												document.getElementById("chat_body_"+$leads_data.customer_id).scrollTop=document.getElementById("chat_body_"+$leads_data.customer_id).scrollHeight;	
											});
											if($response_in_object.response_text_for_unread_inbox_messages != '')
											{
												var $notification_string = "Unread message(s)<br />";
												$.each( $response_in_object.response_text_for_unread_inbox_messages, function( key, $inbox_messages_data ) {
													$notification_string = $notification_string + '<a href="send_to_msg_detail.php?message_id='+$inbox_messages_data.id+'&then_go=message_detail.php" class="white_anchor">'+$inbox_messages_data.count+') '+$inbox_messages_data.subject+'</a><br />'
												});
												$("#notification_container").html($notification_string);
												$("#notification_container").show();
												//document.getElementById("chat_message_audio").play();
											}
											//hb_ajax_get_online_friends_list();							
									
										// make a function free
										hb_ajax_get_online_friends_list_status = "idle";
									}
									else if($response_in_object.response_code == "Failure")
									{					
										
											console.log($response_in_object.response_text);
											
										// make a function free
										hb_ajax_get_online_friends_list_status = "idle";
										
											//console.log("calling get_chat_message again.");
											//hb_ajax_get_online_friends_list();
									}
									else if($response_in_object.response_code == "UserIsOffline")
									{
											console.log($response_in_object.response_text);
									
											// make a function free
											hb_ajax_get_online_friends_list_status = "idle";
										
											// Do nothing, just take rest, you will automatic know when user will come online.
									}
									else
									{						
										// make a function free
										hb_ajax_get_online_friends_list_status = "idle";
										
										alert("WEBSERVICE: "+$service_url+"\n\n There are some unexcepted errors in calling the service, please try again later or contact the administrator.");
									}
							}).fail(function() {
									
										// make a function free
										hb_ajax_get_online_friends_list_status = "idle";
										
								console.log("something unexpected happen.");
								//console.log("calling get_chat_message again.");
								//hb_ajax_get_online_friends_list();
							});
						}
						else
						{
							console.log("one instance of hb_ajax_get_online_friends_list() is already running");
						}
					}
					catch(e)
					{
						alert("Error: "+e);
					}
				
			}
		}
		catch(e)
		{
			alert("Error: "+e);
		}	
	}
</script>
<form name="online_form" id="online_form" onsubmit="javascript: return false;">
<div id="chat_panel" class="chat_panel">  
    <!-- Online Users Started -->
    <table width="100%" border="0">
      <tr id="chat_panel_container">
        <td valign="bottom"><div class="chat_window" id="chat_widnow_0" onclick="hb_something_clicked('chat_window','0')" style="width:167px;">
        		<!-- notification started -->
                <div class="heading_left" id="notification_container" style="margin-bottom:20px;width:95%;float:left;background-color:#227FCD;padding:2.5%;color:#FFFFFF;font-weight:normal; display:none;">
                    <a href="send_to_msg_detail.php?message_id=0&then_go=message_detail.php" class="white_anchor">New message(s) received, click here to view.</a>
                </div>	
           		<!-- notification ended -->
            <div class="heading">
                <div class="heading_left" id="heading_left_0" onclick="hb_something_clicked('chat_head_left','0')" style="width:92%;">
                    Your Friends
                </div>    	           
                <div class='heading_right_minimize' onclick='hb_something_clicked("chat_head_right_minimize","0")'>_</div>	
            </div>
            <div class="chat_body" id="chat_body_0" onclick="hb_something_clicked('chat_body','0')" style="min-height:235px;">
                <?php
                    /*
                        predefined variables
                    */
                    $call_hb_ajax_get_online_friends_list = "yes";
                    $dont_call_hb_ajax_get_online_friends_list = "no";
                    $friends_where_I_am_sender_query = "select `contact_status` as `contact_status`,`id` as `contact_id`,`receiver_id` as `customer_id`,`receiver_login_status` as `customer_login_status`,`receiver_last_activity_on` as `last_activity_on` from `tbl_contacts` where `sender_id` = '$global_user_id' and `sender_type` = '$global_user_type' and (`contact_status` = 'Accepted')";
                    $friends_where_I_am_receiver_query = "select `contact_status` as `contact_status`,`id` as `contact_id`,`sender_id` as `customer_id`,`sender_login_status` as `customer_login_status` ,`sender_last_activity_on` as `last_activity_on` from `tbl_contacts` where `receiver_id` = '$global_user_id' and `receiver_type` = '$global_user_type' and (`contact_status` = 'Accepted')";
                    $friends_query = $friends_where_I_am_sender_query." union ".$friends_where_I_am_receiver_query;
                    $friends_result = hb_mysql_query($friends_query);
                    $friends_total = hb_mysql_num_rows($friends_result);
                    if($friends_total > 0)
                    {
                        while($friends_data = hb_mysql_fetch_array($friends_result))
                        {
                            $contact_id = $friends_data["contact_id"];
                            $customer_id = $friends_data["customer_id"];
                            $customer_login_status = $friends_data["customer_login_status"];
                            $customer_last_activity_on = $friends_data["last_activity_on"];
                            $customer_image_path = hb_get_list("tbl_customers","image_path","id",$customer_id);
                            $contact_status = $friends_data["contact_status"];
                            
                            if($customer_image_path != "" && file_exists("customer_images/".$customer_image_path))
                            {
                                // Do nothing
                                $customer_image_dir = "customer_images/";
                            }
                            else
                            {
                                $customer_image_dir = "images/";
                                $customer_image_path = "inbox_no_photo.png";
                            }
                            if($contact_status == "Pending" && 0)
                            {
                                // will never go inside
                                ?>                        
                                <div class="inbox_area online" id="inbox_area_id_<?php echo $customer_id; ?>" onclick="hb_open_chat_window('maximized','<?php echo $customer_id; ?>','<?php echo $contact_id; ?>','<?php echo $call_hb_ajax_get_online_friends_list; ?>')">
                                
                                <div style="float:left;"><img src="<?php echo $customer_image_dir.$customer_image_path; ?>" width="25" height="25" /><span class="user_name"><?php echo hb_get_list("tbl_customers","first_name","id",$customer_id);?> <?php echo hb_get_list("tbl_customers","last_name","id",$customer_id);?></span>
                                </div>
                               <div style="float:left;">
                               <a href="<?php echo $SITE_URL; ?>update_contact_status.php?change_status=accept&contact_id=<?php echo $contact_id; ?>&post_id=2&then_go=inbox.php" title="Accept Contact Request"><img src="images/tick_mark.png" width="44" /></a>
                                <a href="<?php echo $SITE_URL; ?>update_contact_status.php?change_status=decline&contact_id=<?php echo $contact_id; ?>&post_id=2&then_go=inbox.php" title="Decline Contact Request"><img src="images/cross_mark.png" width="44" /></a>
                                </div>
                                
                                </div>
                                <input type="hidden" name="chat_window_<?php echo $customer_id; ?>_status" id="chat_window_<?php echo $customer_id; ?>_status" value="closed" />
                                <input type="hidden" name="receiver_name_<?php echo $customer_id; ?>" id="receiver_name_<?php echo $customer_id; ?>" value="<?php echo hb_get_list("tbl_customers","first_name","id",$customer_id)." ".hb_get_list("tbl_customers","last_name","id",$customer_id); ?>" />
                                <input type="hidden" name="receiver_image_path_<?php echo $customer_id; ?>" id="receiver_image_path_<?php echo $customer_id; ?>" value="<?php echo $customer_image_path; ?>" />
                                <?php
                            }
                            else
                            {
                                if($customer_login_status == "loggedin")
                                {
                                    ?>                        
                                    <div class="inbox_area online" id="inbox_area_id_<?php echo $customer_id; ?>" onclick="hb_open_chat_window('maximized','<?php echo $customer_id; ?>','<?php echo $contact_id; ?>','<?php echo $call_hb_ajax_get_online_friends_list; ?>')"><img src="<?php echo $customer_image_dir.$customer_image_path; ?>" style="border:1px solid #DEDEDE; padding:1px; " width="35" height="30" /><span class="user_name"><?php echo hb_get_list("tbl_customers","first_name","id",$customer_id);?> <?php echo hb_get_list("tbl_customers","last_name","id",$customer_id);?></span></div>
                                    <input type="hidden" name="chat_window_<?php echo $customer_id; ?>_status" id="chat_window_<?php echo $customer_id; ?>_status" value="closed" />
                                    <input type="hidden" name="receiver_name_<?php echo $customer_id; ?>" id="receiver_name_<?php echo $customer_id; ?>" value="<?php echo hb_get_list("tbl_customers","first_name","id",$customer_id)." ".hb_get_list("tbl_customers","last_name","id",$customer_id); ?>" />
                                    <input type="hidden" name="receiver_image_path_<?php echo $customer_id; ?>" id="receiver_image_path_<?php echo $customer_id; ?>" value="<?php echo $customer_image_path; ?>" />
                                    <?php
                                }
                                else
                                {
                                    ?>                        
                                    <div class="inbox_area offline" id="inbox_area_id_<?php echo $customer_id; ?>" onclick="hb_open_chat_window('maximized','<?php echo $customer_id; ?>','<?php echo $contact_id; ?>','<?php echo $call_hb_ajax_get_online_friends_list; ?>')"><img src="<?php echo $customer_image_dir.$customer_image_path; ?>" style="border:1px solid #DEDEDE; padding:1px;" width="35" height="30" /><span class="user_name"><?php echo hb_get_list("tbl_customers","first_name","id",$customer_id);?> <?php echo hb_get_list("tbl_customers","last_name","id",$customer_id);?></span><br /></div>
                                    <input type="hidden" name="chat_window_<?php echo $customer_id; ?>_status" id="chat_window_<?php echo $customer_id; ?>_status" value="closed" />
                                    <input type="hidden" name="receiver_name_<?php echo $customer_id; ?>" id="receiver_name_<?php echo $customer_id; ?>" value="<?php echo hb_get_list("tbl_customers","first_name","id",$customer_id)." ".hb_get_list("tbl_customers","last_name","id",$customer_id); ?>" />
                                    <input type="hidden" name="receiver_image_path_<?php echo $customer_id; ?>" id="receiver_image_path_<?php echo $customer_id; ?>" value="<?php echo $customer_image_path; ?>" />
                                    <?php
                                }
                            }
                        }
                    }
                    else
                    {
                ?>
                <div class="inbox_area online" style="text-align:center; margin-top:50px;">You have not yet added any of your friends.<br /><br />
                    <button onclick="window.location.href='<?php echo $global_find_people_file_name; ?>'">Find Friends</button>
                </div>
                <?php
                    }
                ?>
            </div>
        </div></td>
      </tr>
    </table>     
    <!-- Online Users Ended -->
</div>
</form>
<div style="height:0px; width:0px; float:left; display:none;">
<audio controls  id="chat_message_audio">
  <source src="mp3s/vorpit_chat_sound.mp3" type="audio/mpeg">
Your browser does not support the audio element.
</audio>
</div> 
<script language="javascript">	
	function hb_open_chat_window($opening_mode,$receiver_id,$contact_id,$call_hb_ajax_get_online_friends_list)
	{
		if($("#chat_window_"+$receiver_id+"_status").val() == "closed")
		{
			try
			{
				$receiver_name = $("#receiver_name_"+$receiver_id+"").val();
				$receiver_image_path = $("#receiver_image_path_"+$receiver_id+"").val();
				
					$chat_window_for_current_user = "  <td valign='bottom' id='chat_widnow_"+$receiver_id+"_container'><div class='chat_window' id='chat_widnow_"+$receiver_id+"' onclick='hb_something_clicked(\"chat_window\",\""+$receiver_id+"\")'><div class='heading' id='chat_heading_"+$receiver_id+"'><div class='heading_left' onclick='hb_something_clicked(\"chat_head_left\",\""+$receiver_id+"\")'>"+$receiver_name+"</div><div class='heading_right_minimize' onclick='hb_something_clicked(\"chat_head_right_minimize\",\""+$receiver_id+"\")'>_</div><div class='heading_right_close' onclick='hb_something_clicked(\"chat_head_right_close\",\""+$receiver_id+"\")' title='Close'>X</div></div><div class='chat_body' id='chat_body_"+$receiver_id+"' onclick='hb_something_clicked(\"chat_body\",\""+$receiver_id+"\")'></div><div class='chat_send_message' id='chat_send_message_"+$receiver_id+"' onclick='hb_something_clicked(\"chat_send_message\",\""+$receiver_id+"\")'><input type='hidden' name='sender_id_"+$receiver_id+"' id='sender_id_"+$receiver_id+"' value='<?php echo $global_user_id; ?>' /><input type='hidden' name='receiver_id_"+$receiver_id+"' id='receiver_id_"+$receiver_id+"' value='"+$receiver_id+"' /><input type='hidden' name='receiver_image_path_"+$receiver_id+"' id='receiver_image_path_"+$receiver_id+"' value='"+$receiver_image_path+"' /><input type='hidden' name='id_of_last_message_stored_in_this_browser_"+$receiver_id+"' id='id_of_last_message_stored_in_this_browser_"+$receiver_id+"' value='0' /><input type='text' name='message_"+$receiver_id+"' id='message_"+$receiver_id+"' autocomplete='off' onkeyup='hb_send_chat_message(\""+$receiver_id+"\",\""+$contact_id+"\",event)' style='width:97%;' /></div></div></td>";					
					//console.log($chat_window_for_current_user);
					
					$("#chat_panel_container").prepend($chat_window_for_current_user);
					$("#message_"+$receiver_id).focus();
					$("#chat_window_"+$receiver_id+"_status").val("maximized");
					if($opening_mode == "minimized")
					{
						$("#chat_body_"+$receiver_id).toggle();
						$("#chat_send_message_"+$receiver_id).toggle();
						$("#chat_window_"+$receiver_id+"_status").val("minimized");
					}
					if($call_hb_ajax_get_online_friends_list == "yes")
					{
						hb_ajax_get_online_friends_list();
					}
			}
			catch(e)
			{
				alert("Error: "+e);
			}
		}
		else if($("#chat_window_"+$receiver_id+"_status").val() == "hidden")
		{
			$("#chat_widnow_"+$receiver_id).toggle();
			$("#chat_widnow_"+$receiver_id+"_container").toggle();
			$("#chat_window_"+$receiver_id+"_status").val("maximized");
			$("#message_"+$receiver_id).focus();
		}
		else
		{
			//console.log("The chat window that you are trying to open for "+$receiver_name+" is already open.");
			console.log("The chat window that you are trying to open for user is already open.");
		}
	}
	var hb_ajax_get_online_friends_list_status = "idle";
	function hb_ajax_get_online_friends_list()
	{
		try
		{
			if(hb_ajax_get_online_friends_list_status == "idle")
			{
				hb_ajax_get_online_friends_list_status = "running";
				$online_form_data = $( "#online_form" ).serialize();
					$.ajax({
					url: "ajax_get_online_friends.php",
					type: "POST",
					data: $online_form_data,
					statusCode: {
						404: function() {
						
							// make a function free
							hb_ajax_get_online_friends_list_status = "idle";
							
							alert("Service '"+$service_url+"' not found.");
						}
					},
				}).done(function(data) {
							
						$response_in_json = data;
						$response_in_object = jQuery.parseJSON($response_in_json);
						if($response_in_object.response_code == "Success")
						{
								$.each( $response_in_object.response_text, function( key, $leads_data ) {
									//hb_open_chat_window('maximized',$leads_data.customer_id,$leads_data.contact_id,'<?php //echo $dont_call_hb_ajax_get_online_friends_list; ?>');
									
									if($("#chat_window_"+$leads_data.customer_id+"_status").val() == "maximized")
									{
										// Do nothing
										$("#message_"+$leads_data.customer_id).focus();
									}
									else if($("#chat_window_"+$leads_data.customer_id+"_status").val() == "closed")
									{
										// make it maximized
										hb_open_chat_window('maximized',$leads_data.customer_id,$leads_data.contact_id,'<?php echo $dont_call_hb_ajax_get_online_friends_list; ?>');
									}
									else if($("#chat_window_"+$leads_data.customer_id+"_status").val() == "hidden")
									{
										// make it maximized
										$("#chat_widnow_"+$leads_data.customer_id).toggle();	
										$("#chat_widnow_"+$leads_data.customer_id+"_container").toggle();									
										$("#chat_window_"+$leads_data.customer_id+"_status").val("maximized");
										$("#message_"+$leads_data.customer_id).focus();
									}
									$.each( $leads_data.messages, function( key, $messages_data ) {
									$receiver_image_path = $("#receiver_image_path_"+$leads_data.customer_id+"").val();
										if($messages_data.sender_id == $leads_data.customer_id)
										{
											$("#chat_body_"+$leads_data.customer_id+"").append('<div class="inbox_area"><a href="user_detail.php?user_id='+$leads_data.customer_id+'" class="receiver_name_class"><img src="customer_images/'+$receiver_image_path+'" width="25" height="25" /></a><span class="msg">'+$messages_data.message+'</span></div>');
											if($messages_data.type == "unread")
											{
												document.getElementById("chat_message_audio").play();
												$("#chat_heading_"+$leads_data.customer_id).css("background-color","#ffa500");
											}
										}
										else
										{
											$global_user_image = "<?php echo $global_user_image_dir.$global_user_image_path; ?>";
											$("#chat_body_"+$leads_data.customer_id+"").append('<div class="sentbox_area"><a class="receiver_name_class" href="user_detail.php?user_id=2"><img height="25" width="25" src="'+$global_user_image+'"></a><span class="msg1">'+$messages_data.message+'</span></div>');
										}
									});	
									$("#id_of_last_message_stored_in_this_browser_"+$leads_data.customer_id).val($leads_data.id_of_last_message_stored_in_this_browser);				
									document.getElementById("chat_body_"+$leads_data.customer_id).scrollTop=document.getElementById("chat_body_"+$leads_data.customer_id).scrollHeight;	
								});
								//hb_ajax_get_online_friends_list();							
								
								if($response_in_object.response_text_for_unread_inbox_messages != '')
								{
									var $notification_string = "Unread message(s)<br />";
									$.each( $response_in_object.response_text_for_unread_inbox_messages, function( key, $inbox_messages_data ) {
										$notification_string = $notification_string + '<a href="send_to_msg_detail.php?message_id='+$inbox_messages_data.id+'&then_go=message_detail.php" class="white_anchor">'+$inbox_messages_data.count+') '+$inbox_messages_data.subject+'</a><br />'
									});
									$("#notification_container").html($notification_string);
									$("#notification_container").show();
									//document.getElementById("chat_message_audio").play();
								}
						
							// make a function free
							hb_ajax_get_online_friends_list_status = "idle";
						}
						else if($response_in_object.response_code == "Failure")
						{					
							
								console.log($response_in_object.response_text);
								
							// make a function free
							hb_ajax_get_online_friends_list_status = "idle";
							
								//console.log("calling get_chat_message again.");
								//hb_ajax_get_online_friends_list();
						}
						else if($response_in_object.response_code == "UserIsOffline")
						{
								console.log($response_in_object.response_text);
						
								// make a function free
								hb_ajax_get_online_friends_list_status = "idle";
							
								// Do nothing, just take rest, you will automatic know when user will come online.
						}
						else
						{						
							// make a function free
							hb_ajax_get_online_friends_list_status = "idle";
							
							alert("WEBSERVICE: "+$service_url+"\n\n There are some unexcepted errors in calling the service, please try again later or contact the administrator.");
						}
				}).fail(function() {
						
							// make a function free
							hb_ajax_get_online_friends_list_status = "idle";
							
					console.log("something unexpected happen.");
					//console.log("calling get_chat_message again.");
					//hb_ajax_get_online_friends_list();
				});
			}
			else
			{
				console.log("one instance of hb_ajax_get_online_friends_list() is already running");
			}
		}
		catch(e)
		{
			alert("Error: "+e);
		}
	}
	<?php
		if(isset($_SESSION["windows"]))
		{
			if(is_array($_SESSION["windows"]) && (!(empty($_SESSION["windows"]))))
			{
				foreach($_SESSION["windows"] as $key => $value)
				{
					if($value[2] == "maximized" || $value[2] == "minimized")
					{
						?>
						hb_open_chat_window("<?php echo $value[2]; ?>",<?php echo $value[0]; ?>,<?php echo $value[1]; ?>,'<?php echo $dont_call_hb_ajax_get_online_friends_list; ?>');
						<?php
					}				
				}
				if(count($_SESSION["windows"]) > 0)
				{
					?>
					hb_ajax_get_online_friends_list();
					<?php
				}
			}
		}
	?>
	setInterval("hb_ajax_get_online_friends_list()",3000);
</script>
<?php } ?>