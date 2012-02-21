<?php include_once('inc/h_header.php');	?>

<script type="text/javascript" src="js/othello.js"></script>

<?php include_once('inc/h_footer_body.php'); ?>
	
<?php include_once('userbox.php'); ?>

<?

    if (isset($_REQUEST['id']) && isset($_REQUEST['mode']))
	{
		$id_game = (int)$_REQUEST['id'];
        $mode = $_REQUEST['mode'];
    }
    else
        return;

	if (!isLoggedIn())
        echo '<div class="lhome"><a href= "./index.php">Index</a></div>';        
    
	if (isLoggedIn() || $mode == 'spectator')
	{
        if ($mode == 'spectator')
        {
            $id_user = 0;
            $query = "SELECT board, turn, moves FROM games WHERE (black <> 0 AND white <> 0) AND id_game = '$id_game'";        
        }
        else
        {        
            $id_user = $_SESSION['id_player'];	
			$username = $_SESSION['username'];	
			$query = "SELECT board, turn, moves FROM games WHERE (black <> 0 AND white <> 0) AND id_game = '$id_game' AND (black = '$id_user' || white = '$id_user')";
        }        
        
		try {
			$stmt = $dbh->query($query);								
			if ($stmt->rowCount() == 1)
			{			
				$row = $stmt->fetch();			
					
	            //obtiene información sobre el jugador negro
			    $query = "SELECT username, id_player FROM players, games WHERE id_player = black AND id_game = '$id_game'";
				$stmt = $dbh->query($query);								
				if ($stmt->rowCount() == 1)		
					$black = $stmt->fetch();								
				
	            //obtiene información sobre el jugador blanco
				$query = "SELECT username, id_player FROM players, games WHERE id_player = white AND id_game = '$id_game'";
				$stmt = $dbh->query($query);								
				if ($stmt->rowCount() == 1)		
					$white = $stmt->fetch();
	                            
	            //damos un valor al rol del jugador en la partida
	            $role = 0;            
	            if ($black['id_player'] == $id_user)                
	                $role = 1; //black                                                                                       
	            else if ($white['id_player'] == $id_user)                
	                $role = 2; //white         
								
	            //damos un valor al turno
	            $yourTurn = 0;      
				$turnString = 'Opponent\'s turn.';
	            if (($row['turn'] == 'white' && $white['id_player'] == $id_user) || ($row['turn'] == 'black' && $black['id_player'] == $id_user))                                   
				{
	                $yourTurn = 1;                                 
					$turnString = 'Your turn.';
				}     
				else if($row['turn'] == 'finished')
					$turnString = 'Game finished.';
				
				if ($role == 0)
					$turnString = 'Watching.';
				
				$f_board = $row['board'];
				
				//construimos la cadena de movimientos
				$moves = $row['moves'];
				$moves_string = '';
				
				for($i = 0; $i < strlen($moves); $i += 2)
				{
					$moves_string .= ($i/2+1).'. '.$moves[$i].$moves[$i+1].' ';
					if (($i + 2) % 20 == 0)
						$moves_string .= '<br />';
				}

								
	            echo '
	                <table class = "centered-table-aux">
	                    <tr>
	                        <td></td>
	                        <td>Black</td>
	                        <td>Pieces</td>
	                        <td>Status</td>
	                        <td>White</td>
	                        <td>Pieces</td>
	                        <td></td>
	                    </tr>
	                    
	                    <tr>
	                        <td></td>
	                        <td><a href = "stats.php?player='.$black['id_player'].'">'.$black['username'].'</a></td>
	                        <td><div id="pblack"></div></td>
	                        <td><div id="turn"></div></td>
	                        <td><a href = "stats.php?player='.$white['id_player'].'">'.$white['username'].'</a></td>
	                        <td><div id="pwhite"></div></td>
	                        <td></td>
	                    </tr>';
                    
				if($row['turn'] != 'finished' || $role != 0)
				{
					echo '<tr>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td>';
							
					echo '  <script type="text/javascript">	
								ini_board("'.$f_board.'", "'.$id_game.'", "'. $role . '", "'. $yourTurn. '", "' . $turnString . '","'.$moves.'","'.$username.'");
								connect_game();
							</script>';
							
					echo '			</td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                    </tr>';
					echo '</table>';
						
				}
				else
				{	
					echo '<tr>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td>';
							
					echo '<script type="text/javascript">ini_offline_game("'.$moves.'", "' . $turnString . '");</script>'	;
					echo '</td>
							<td></td>
	                        <td></td>
	                        <td></td></tr></table>';
					
				}
				
				echo '<div id="MOVES_INTERACTIVE"></div>';				echo '<script type="text/javascript">showOffLineGameMenu();</script>'	;								
				
				if($row['turn'] == 'finished' &&  ($role == 1 || $role == 2))
				{
					echo '<script type="text/javascript">run_game_end();</script>'	;
				}
				
				echo '<table class = "centered-table">						
						<tr>
	                        <td><div id="moves">'.
	                        $moves_string
	                        .'</div></td>							
	                    </tr>
						</table>';
	
				if($mode == "player")
				{
					echo '
					<p><center><div class="iedivh"><div id="CHAT" class="chat"></div></div></center><p>

					<p><br /></p>

					<p><center><input onfocus="clearinputmsg(this);" onblur="setinputmsg(this);" onkeypress="pressedEnter(event, this);" value="Press enter to send..." size=58 /></center></p>
					<div id="LOAD"></div>
					<p><br /></p>';  
				}
					
			}
			else
			{
				echo 'Not a valid game.<br />';
			}	
		}
		catch(PDOException $e ) {
			// tratamiento del error
			echo "error: ".$e->GetMessage();
		}
	}
    
?>
      
<?php include_once('inc/footer.php'); ?>
