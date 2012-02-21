<?php
    //error_reporting(E_ALL);
    session_start();
    include_once('inc/db_connect.php'); 
    include_once('inc/db_games.php');
  
    if(isset($_REQUEST['id']))
	{                
		$id_game = (int)$_REQUEST['id'];        
		if(exist_game($id_game))
		{
			echo get_board($id_game);
		}
		else
		{
			echo 'N';
		}
	}
	else
        echo 'N';
    
?>