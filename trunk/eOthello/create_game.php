<?php include_once('inc/h_header.php');include_once('inc/h_footer_body.php');include_once('userbox.php'); 
if(isLoggedIn())
{
	$id = $_SESSION['id_player'];
	if(num_active_games($id)<=19)
	{		echo '<div align="center">				<form action="./add_game.php" method="post"> 					<label id = "userlabel" for="rated">Rated game:</label>					<select name="rated">						<option selected value="yes">Yes</option>						<option value="no">No</option>				 	</select>                    <br /><br />					<a href="random_opening.php">						<label id = "userlabel" for="rated">Random opening:</label>					</a>					<select name="random">						<option selected value="no">No</option>		                               						<option value="yes">Yes</option>					</select>					<br /><br />                      <label id = "userlabel" for="color">Play as:</label>					<select name="color">						<option value="Black">Black</option>						<option value="White">White</option>                       	<option selected value="Random">Random</option>					</select>					<br />					<input name="challenge" type="submit" value="Create!" class="button" />				</form>			</div>';
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