<?php

    //error_reporting(E_ALL);
    session_start();
    include_once('inc/db_connect.php'); 
    include_once('inc/misc.php');
	include_once('inc/db_games.php');
	
	if(isLoggedIn() && isset($_REQUEST['id']))
	{
		$id_player = $_SESSION['id_player'];
		$id_game = (int)$_REQUEST['id'];
        
		clear_game($id_game,$id_player);

		//vuelve a mostrar tus partidas
		header('Location: games.php?cond=mine');
    }

?>