<?php include_once('inc/h_header.php');include_once('inc/h_footer_body.php');include_once('userbox.php'); 
if(isLoggedIn())
{
	$id = $_SESSION['id_player'];
	if(num_active_games($id)<=19)
	{
		if(create_new_game($id))
		{
			echo '<p>Game successfully created.<br /><a href="games.php?cond=mine">See your games.</a></p>';
		}
		else
		{
			echo '<p>Error when trying to create the game. Please contact the site administrators.</p>';
		}
	}
	else
	{
		echo '<p>You can\'t have more than 20 unfinished games.<br /><a href="games.php?cond=mine">See your games.</a></p>';
	}
}
else
{
	echo '<div class="lhome"><a href= "./index.php">Index</a></div><br />';
	needLoggedIn();
}include_once('inc/footer.php');  ?>