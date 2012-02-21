<?php

    //error_reporting(E_ALL);
    session_start();
    include_once('inc/db_connect.php'); 
    include_once('inc/misc.php');
    include_once('inc/db_games.php');
 
	if(isLoggedIn() && isset($_REQUEST['id']) && isset($_REQUEST['last_act']))
	{ 
		$last_time = (int)$_REQUEST['last_act'];    
		$id_game = (int)$_REQUEST['id']; 
        $id_user = $_SESSION['id_player'];	

		
		if(exist_game($id_game))
		{
			$query = "SELECT id_game FROM games WHERE id_game = '$id_game' AND (black = '$id_user'  || white = '$id_user')";            
			try {
	            $stmt = $dbh->query($query);			                        
	            if ($stmt->rowCount() == 1)
	            {
				
					$query = "SELECT username, message, timestamp FROM chat, players WHERE id_player = id_user AND id_game = '$id_game' AND timestamp > $last_time ORDER BY timestamp ASC";
					$stmt = $dbh->query($query);

					if ($stmt->rowCount() > 0)
					{
						foreach ($stmt as $row)
						{
							echo "<p>".$row['username'] . " says: " .$row['message']."</p>";
						}
						echo '<--Last Act-->: '.$row['timestamp'];
					}
				
				}
				
			}
			catch(PDOException $e ) {
				// tratamiento del error
				echo "error: ".$e->GetMessage();
			}
			
		}
		
	}
	
	
?>