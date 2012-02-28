<?php 

session_start();
include_once ('../inc/misc.php');include_once ('../inc/emails.php');include_once ('../inc/db_connect.php');include_once ('../inc/db_players.php');

if(isset($_REQUEST['pass']) && $_REQUEST['pass'] = "emailme"){	try 
	{
    	//borramos chat de partidas cuyos jugadores hayan hecho clear (ambos)
    	$query = "DELETE FROM chat WHERE id_game IN (SELECT id_game FROM games WHERE clearb = 1 AND clearw = 1)";        $dbh->exec($query);
		//cogemos las partidas que llevan más de 3 días (habrá q borrarlas)		$time = time() - 3*24*60*60;		echo $time;		
		$query = "SELECT DISTINCT id_game, black, white, turn FROM games, players WHERE games.time < $time AND turn <> 'finished'";		$stmt = $dbh->query($query);
		// Borramos todas las partidas antiguas
		if ($stmt->rowCount() > 0)
		{		
			foreach ($stmt as $row)			{											
				$id_game = $row["id_game"];				$black = $row["black"];
				$white = $row["white"];				$turn = $row["turn"];
				echo $id_game;
				//si le tocaba al negro, negro pierde y blanco gana
				if ($turn == 'black')				{
					$query_black = "UPDATE players SET games_lost = games_lost + 1 WHERE id_player = $black";					$query_white = "UPDATE players SET games_won = games_won + 1 WHERE id_player = $white";							update_scores($black, $white, -1);
				} //si le tocaba al blanco, blanco pierde y negro gana
				else if ($turn == 'white')
				{					$query_black = "UPDATE players SET games_won = games_won + 1 WHERE id_player = $black";					$query_white = "UPDATE players SET games_lost = games_lost + 1 WHERE id_player = $white";						update_scores($black, $white, 1);				}
				//y se borra la partida				$query_game = "DELETE FROM games WHERE id_game = $id_game";				$dbh->exec($query_black);				$dbh->exec($query_white);				$dbh->exec($query_game);								}
		}	
		//para las que llevan más de 1 día se envían e-mails recordatorios		$time = time() - 1*24*60*60;		$query = "SELECT DISTINCT email FROM games, players WHERE games.time < $time AND ((turn = 'black' AND black = id_player) OR (turn = 'white' AND white = id_player)) ORDER BY id_player;";
		$stmt = $dbh->query($query);
		// si hay e-mails que mandar		if ($stmt->rowCount() > 0)		{		
			//para cada e-mail
			foreach ($stmt as $row)
		    {	
				$to = $row["email"];
				$from = 'admin@eothello.com';				$subject = 'It is your turn at eOthello.';
				$message = "It is your turn at one or more games on http://www.eothello.com/.
						Happy Othello!";
				if (sendMail($to, $subject, $message, $from))
				{
					   echo "e-mail sent: $email<br />";
				}			}
		}	
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("error: ".$e->GetMessage());
	}
}

?>