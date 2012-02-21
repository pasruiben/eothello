<?php
$mail = false; // enviar mail de activación
function sendLostPasswordEmail($username, $email, $newpassword)
{
	$ret = false;
	$from = 'admin@eothello.net';
	$subject = 'Your password has been reset.';
	$message = "You have requested a new password on http://www.eothello.com/,
    Your new password information:
        username:  $username
        password:  $newpassword
    Regards Administration";
	if (sendMail($email, $subject, $message, $from))
		$ret = true;
	return $ret;
}
function sendActivationEmail($username, $password, $uid, $email, $actcode)
{
	$ret = false;
	$from = 'admin@eothello.com';
	$subject = 'Please activate your acccount.';
	$link = "http://www.eothello.com/activate.php?uid=$uid&actcode=$actcode";
	$message = "
        Thank you for registering on http://www.eothello.com/,
        Your account information:
            username:  $username
            password:  $password
        Please click the link below to activate your account.
        $link
        Regards Administration";        	if (sendMail($email, $subject, $message, $from))
  	$ret = true;  	return $ret;
}
function sendMail($to, $subject, $message, $from)
{
	$from_header = "From: $from";
	$ret = false;
	if (mail($to, $subject, $message, $from_header))
		$ret = true;
	return $ret;
}
?>