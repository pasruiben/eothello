<?php 
    
    include_once('inc/h_header.php');	
    include_once('inc/h_footer_body.php');    
	
	$message = '';
	
	if(isLoggedIn() && isset($_REQUEST['challenge']))
	{
		$challenge = (int)$_REQUEST['challenge'];

		try {
	        //obtenemos los id de los 2 jugadores que van a formar la nueva partida (retador y retado)
	        $query = "SELECT challenger, challenged FROM challenges WHERE id_challenge = '$challenge'";
			$stmt = $dbh->query($query);
	        
	        //comprobamos que de verdad existe el reto
	        if ($stmt->rowCount() == 1)
	        {
	            $row = $stmt->fetch();
	        
	            $black = $row['challenger'];
	            $white = $row['challenged']; 

	            // y que de verdad eres el retado en ese reto
	            if ($white == $_SESSION['id_player'])
	            {        
	                //actualizamos el estado del reto
	                $query = "UPDATE challenges SET status = 'declined' WHERE id_challenge = '$challenge'";                
	                if ($dbh->exec($query))
	                    $message = '<p>Challenge declined.</p>';                  
	                else
	                    $message = '<p>Error when trying to accept the challenge. Please contact the site administrators.</p>';                  
	            }
	        }
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("error: ".$e->GetMessage());
		}
	}
    else if (!isLoggedIn())
    {    	
		$message = '<p><div class="lhome"><a href= "./index.php">Index</a></div><br /></p>';
        needLoggedIn();   
    }

	include_once('userbox.php');  

	echo $message;
	
	include_once('inc/footer.php');  

?>