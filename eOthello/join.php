<?php include_once('inc/h_header.php');	?>

<?php include_once('inc/h_footer_body.php'); ?>
	
<?php include_once('userbox.php'); ?>

<?php
	
	if(isLoggedIn() && isset($_REQUEST['id_game']))
	{	
		$id = $_SESSION['id_player'];
		$id_game = (int)$_REQUEST['id_game'];
		
		$query = "SELECT id_game FROM games WHERE id_game = '$id_game' AND turn = 'pending' AND black <> '$id'";
	
		try {
            $stmt = $dbh->query($query);
			if ($stmt->rowCount() == 1)
			{
				if(num_active_games($id)<=19)
				{
					if(join_game($id_game,$id))
					{
						echo 'Game succesfully joined.<br />';
						echo '<a href="game.php?id='.$id_game.'&mode=player">Go!</a>';
					}
					else
					{			
						echo 'Error when attempting to join the game.';	
					}
				}
				else
				{
					echo 'You can\'t have more than 20 unfinished games.';
				}
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("error: ".$e->GetMessage());
		}
		
	}

?>

<?php include_once('inc/footer.php'); ?>