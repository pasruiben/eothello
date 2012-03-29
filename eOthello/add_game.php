<?php

include_once('inc/h_header.php');	
include_once('inc/h_footer_body.php'); 
include_once('inc/db_games.php');
include_once('userbox.php'); 

if(isLoggedIn())
{
	$id = $_SESSION['id_player'];

	if (isset($_REQUEST['color']) && isset($_REQUEST['rated']) && isset($_REQUEST['random']))
	{
		try 
		{
			//valor de rated
			$rated = 1;
			if ($_REQUEST['rated'] == "no")
			{
				$rated = 0;
			}
			
			//valor de random
			$random = 0;
			if ($_REQUEST['random'] == "yes")
			{
				$random = 1;
			}

			//valor de color
			$color = $_REQUEST['color'];
			if ($color == "White")
			{
				$color = "white";
			}
			else if ($color == "Black")
			{
				$color = "black";
			}
			else  //random
			{
				$color = "black, white";
				$id = $id."', '".$id;
			}
			
			$elo_min = $_REQUEST['eloMin'];
			$elo_max = $_REQUEST['eloMax'];
			
			$time = time();
			$array = init_board($random);
			
			$query = "INSERT INTO games ($color, board, turn, time, rated, moves, random_opening, elo_min, elo_max) 
					VALUE ('$id', '". $array['board'] ."', 'pending', $time, $rated, '". $array['sequence'] ."', $random, $elo_min, $elo_max)";
		
			if ($dbh->exec($query))
			{		
				echo '<p>Game successfully created.<br /><a href="games.php?cond=mine">See your games.</a></p>';
			}
			else
			{
				echo '<p>Error when trying to create the game. Please contact the site administrators.</p>';
			}
		}
		catch(PDOException $e) 
		{
			// tratamiento del error
			echo "error: ".$e->GetMessage();
		}
	}
}
else
{
	needLoggedIn();
}

include_once('inc/footer.php');  

?>