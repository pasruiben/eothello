<?php 

include_once('inc/h_header.php');
include_once('inc/h_footer_body.php');
include_once('userbox.php');

if(isset($_REQUEST['player']))
{
	try 
	{
		$player = (int)$_REQUEST['player'];
		$query = "SELECT username FROM players WHERE id_player = $player";
		$stmt = $dbh->query($query);
		$row = $stmt->fetch();
		$username = $row["username"];
		echo '<h2><p class="centered"><a href="stats.php?player='.$player.'">'.$username."</a>'s opponents</p></h2>";
		//obtenemos todos los jugadores
		$query = "SELECT id_player, username, score FROM players WHERE activated = 1 ORDER BY score DESC";
		$stmt = $dbh->query($query);
		if(!isLoggedIn())
			echo '<div class="lhome"><p><a href= "./index.php">Index</a></p><br /></div>';
			
		if ($stmt->rowCount() > 0)
		{
			echo '
				<table class="centered-table">
					<tr class="head-table">
						<td><b>Opponent</b></td>
						<td><b>Score</b></td>
						<td><b>Won</b></td>
						<td><b>Draw</b></td>
						<td><b>Lost</b></td>
						<td><b>Total</b></td>
					</tr>';
			
			$i = 1;
			//para cada jugador del sitio
			foreach ($stmt as $row)
			{
				$opponent = $row["id_player"];
				$query_won = "SELECT COUNT(id_game) AS games_won FROM games WHERE (black = $player AND white = $opponent AND winner = 'black') OR (black = $opponent AND white = $player AND winner = 'white')";
				$stmt_won = $dbh->query($query_won);
				$row_won = $stmt_won->fetch();
				$games_won = $row_won["games_won"];
				$query_lost = "SELECT COUNT(id_game) AS games_lost FROM games WHERE (black = $player AND white = $opponent AND winner = 'white') OR (black = $opponent AND white = $player AND winner = 'black')";
				$stmt_lost = $dbh->query($query_lost);
				$row_lost = $stmt_lost->fetch();
				$games_lost = $row_lost["games_lost"];
				$query_draw = "SELECT COUNT(id_game) AS games_draw FROM games WHERE (black = $player AND white = $opponent AND winner = 'draw') OR (black = $opponent AND white = $player AND winner = 'draw')";
				$stmt_draw = $dbh->query($query_draw);
				$row_draw = $stmt_draw->fetch();
				$games_draw = $row_draw["games_draw"];
				$total = $games_won + $games_lost + $games_draw;
				if ($total == 0)
					continue;
				echo '
					<tr class="'.(($i % 2)?"oddrow":"evenrow").'">		
						<td ><a href= "stats.php?player='.$row["id_player"].'">'.$row["username"].'</a></td>
						<td>'.$row["score"].'</td>
						<td>'.$games_won.' ('.round(($games_won/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$games_draw.' ('.round(($games_draw/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$games_lost.' ('.round(($games_lost/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$total.'</td>
					</tr>';
				
				$i++;
			}
			
			echo '</table>';
		}
	}
	catch(PDOException $e ) 
	{
		die("error: ".$e->GetMessage());
	}
}

include_once('inc/footer.php'); 

?>