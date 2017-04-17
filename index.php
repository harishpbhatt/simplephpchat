<?php
	include_once('include/connect.php');// we need to show
  $global_user_id = 1;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<?php
	if($global_user_id > 0)
	{
		hb_SetValue("tbl_contacts","sender_last_activity_on","sender_id",$global_user_id,date("Y-m-d H:i"));
		hb_SetValue("tbl_contacts","receiver_last_activity_on","receiver_id",$global_user_id,date("Y-m-d H:i"));
	}
	include_once("chatting_script.php");
 ?>
