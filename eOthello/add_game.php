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
			$rated = true;
			if ($_REQUEST['rated'] == "no")
			{
				$rated = false;
			}
			
			//valor de random
			$random = false;
			if ($_REQUEST['random'] == "yes")
			{
				$random = true;
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
			
			$time = time();

			$sql = "INSERT INTO games ($color, turn, time, rated, random_opening) 
					VALUE ('$id', 'pending', $time, $rated, $random)";
			
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