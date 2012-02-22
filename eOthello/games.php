<?php

include_once('inc/h_header.php');
include_once('inc/h_footer_body.php');
include_once('userbox.php');
//recoge la condicion que las partidas deben cumplir para ser mostradas
if (isset($_REQUEST['cond']))
{
	$condition = $_REQUEST['cond'];

	if(!isLoggedIn())
	{
		echo '<div class="lhome"><a href= "./index.php">Index</a></div>';	}
	
	$needLoggedIn = false;
	$constant = '';
	$web = '';
	switch($condition)
	{
		case 'current':
			$needLoggedIn = false;
			$constant = 'CGAMES'; //completed games
			$web = 'games_aux.php?cond=current&page=1';
			break;
		case 'finished':
			$needLoggedIn = false;
			$constant = 'FGAMES'; //finished games
			$web = 'games_aux.php?cond=finished&page=1';
			break;
		case 'pending':
			$needLoggedIn = true;
			$constant = 'PGAMES'; //pending games
			$web = 'games_aux.php?cond=pending&page=1';
			break;
		case 'mine':
			$needLoggedIn = true;
			$constant = 'MGAMES'; //my games
			$web = 'games_aux.php?cond=mine';
			break;
	}
	if (!isLoggedIn() && $needLoggedIn)
	{
		needLoggedIn();
	}
	else if ($constant != '')
	{
		echo '<div id="'.$constant.'"></div>';
		echo '<script type="text/javascript">Start(\''.$constant.'\',\''.$web.'\');</script>';
	}
	else
	{
		echo '<p>Unknown value for cond variable.</p>';
	}
}
include_once('inc/footer.php'); ?>