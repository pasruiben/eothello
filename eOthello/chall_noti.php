<?php
session_start();include_once('inc/db_connect.php'); include_once('inc/misc.php');$cad = '';
$id = $_SESSION['id_player'];try {	    	//mostramos si hemos sido retados
		$query = "SELECT challenger FROM challenges WHERE challenged = '$id' AND status = 'unanswered'";
			
		$stmt = $dbh->query($query);
		$unanswered = $stmt->rowCount();        
	 
		if ($unanswered > 0)		
			$cad = "<p><a href='challenges.php'>You have $unanswered unanswered challenges.</a><br /></p>";
	        
	    //mostramos las respuestas a nuestros retos    
		$query = "SELECT status, username, id_player FROM challenges, players WHERE challenger = '$id' AND status <> 'unanswered' AND id_player = challenged";
		
	    $stmt = $dbh->query($query);
		
	    if ($stmt->rowCount() > 0)
	    {  
	        $cad .= '<p>';
	        foreach ($stmt as $row)        
	            $cad .= '<a href="stats.php?player='.$row['id_player'].'">'.$row['username'].'</a> ' . $row['status'] . ' your challenge.<br />';        
	        $cad .= '</p>';
	    }
	        
	    //borramos los retos que ya han sido respondidos y sobre los cuales ya hemos sido notificados
	    $query = "DELETE FROM challenges WHERE challenger = $id AND status <> 'unanswered'";
	    $dbh->exec($query);
	}
	catch(PDOException $e ) {
		// tratamiento del error
		die("error: ".$e->GetMessage());
	}
        
	echo $cad;



?>