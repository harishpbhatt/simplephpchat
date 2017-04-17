<?php
$HOST = $_SERVER['HTTP_HOST'];
if($HOST == 'server' || $HOST == '192.168.0.2' || $HOST == 'admin-pc')
{
	// Will be used in local server
	$SITE_URL="http://admin-pc/kitsolutions/vopeit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="#";	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "localhost";
	$DATABASENAME = "vopeit";
	$USERNAME = "root";
	$PASSWORD = "";
	$local = true;
	$development = false;
	$live = false;
	$global_show_full_errors = true;
	//$global_record_queries = true;
}
else if($HOST == 'www.paradiseinfosoft.com' || $HOST == 'paradiseinfosoft.com' || $HOST == 'demo.paradiseinfosoft.com')
{
	// Will be used in 'www.idealgrowthclients.com' server
	$SITE_URL="http://".$_SERVER['SERVER_NAME']."/demo/vorpit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://".$_SERVER['SERVER_NAME']."/demo/vorpit/";;	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "67.212.186.170";
	$DATABASENAME = "paradise_vorpit";
	$USERNAME = "paradise_vorpit";
	$PASSWORD = "7tVhLusP3,;i";
	$local = false;
	$development = true;
	$live = false;
	$global_show_errors = true;
}
else if($HOST == '119.18.55.52')
{
	// Will be used in 'www.idealgrowthclients.com' server
	$SITE_URL="http://".$_SERVER['SERVER_NAME']."/~paradis1/demo/vorpit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://".$_SERVER['SERVER_NAME']."/~paradis1/demo/vorpit/";	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "localhost";
	$DATABASENAME = "paradis1_vorpit";
	$USERNAME = "paradis1_cmnuser";
	$PASSWORD = "Z=;4qNa9snsT";
	$local = false;
	$development = true;
	$live = false;
	$global_show_errors = true;
}
else if($HOST == '67.212.186.170')
{
	// Will be used in 'www.idealgrowthclients.com' server
	$SITE_URL="http://".$_SERVER['SERVER_NAME']."/~paradise/demo/vorpit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://".$_SERVER['SERVER_NAME']."/~paradise/demo/vorpit/";	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "67.212.186.170";
	$DATABASENAME = "paradise_vorpit";
	$USERNAME = "paradise_vorpit";
	$PASSWORD = "7tVhLusP3,;i";
	$local = false;
	$development = true;
	$live = false;
	$global_show_errors = true;
}
else if($HOST == 'www.madniinfoway.com' || $HOST == 'madniinfoway.com' || $HOST == 'demo.madniinfoway.com')
{
	// Will be used in 'www.idealgrowthclients.com' server
	$SITE_URL="http://".$_SERVER['SERVER_NAME']."/demo/vorpit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://".$_SERVER['SERVER_NAME']."/demo/vorpit/";;	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	//$DBSERVER = "216.12.194.26";
	$DBSERVER = "localhost";
	$DATABASENAME = "madniinf_vorpit";
	$USERNAME = "madniinf_vorpit";
	$PASSWORD = "7tVhLusP3,;i";
	$local = false;
	$development = true;
	$live = false;
	$global_show_errors = true;
}
else if($HOST == '216.12.194.26')
{
	// Will be used in 'www.idealgrowthclients.com' server
	$SITE_URL="http://".$_SERVER['SERVER_NAME']."/~madniinf/demo/vorpit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://".$_SERVER['SERVER_NAME']."/~madniinf/demo/vorpit/";	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "216.12.194.26";
	$DATABASENAME = "madniinf_vorpit";
	$USERNAME = "madniinf_vorpit";
	$PASSWORD = "7tVhLusP3,;i";
	$local = false;
	$development = true;
	$live = false;
	$global_show_errors = true;
	$global_show_full_errors = true;
}else if($HOST == 'www.joinsocialbubble.com' || $HOST == 'joinsocialbubble.com')
{
	// Will be used in 'www.idealgrowthclients.com' server
	$SITE_URL="http://".$_SERVER['SERVER_NAME']."/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://".$_SERVER['SERVER_NAME']."/";	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "localhost";
	$DATABASENAME = "joinsocialbubble";
	$USERNAME = "jsbUser";
	$PASSWORD = "jsbPassword";

	$local = false;
	$development = true;
	$live = false;
	$global_show_errors = true;
	$global_show_full_errors = true;
}else
{
	// Will be used in 'live' server
	$SITE_URL="http://172.16.105.183/meteor/vopeit/";
	$MOBILE_SITE_URL=$SITE_URL."mobile/";
	$IA_SITE_URL=$SITE_URL."ia/";
	$WEBSERVICES_SITE_URL=$SITE_URL."webservices/";
	$MAIL_URL="http://172.16.105.183/meteor/vopeit/";	// The mail of this site will be sent only if the $SITE_URL matches the $MAIL_URL

	$DBSERVER = "localhost";
	$DATABASENAME = "vopeit";
	$USERNAME = "root";
	$PASSWORD = "root";
	$local = true;
	$development = false;
	$live = false;	//	 This variable is used in hb_filter_var() function
}
?>
