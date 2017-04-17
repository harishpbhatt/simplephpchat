<?php
$to = "boradashvin@gmail.com";
$sub = "Enquiry";
$body="How are you!";

//$cname=$_POST["txtname"];
//$cemail=$_POST["txtemailaddress"];
//$comments=$_POST["txtComments"];

require("class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = "mail.radhecandy.com"; // SMTP server
$mail->SMTPAuth = true;
$mail->Username = "radhecan";
$mail->Password = "admin123";

///$body="Name: " . $cname . "\nEmail: " . $cemail . "\nComments: " . $comments;

$mail->From = "info@radhecandy.com";
$mail->FromName = "radhecandy.com";

$mail->AddAddress($to);
$mail->Subject = $sub;
$mail->Body = $body;
$mail->WordWrap = 50;

if(!$mail->Send())
{
   echo 'Message was not sent.';
   echo 'Mailer error: ' . $mail->ErrorInfo;
}
else
{
   echo 'Message sent.';
}
?> 