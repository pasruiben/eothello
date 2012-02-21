<?php
$seed = "irjPftXjUR3o"; // the seed for the passwords
function isLoggedIn()
{
	$ret = false;
	if (session_is_registered('id_player') && session_is_registered('username'))
		$ret = true;
	return $ret;
}
function generate_code($length = 10)
{
	$code = "";
	$chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	srand((double)microtime() * 1000000);
	for ($i = 0; $i < $length; $i++)	{
		$code = $code . substr($chars, rand() % strlen($chars), 1);
	}	
	return $code;
}
function get_formated_duration($time_in_sec) {
	//si es menos de una hora
	if ($time_in_sec < 60*60) 	{
		$time_min = round(($time_in_sec/60),0);
		$formated_time = ($time_min < 10 ? "0".$time_min : $time_min)." min";
	}
	//si es menos de un día
	else if ($time_in_sec < 60*60*24) 	{
		$time_hour = floor($time_in_sec/(60*60));
		$time_min = fmod($time_in_sec,60*60)/60;
		$time_min = round($time_min);
		$formated_time = $time_hour." h ".($time_min < 10 ? "0".$time_min : $time_min)." min";
	}
	//si es menos de un año
	else if ($time_in_sec < 60*60*24*365) 	{
		$time_day = floor($time_in_sec/(60*60*24));
		$time_hour = floor(($time_in_sec-$time_day*60*60*24)/(60*60));
		$time_min = round(($time_in_sec-($time_hour*60*60)-($time_day*60*60*24))/60,0);
		$formated_time = $time_day." day".($time_day > 1 ? "s" : "")." ".$time_hour." h ".($time_min < 10 ? "0".$time_min : $time_min)." mins";
	}
	else
		$formated_time = "Too long";
	return $formated_time;
}
function XOREncryption($InputString, $KeyPhrase){
	$KeyPhraseLength = strlen($KeyPhrase);	// Loop trough input string
	for ($i = 0; $i < strlen($InputString); $i++)	{
		// Get key phrase character position
		$rPos = $i % $KeyPhraseLength;		
		// Magic happens here:
		$r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);
		// Replace characters
		$InputString[$i] = chr($r);
	}	
	return $InputString;
}
// Helper functions, using base64 to
// create readable encrypted texts:
function XOREncrypt($InputString, $KeyPhrase){
	$InputString = XOREncryption($InputString, $KeyPhrase);
	$InputString = base64_encode($InputString);
	return $InputString;
}
function XORDecrypt($InputString, $KeyPhrase){
	$InputString = base64_decode($InputString);
	$InputString = XOREncryption($InputString, $KeyPhrase);
	return $InputString;
}
?>