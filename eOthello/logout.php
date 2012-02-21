<?php
include_once('inc/db_connect.php');
include_once('inc/db_players.php');
include_once('inc/misc.php');
global $seed;
$cookie = $_COOKIE['ident'];
$ident = XORDecrypt($cookie, $seed);
$data_array = split(":",$ident);
$user_id = $data_array[1];
SetLoggedIn($user_id, 0);
session_start();
unset($_COOKIE["ident"]);
if(!session_unregister('id_player') || !session_unregister('username'))
{
	unset($_SESSION['id_player']);
	unset($_SESSION['username']);
}session_destroy();header('Location: index.php');
?>