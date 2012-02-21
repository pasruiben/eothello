<?php 

	session_start();
	include_once ('inc/misc.php');
	include_once ('inc/emails.php');
	include_once ('inc/db_connect.php');
	
	//if(isset($_REQUEST['pass']) && $_REQUEST['pass'] = "emailme")
	{
		try {
	        $query = "SELECT id_game, board FROM games WHERE turn = 'finished'";
	        $stmt = $dbh->query($query);
			
			if ($stmt->rowCount() > 1)
			{		
		        foreach ($stmt as $row)
		        {	
					//cogemos la board y el id_game
					$board = $row['board'];
					$id_game = $row['id_game'];
					
					//contamos el número de piezas negras y blancas
					$black = 0;
					$white = 0;
					
					for($i = 0; $i < strlen($board); $i++)
					{
						if ($board[$i] == 'B')
							$black += 1;
						else if ($board[$i] == 'W')
							$white += 1;
					}
					
					if ($black > $white)
						$sql = "UPDATE games SET winner = 'black' WHERE id_game = '$id_game'";
					else if ($black < $white)
						$sql = "UPDATE games SET winner = 'white' WHERE id_game = '$id_game'";
					else
						$sql = "UPDATE games SET winner = 'draw' WHERE id_game = '$id_game'";

					$dbh->exec($sql);
					
					echo $row['board']." Black $black, White $white".'<br />';
				}
			}	
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("error: ".$e->GetMessage());
		}
	}
?>