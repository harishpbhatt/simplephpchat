<?php session_start();
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once("config.inc.php");
// include_once($site_path."include/db_function.php");
include_once($site_path."Opentok-PHP-SDK-master/OpenTokSDK.php");
include_once($site_path."include/function.php");
include_once($site_path."include/sendmail.php");
include_once($site_path."include/tablename.php");
include_once($site_path."include/message.php");
include_once($site_path."fckeditor/fckeditor.php");
include_once($site_path."include/htaccess_paging.php");
// include_once($site_path."include/click_counter.php");

//................Paging file..............
include_once($site_path."include/newpaging.php");
$prs_pageing = new get_pageing_new();

	$select_paypal_query = "select * from `tbl_settings` where 1";
	$select_paypal_result = hb_mysql_query($select_paypal_query);
	$select_paypal_total = hb_mysql_num_rows($select_paypal_result);
	if($select_paypal_total > 0)
	{
		while($select_paypal_data = hb_mysql_fetch_object($select_paypal_result))
		{
			$select_paypal_data2[$select_paypal_data->setting_name] = $select_paypal_data->setting_value;
		}
	}

$x_Test_Request = hb_get_payment_mode();
if($x_Test_Request == "TRUE")
{
	/* paypal api settings started */
	$global_paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	$global_paypal_adaptive_url = "https://svcs.sandbox.paypal.com/AdaptivePayments/Preapproval";
	$global_paypal_preapproved_pay_url = "https://svcs.sandbox.paypal.com/AdaptivePayments/Pay";
	$global_paypal_refund_url = "https://svcs.sandbox.paypal.com/AdaptivePayments/Refund";
	$global_paypal_to_email_address = trim($select_paypal_data2["test_to_paypal_email_address"]);
	$global_paypal_security_user_id = trim($select_paypal_data2["test_security_user_id"]);
	$global_paypal_security_password = trim($select_paypal_data2["test_security_password"]);
	$global_paypal_security_signature = trim($select_paypal_data2["test_security_signature"]);
	$global_paypal_application_id = trim($select_paypal_data2["test_application_id"]);
	/* paypal api settings ended */
}
else
{
	/* paypal api settings started */
	$global_paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	$global_paypal_adaptive_url = "https://svcs.paypal.com/AdaptivePayments/Preapproval";
	$global_paypal_preapproved_pay_url = "https://svcs.paypal.com/AdaptivePayments/Pay";
	$global_paypal_refund_url = "https://svcs.paypal.com/AdaptivePayments/Refund";
	$global_paypal_to_email_address = trim($select_paypal_data2["to_paypal_email_address"]);
	$global_paypal_security_user_id = trim($select_paypal_data2["security_user_id"]);
	$global_paypal_security_password = trim($select_paypal_data2["security_password"]);
	$global_paypal_security_signature = trim($select_paypal_data2["security_signature"]);
	$global_paypal_application_id = trim($select_paypal_data2["application_id"]);
}

$global_offline_vorpit_fees_percentage = $select_paypal_data2["commision_offline"];
$global_online_vorpit_fees_percentage = $select_paypal_data2["commision_online"];
$global_broadcasting_charge_per_minute = $select_paypal_data2["charge_per_minute"];
$global_broadcasting_charge_mode = $select_paypal_data2["charge_mode"];

$global_tokbox_api_owner_email_address = $select_paypal_data2["tokbox_api_owner_email_address"];
$global_tokbox_api_key = $select_paypal_data2["tokbox_api_key"];
$global_tokbox_api_secret = $select_paypal_data2["tokbox_api_secret"];
$global_valid_hours_for_complaint = floatval($select_paypal_data2["valid_hours_for_complaint"]);
if($global_valid_hours_for_complaint <= "0")
{
	$global_valid_hours_for_complaint = 24;
}
	$global_valid_seconds_for_complaint = $global_valid_hours_for_complaint * 3600;

$global_sms_fees_amount = floatval($select_paypal_data2["sms_fees_amount"]);
$global_featured_event_video_price = hb_get_value("tbl_payment_settings","video_price","id",1);
$global_featured_event_fees_amount = hb_get_value("tbl_payment_settings","event_payment","id",1);
$global_feature_event_days = $select_paypal_data2["feature_event_days"];


//...........For Facebook connect..........

//................Paging file..............
//$prs_pageing = new get_htaccess_pageing();

$session_id = session_id();
hb_check_session_hijack();
$adminsetting=array();
$adminmail=GetValue("tbl_admin","email","id","1");
//$mailing_email=get_single_value(ADMIN,"extra_email","id = '1'");
//$contactno_o=get_single_value (ADMIN,'contactno_o','1=1');
//$fax=get_single_value (ADMIN,'contactno_f','1=1');

$cur_page_arr = explode("/",$_SERVER['PHP_SELF']);
$cur_page = $cur_page_arr[count($cur_page_arr)-1];
if($cur_page=="login.php" || $cur_page=="signup.php")
{

}
else
{
	$_SESSION["page"]=$cur_page;
}

//............search for the menu number [start].............
if (isset($menu) && count ($menu) > 0)
{
	foreach ($menu as $k=>$v)
	{
		if (is_array($v[2]) && count ($v[2]) > 0)
		{
			foreach ($v[2] as $k1=>$v1)
			{
				if ($v1[1]==$cur_page)
				{
					$menunum = $v[3];
					$leftmenu = $v1[2];
					break;
				}
			}
		}
	}
}

if(isset($_SESSION['seb_current_user_id']) && $_SESSION['seb_current_user_id'] > 0)
{
	$global_user_query = "select * from `tbl_customers` where 1 = 1 and `id` = '".$_SESSION['seb_current_user_id']."'";
	$global_user_result = hb_mysql_query($global_user_query);
	$global_user_total = hb_mysql_num_rows($global_user_result);
	if($global_user_total > 0)
	{
		while($global_user_data = hb_mysql_fetch_array($global_user_result))
		{
			$global_user_id = stripslashes($global_user_data["id"]);
			$global_company_id = stripslashes($global_user_data["id"]);
			$global_professional_id = stripslashes($global_user_data["id"]);
			$global_user_image_path = stripslashes($global_user_data["image_path"]);
			$global_user_first_name = stripslashes($global_user_data["first_name"]);
			$global_user_last_name = stripslashes($global_user_data["last_name"]);
			$global_user_name = $global_user_first_name." ".$global_user_last_name;
			$global_user_address = stripslashes($global_user_data["address"]);
			$global_user_city_id = stripslashes($global_user_data["city_id"]);
			$global_user_city_name = GetValue("tbl_cities","name","id",$global_user_city_id);
			$global_user_state_id = stripslashes($global_user_data["state_id"]);
			$global_user_state_name = GetValue("tbl_states","name","id",$global_user_state_id);
			$global_user_country_id = stripslashes($global_user_data["country_id"]);
			$global_user_country_name =GetValue("tbl_countries","name","id",$global_user_country_id);
			$global_user_email_address =stripslashes($global_user_data["email_address"]);
			$global_facebook_id =stripslashes($global_user_data["facebook_id"]);
			$global_facebook_access_token = stripslashes($global_user_data["facebook_access_token"]);
			$global_twitter_id = stripslashes($global_user_data["twitter_id"]);
			$global_twitter_access_token = stripslashes($global_user_data["twitter_oauth_token"]);
			$global_twitter_access_token_secret = stripslashes($global_user_data["twitter_oauth_token_secret"]);
			$global_twitter_screen_name = stripslashes($global_user_data["twitter_screen_name"]);
			$global_user_paypal_email_address = stripslashes($global_user_data["paypal_email"]);
			$global_user_preferred_sub_categories = stripslashes($global_user_data["preferred_sub_categories"]);
			$global_user_image_dir = "customer_images/";
			$global_user_type = "tbl_customers";

			if($global_user_image_path != "" && file_exists($global_user_image_dir.$global_user_image_path))
			{
				// Do nothing
				$global_user_image_dir = "customer_images/";
			}
			else
			{
				$global_user_image_dir = "images/";
				$global_user_image_path = "inbox_no_photo.png";
			}
		}
	}
}

//echo "access tocken=".$global_facebook_access_token;exit;

$GBV_AUTOMATED_FROM_NAME = "noreply@socialeventbubble.com";
$GBV_MAILS_ENABLED = true;

include_once("customer_join_me_urls.php");

$tables_with_status_column = array();
$global_site_name = "Social Event Bubble";

/* timezone operations started */
date_default_timezone_set("America/New_York");
$global_browser_timezone_offset_in_minutes = $_COOKIE["Browser_Timezone_Offset_In_Minutes"];
$global_browser_timezone_offset_in_seconds = $global_browser_timezone_offset_in_minutes * 60;
$global_server_timezone_offset_in_seconds = date("Z",time());
/* timezone operations ended */

	extract($_REQUEST);

	if($development || $live)
	{
	/* facebook coding started */
	include_once($site_path."connectfb/fbconfig.php"); // Include fbconfig.php file
	/* facebook coding ended */

	/* Twitter connect coding started */
	//require_once('connecttw/twitteroauth.php');
	//require_once('connecttw/twconfig.php');
	include_once($site_path."connecttw/index.php");

	/* Twitter connect coding ended */
	}
?>
