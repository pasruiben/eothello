<?php
//error_reporting(E_ALL);
ob_start();
session_start();
include_once ('inc/db_connect.php');
include_once('inc/db_players.php');
include_once('inc/db_games.php');
include_once('inc/emails.php');
include_once ('inc/validate.php');
include_once ('inc/misc.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">	<head>		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />		<link rel="stylesheet" type="text/css" href="css/style.css" />		<link rel="shortcut icon" href="favicon.ico" />		<title>eOthello</title>