<?php

    //error_reporting(E_ALL);
    session_start();
    include_once('inc/db_connect.php'); 
    include_once('inc/misc.php');
    include_once('inc/db_games.php');

    if (isLoggedIn())
    { 		
		if(isset($_REQUEST['id']) && isset($_REQUEST['msg']))
		{ 
			$id_player = $_SESSION['id_player'];
			$id_game = (int)$_REQUEST['id'];   
			
			$msg = htmlentities($_REQUEST['msg']);	
			
			if(get_magic_quotes_gpc()) 
			{
				$msg = stripslashes($msg);
			}
		
			//echo $msg . "\n";	
			
			if(exist_game($id_game) && $msg != "")
			{					
				$query = "SELECT id_game FROM games WHERE id_game = '$id_game' AND (black = '$id_player'  || white = '$id_player')";            
				try {
					$stmt = $dbh->query($query);			                        
					if ($stmt->rowCount() == 1)
					{						
						$query = "INSERT INTO chat (id_game, id_user, message, timestamp) VALUE (?, ?, ?, ?)";
						try						
						{							
							$query_obj = $dbh->prepare($query);		
							$query_obj->execute(array($id_game, $id_player, $msg, time()));		
							echo "send";											
						}						
						catch(PDOException $e) { }						
					}
					
				}
				catch(PDOException $e ) {
					// tratamiento del error
					echo "error: ".$e->GetMessage();
				}
			
			}
			
		}
	}
	
		
?>