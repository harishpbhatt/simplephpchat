<?php

	function HtmlMailSend($to,$subject,$mailcontent,$from)
	{
		include_once('class.phpmailer.php');
		$mail = new PHPMailer;
		return $mail->HtmlMailSend($to,$subject,$mailcontent,$from);
	}
	
	function SimpleMailSend($to,$subject,$mailcontent1,$from)
	{
		include_once('class.phpmailer.php');
		$mail = new PHPMailer;
		return $mail->SimpleMailSend($to,$subject,$mailcontent1,$from);
	}	
	
	function SendMail($to,$subject,$mailcontent,$from)
	{
		$array = explode("@",$from,2);
		$SERVER_NAME = $array[1];
		$username =$array[0];
		$fromnew = "From: $username@$SERVER_NAME\nReply-To:$username@$SERVER_NAME\nX-Mailer: PHP";
		@mail($to,$subject,$mailcontent,$fromnew);
	}
  
    function SendHTMLMail($to,$subject,$mailcontent,$from1,$cc="")
	{
			
			$limite = "_parties_".md5 (uniqid (rand()));
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			if ($cc != "")
			{
				$headers .= "Cc: $cc\r\n";
			}
			$headers .= "From: $from1\r\n";
			@mail($to,$subject,$mailcontent,$headers);
	}
	function sentmail($to,$subject,$mailcontent,$from)
	{
		
		require("mail7/class.phpmailer.php");
			
		$mail = new PHPMailer();

		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host = "mail.radhecandy.com"; // SMTP server
		$mail->SMTPAuth = true;
		$mail->Username = "admin";
		$mail->Password = "admin";
		
		///$body="Name: " . $cname . "\nEmail: " . $cemail . "\nComments: " . $comments;
		
		$mail->From = $from;
		$mail->FromName = "hydro.com";
		
		$mail->AddAddress($to);
		$mail->Subject = $subject;
		$mail->Body = $mailcontent;
		$mail->WordWrap = 50;
		
		if(!$mail->Send())
		{
			return false;
		}
		else
		{
		   return true;
		}
		
	}
?>