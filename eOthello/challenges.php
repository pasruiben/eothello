<?php 

    include_once('inc/h_header.php');	
    include_once('inc/h_footer_body.php');
	include_once('userbox.php'); 

	if(isLoggedIn())
	{
		$id = $_SESSION['id_player'];
		
		try {
			$query = "SELECT id_challenge, challenger, rated, color, random, username, score FROM challenges, players WHERE challenged = '$id' AND id_player = challenger AND status = 'unanswered'";
			$stmt = $dbh->query($query);
			
			if($stmt->rowCount() > 0)
	        {
				echo '<table class="centered-table">
	                      <tr class="head-table">';
	            	                        
	            $columns = array('Challenger (Score)', 'Rated', 'Your colour', '<a href="random_opening.php">Random opening</a>', 'Accept?', 'Decline?');                                        
		        for ($i = 0; $i < count($columns); $i++) 
		            echo '<td>'.$columns[$i].'</td>';
		                            
		        echo '</tr>';
		            
		        //cuerpo de la tabla	            
		        $i = 1;						
				
				//muestra los retos y te permite aceptarlos o rechazarlos
				foreach ($stmt as $row)
				{
					echo '<tr class = "'.(($i % 2) ? "oddrow" : "evenrow").'">';
					echo '<td ><a href= "stats.php?player='.$row["challenger"].'">'.$row["username"].'</a> ('.$row['score'].')</td>';
                    echo '<td >'.(($row["rated"])?"Yes":"No").'</td>';
                    echo '<td >'.(($row["color"]=="White")?"Black":(($row["color"]=="Black")?"White":"Random")).'</td>';
                    echo '<td >'.(($row["random"])?"Yes":"No").'</td>';
					echo '<td><a href="accept_challenge.php?challenge='.$row['id_challenge'].'">Accept!</a></td>';
	                echo '<td><a href="decline_challenge.php?challenge='.$row['id_challenge'].'">Decline</a></td>';
					echo '</tr>';
					$i++; 
				}
	            
	            echo '</table>';
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			echo "error: ".$e->GetMessage();
		}
	}
	
?>

<?php
    include_once('inc/footer.php');
?>