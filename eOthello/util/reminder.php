<?php 

session_start();
include_once ('../inc/misc.php');

if(isset($_REQUEST['pass']) && $_REQUEST['pass'] = "emailme")
	{
    	//borramos chat de partidas cuyos jugadores hayan hecho clear (ambos)
    	$query = "DELETE FROM chat WHERE id_game IN (SELECT id_game FROM games WHERE clearb = 1 AND clearw = 1)";
		//cogemos las partidas que llevan m�s de 3 d�as (habr� q borrarlas)
		$query = "SELECT DISTINCT id_game, black, white, turn FROM games, players WHERE games.time < $time AND turn <> 'finished'";
		// Borramos todas las partidas antiguas
		if ($stmt->rowCount() > 0)
		{		
			foreach ($stmt as $row)
				$id_game = $row["id_game"];
				$white = $row["white"];
				echo $id_game;
				//si le tocaba al negro, negro pierde y blanco gana
				if ($turn == 'black')
					$query_black = "UPDATE players SET games_lost = games_lost + 1 WHERE id_player = $black";
				} //si le tocaba al blanco, blanco pierde y negro gana
				else if ($turn == 'white')
				{
				//y se borra la partida
		}	
		//para las que llevan m�s de 1 d�a se env�an e-mails recordatorios
		// si hay e-mails que mandar
			//para cada e-mail
			foreach ($stmt as $row)
		    {	
				$to = $row["email"];
				$from = 'eothelloadmin@eothello.com';
				$message = "It is your turn at one or more games on http://www.eothello.com/.
						Happy Othello!";
				if (sendMail($to, $subject, $message, $from))
				{
					   echo "e-mail sent: $email<br />";
				}
		}	
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("error: ".$e->GetMessage());
	}
}

?>