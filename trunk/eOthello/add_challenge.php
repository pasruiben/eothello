<?php include_once('inc/h_header.php');	include_once('inc/h_footer_body.php'); include_once('userbox.php'); 
if(isLoggedIn())
{
	$id = $_SESSION['id_player'];
	if (isset($_REQUEST['challenged']) && isset($_REQUEST['color']) && isset($_REQUEST['rated']))
	{
		$challenged = (int)$_REQUEST['challenged'];
		//controlamos que no se rete a sí mismo
		if ($challenged == $id)		{
			echo '<p>You can\'t challenge yourself.</p>';
		}		else
		{
			try 			{
				$query = "SELECT challenger FROM challenges WHERE challenger = '$id' AND challenged = '$challenged' AND status = 'unanswered'";
				$stmt = $dbh->query($query);
				//controlamos que no rete a alguien con quien ya tiene un reto pendiente
				if ($stmt->rowCount() != 0)				{
					echo '<p>You can\'t challenge the same person twice.<br />';
				}				else
				{
					//valor de rated
					$rated = true;
					if ($_REQUEST['rated'] == "no")
					{						$rated = false;					}					
					//valor de random
					$random = false;
					if ($_REQUEST['random'] == "yes")					{						$random = true;					}
					//valor de color
					$color = "random";
					if ($_REQUEST['color'] == "White")					{
						$color = "White";
					}					else if ($_REQUEST['color'] == "Black")
					{						$color = "Black";					}
					$query = "INSERT INTO challenges (challenger, challenged, status, rated, color, random) VALUE ('$id', '$challenged', 'unanswered', '$rated', '$color', '$random')";
					if ($dbh->exec($query))
					{
						$query = "SELECT username FROM players WHERE id_player = '$challenged'";
						$stmt = $dbh->query($query);
						$row = $stmt->fetch();
							
						echo "<p>You successfully challenged <a href='stats.php?player=$challenged'>".$row['username']."</a>, you will be notified when <a href='stats.php?player=$challenged'>".$row['username']."</a> replies to your challenge.<br /></p>";
					}
					else					{
						echo '<p>Error when trying to add the challenge. Please contact the site administrators.</p>';
					}				}			}
			catch(PDOException $e) 			{
				// tratamiento del error
				echo "error: ".$e->GetMessage();
			}
		}
	}
}
else{
	needLoggedIn();}
include_once('inc/footer.php');  ?>