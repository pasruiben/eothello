<?php include_once('inc/h_header.php');include_once('inc/h_footer_body.php');include_once('userbox.php');
	
if(isLoggedIn()){	$id = $_SESSION['id_player'];		if (isset($_REQUEST['challenged']))		{				$challenged = (int)$_REQUEST['challenged'];
		//controlamos que no se rete a sí mismo		if ($challenged == $id)		{			echo '<p>You can\'t challenge yourself.</p>';		}		else		{			try 			{				$query = "SELECT challenger FROM challenges WHERE challenger = '$id' AND challenged = '$challenged' AND status = 'unanswered'";				$stmt = $dbh->query($query);
				//controlamos que no rete a alguien con quien ya tiene un reto pendiente				if ($stmt->rowCount() != 0)				{					echo '<p>You can\'t challenge the same person twice.<br />';				  				}				else				{							$query = "SELECT username FROM players WHERE id_player = '$challenged'";					$stmt = $dbh->query($query);					$row = $stmt->fetch();					echo "<p class='centered'>You are about to challenge <a href='stats.php?player=$challenged'>".$row['username']."</a>:</p>";					echo '<div align="center">							<form action="./add_challenge.php?challenged='.$challenged.'" method="post"> 								<label id = "userlabel" for="rated">Rated game:</label>								<select name="rated">									<option selected value="yes">Yes</option>									<option value="no">No</option>							 	</select>		                        	<br /><br />								<a href="random_opening.php">									<label id = "userlabel" for="rated">Random opening:</label>								</a>								<select name="random">									<option selected value="no">No</option>		                               									<option value="yes">Yes</option>								</select>								<br /><br />  
                            	<label id = "userlabel" for="color">Play as:</label>								<select name="color">									<option value="Black">Black</option>									<option value="White">White</option>	                               	<option selected value="Random">Random</option>								</select>								<br />								<input name="challenge" type="submit" value="Challenge!" class="button" />							</form>						</div>';
				}
			}
			catch(PDOException $e ) 			{				// tratamiento del error				echo "error: ".$e->GetMessage();
			}
		}
	}
}else{	needLoggedIn();   }
include_once('inc/footer.php');  	?>