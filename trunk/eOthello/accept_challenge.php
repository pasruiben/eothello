<?php
include_once('inc/h_header.php');
include_once('inc/h_footer_body.php');
include_once ('updateboard.php');
$message = '';
if(isLoggedIn() && isset($_REQUEST['challenge']))
{
	$challenge = (int)$_REQUEST['challenge'];	
	try 	{
		//obtenemos los id de los 2 jugadores que van a formar la nueva partida (retador y retado)
		$query = "SELECT challenger, challenged, rated, color, random FROM challenges WHERE id_challenge = '$challenge' AND status = 'unanswered'";
		$stmt = $dbh->query($query);
		//comprobamos que de verdad existe el reto y que está sin aceptar
		if ($stmt->rowCount() == 1)
		{
			$row = $stmt->fetch();
			$challenger = $row['challenger'];
			$challenged = $row['challenged'];
			$rated = $row['rated'];
			$color = $row['color'];
			$random = $row['random'];
			$sequence = "";
			// y que de verdad eres el retado en ese reto
			if ($challenged == $_SESSION['id_player'])
			{
				//comprobamos que el retador no tenga más de 19 partidas en juego
				$query = "SELECT id_game FROM games WHERE (black = '$challenger' OR white = '$challenger') AND turn <> 'finished'";
				$stmt = $dbh->query($query);
				if ($stmt->rowCount() > 19)
				{
					$message = '<p>Can\'t accept the challenge because the challenger has too many active games.<br />
	                          <a href="games.php?cond=mine">See your games.</a></p>';
				}
				else
				{
					//comprobamos que el retado no tenga más de 19 partidas en juego
					$query = "SELECT id_game FROM games WHERE (black = '$challenged' OR white = '$challenged') AND turn <> 'finished'";
					$stmt = $dbh->query($query);					
					if ($stmt->rowCount() > 19)
					{
						$message = '<p>Can\'t accept the challenge because you have too many active games.<br />
	                              <a href="games.php?cond=mine">See your games.</a></p>';
					}
					else
					{
						//comenzamos la transacción
						$dbh->beginTransaction();
							
						//actualizamos el estado del reto
						$query = "UPDATE challenges SET status = 'accepted' WHERE id_challenge = '$challenge'";
						$dbh->exec($query);
						//creamos la partida
						//estado inicial del tablero
						$board = 'EEEEEEEEEEEEEEEEEEEEEEEEEEEWBEEEEEEBWEEEEEEEEEEEEEEEEEEEEEEEEEEE';
						//si la partida es de apertura aleatoria, hay que elegir una apertura y actualizar el tablero
						if ($random)
						{
							//obtiene número de aperturas aleatorias
							$query = "SELECT count(*) as num FROM openings";
							$stmt = $dbh->query($query);
							if ($stmt->rowCount() == 1)
							{
								$row = $stmt->fetch();
								$numOpenings = $row['num'];								
								//elige una aleatoriamente
								$opening = rand(1, $numOpenings);
								//y obtiene la secuencia
								$query = "SELECT sequence FROM openings WHERE id = $opening";
								$stmt = $dbh->query($query);
								if ($stmt->rowCount() == 1)
								{
									$row = $stmt->fetch();
									$sequence = $row['sequence'];
									//inicializa el array del tablero
									$boardArray = SetBoardState($board);
									$turn = 'black';
									//aplica los movimientos
									for ($mov = 0; $mov < strlen($sequence) / 2; $mov++)
									{
										$columna = substr($sequence, $mov * 2, 1);
										$fila = substr($sequence, $mov * 2 + 1, 1);
										$x = ord($columna) - ord('a');
										$y = $fila - 1;
										$boardArray = FlipPieces($x, $y, $turn, $boardArray);										
										if ($turn == 'black')
											$turn = 'white';
										else
											$turn = 'black';
									}
									//obtiene la nueva cadena
									$board = GetBoardState($boardArray);
								}
							}
						}
						//el turno se lo asignamos al jugador negro (ya que conocemos los 2 jugadores)
						$turn = 'black';						
						$time = time();
						if ($color == "Black" || ($color == "Random" && rand(1, 100) >= 50))
							$query = "INSERT INTO games (black, white, board, turn, time, rated, moves, random_opening) VALUE ('$challenger', '$challenged', '$board', '$turn', $time, $rated, '$sequence', $random)";
						else
							$query = "INSERT INTO games (black, white, board, turn, time, rated, moves, random_opening) VALUE ('$challenged', '$challenger', '$board', '$turn', $time, $rated, '$sequence', $random)";
						$dbh->exec($query);
						$message = '<p>Challenge successfully accepted.<br />
	                              <a href="games.php?cond=mine">See your games.</a></p>';
						//fin de la transacción
						$dbh->commit();
					}
				}
			}
		}
	}
	catch(PDOException $e ) 	{
		//rollback
		$dbh->rollback();
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