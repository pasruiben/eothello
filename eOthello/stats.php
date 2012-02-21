<?php
include_once('inc/h_header.php');
include_once('inc/h_footer_body.php');
include_once('userbox.php');
if(isset($_REQUEST['player']))
{
	try	{
		$player = (int)$_REQUEST['player'];
		$query = "SELECT id_player, username, games_draw, games_lost, games_won, score, time FROM players WHERE id_player = '$player'";
		$stmt = $dbh->query($query);		
		if(!isLoggedIn())
			echo '<div class="lhome"><p><a href= "./index.php">Index</a></p><br /></div>';
		//si existe el jugador
		if ($stmt->rowCount() == 1)
		{
			$row = $stmt->fetch();
			$total = $row["games_won"] + $row["games_lost"] + $row["games_draw"];			//si ha jugado al menos una partida mostramos el diagrama
			if ($total > 0)
			{
				$won = round(($row["games_won"] / $total) * 100);
				$draw = round(($row["games_draw"] / $total) * 100);
				$lost = round(($row["games_lost"] / $total) * 100);
				echo '<p><img src="http://chart.apis.google.com/chart?chs=500x200&amp;chd=t:'.$won.','.$draw.','.$lost.'&amp;cht=p3&amp;chco=aaaaff&amp;chf=bg,s,191E28&amp;chtt='.$row["username"].'\'s+games&amp;chts=FFFFFF,16&amp;chdl=Won|Draw|Lost" class="centered" alt="Player games\' chart" /></p>';
			}
			$time = time();
			//mostramos sus datos más relevantes
			echo '
				<table class="centered-table">
					<tr class="head-table">
						<td><b>Name</b></td>
						<td><b>Score</b></td>
						<td><b>Won</b></td>
						<td><b>Draw</b></td>
						<td><b>Lost</b></td>
						<td><b>Total</b></td>
						<td ><b>Last active</b></td>';
			if (isLoggedIn())
				echo '<td ><b>Challenge?</b></td>';
			echo '</tr>						<tr class="oddrow">		
							<td><a href= "stats.php?player='.$row["id_player"].'">'.$row["username"].'</a></td>
							<td>'.$row["score"].'</td>
							<td>'.$row["games_won"].' ('.round(($row["games_won"]/($total?$total:1))*10000)/100 .' %)</td>
							<td>'.$row["games_draw"].' ('.round(($row["games_draw"]/($total?$total:1))*10000)/100 .' %)</td>
							<td>'.$row["games_lost"].' ('.round(($row["games_lost"]/($total?$total:1))*10000)/100 .' %)</td>
							<td>'.$total.'</td>			
							<td>'.get_formated_duration($time - $row['time']).' ago</td>';
			if (isLoggedIn())
			{
				if ($_SESSION['id_player'] == $row["id_player"])
					echo '<td>---</td>';
				else
					echo '<td><a href="create_challenge.php?challenged='.$row["id_player"].'">Challenge!</a></td>';
			}			
			echo '</tr>
				</table>';						                
			//mostramos sus últimas partidas
			$query = "SELECT id_game, winner, p1.id_player AS p1id, p1.username AS p1us, p1.score AS p1sc, p2.id_player AS p2id, p2.username AS p2us, p2.score AS p2sc, games.time AS time FROM games, players AS p1, players AS p2 WHERE turn = 'finished' AND black = p1.id_player AND white = p2.id_player AND (black = '$player' OR white = '$player') ORDER BY games.time DESC LIMIT 10";
			$stmt = $dbh->query($query);
			if ($stmt->rowCount() > 0)
			{
				//ofrecemos ver sus oponentes
				echo '<p class="centered"><a href="opponents.php?player='.$player.'">Check '.$row["username"]."'s opponents!</a></p>";
				echo '<p class="centered">Recent games:</p>';
				//cabecera de la tabla
				echo   '<table class="centered-table">
		            	<tr class="head-table">										<td>Black (Score)</td>
										<td>White (Score)</td>
										<td>Winner</td>
										<td>Finished</td>
										<td>Watch?</td>
									</tr>';
				//cuerpo de la tabla
				$i = 1;
				$time = time();
				//para cada fila
				foreach ($stmt as $row)
				{
					//comienza fila
					echo '<tr class = "'.(($i % 2) ? "oddrow" : "evenrow").'">									<td><a href = "stats.php?player='.$row['p1id'].'">'.$row['p1us'].'</a> ('.$row['p1sc'].')</td>
							  	<td><a href = "stats.php?player='.$row['p2id'].'">'.$row['p2us'].'</a> ('.$row['p2sc'].')</td>';
										//winner
					if ($row['winner'] == 'black')
						echo '<td><a href = "stats.php?player='.$row['p1id'].'">'.$row['p1us'].'</a></td>';
					else if ($row['winner'] == 'white')
						echo '<td><a href = "stats.php?player='.$row['p2id'].'">'.$row['p2us'].'</a></td>';
					else
						echo '<td>Draw</td>';						
					echo '<td>'.get_formated_duration($time - $row['time']).' ago</td>
							  <td><a href = "game.php?id='.$row['id_game'].'&mode=spectator">Watch!</a></td>';   
					//acaba fila
					echo '</tr>';
					$i++;
				}
				//fin de la tabla
				echo '</table>';
			}
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
}
include_once('inc/footer.php');
?>