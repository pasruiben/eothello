<?php

session_start();

include_once ('../inc/misc.php');
include_once ('../inc/emails.php');
include_once ('../inc/db_connect.php');
include_once ('../inc/db_players.php');

if(isset($_REQUEST['pass']) && $_REQUEST['pass'] = "emailme")
{
	try
	{
		//para las que llevan más de 1 día se envían e-mails recordatorios
		$time = time() - 1*24*60*60;
		$query = "SELECT DISTINCT email FROM games, players WHERE games.time < $time AND ((turn = 'black' AND black = id_player) OR (turn = 'white' AND white = id_player)) ORDER BY id_player;";
		$stmt = $dbh->query($query);
		
		// si hay e-mails que mandar
		if ($stmt->rowCount() > 0)
		{
			//para cada e-mail
			foreach ($stmt as $row)
			{
				$to = $row["email"];
				$from = 'admin@eothello.com';
				$subject = 'It is your turn at eOthello.';
				$message = "It is your turn at one or more games on http://www.eothello.com/.
				Happy Othello!";
		
				if (sendMail($to, $subject, $message, $from))
				{
					echo "e-mail sent: $email<br />";
				}
			}
		}
	}
	catch(PDOException $e )
	{
		// tratamiento del error
		die("error: ".$e->GetMessage());
	}
}