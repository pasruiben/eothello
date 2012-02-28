<?php include_once('inc/h_header.php');include_once('inc/h_footer_body.php');include_once('userbox.php');
if(isset($_REQUEST['count']) && isset($_REQUEST['start']))
{
	try 	{
		$start = (int)$_REQUEST['start'];
		$count = (int)$_REQUEST['count'];
		$query = "SELECT id_player, username, games_draw, games_lost, games_won, score, time FROM players WHERE activated = 1 ORDER BY score DESC, games_won DESC, games_draw DESC, games_lost DESC LIMIT $start, $count";
		$stmt = $dbh->query($query);
		if(!isLoggedIn())				echo '<div class="lhome"><p><a href= "./index.php">Index</a></p><br /></div>';
		if ($stmt->rowCount() > 0)
		{
			echo '
				<table class="centered-table">
					<tr class="head-table">
						<td></td>
						<td><b>Name</b></td>
						<td><b>Score</b></td>
						<td><b>Won</b></td>
						<td><b>Draw</b></td>
						<td><b>Lost</b></td>
						<td><b>Total</b></td>
						<td ><b>Last active</b></td>';
			if (isLoggedIn())
				echo '<td ><b>Challenge?</b></td>';
			echo '</tr>';
			$i = 1;			
			foreach ($stmt as $row)
			{
				$total = $row["games_won"] + $row["games_lost"] + $row["games_draw"];

				if ($total == 0)
					continue;

				$time = time();
					
				echo '
					<tr class="'.(($i % 2)?"oddrow":"evenrow").'">		
						<td>'.($start+$i).'.</td>
						<td ><a href= "stats.php?player='.$row["id_player"].'">'.$row["username"].'</a></td>
						<td>'.round($row["score"]).'</td>
						<td>'.$row["games_won"].' ('.round(($row["games_won"]/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$row["games_draw"].' ('.round(($row["games_draw"]/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$row["games_lost"].' ('.round(($row["games_lost"]/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$total.'</td>					
						<td>'.get_formated_duration($time - $row['time']).' ago</td>';
				//sólo si estamos loggeados podemos retar a otros jugadores (y no nos podemos retar a nosotros mismos)
				if (isLoggedIn())
				{
					if ($_SESSION['id_player'] == $row["id_player"])
						echo '<td>---</td>';
					else
						echo '<td><a href="create_challenge.php?challenged='.$row["id_player"].'">Challenge!</a></td>';
				}
				echo '</tr>';
				$i++;
			}
		}
		$query = "SELECT id_player, username, games_draw, games_lost, games_won, score, time FROM players WHERE games_won = 0 AND games_draw = 0 AND games_lost = 0";
		$stmt = $dbh->query($query);		
		if ($stmt->rowCount() > 0)
		{
			foreach ($stmt as $row)
			{
				$total = $row["games_won"] + $row["games_lost"] + $row["games_draw"];
				$time = time();
				echo '
					<tr class="'.(($i % 2)?"oddrow":"evenrow").'">		
						<td>'.($start+$i).'.</td>
						<td ><a href= "stats.php?player='.$row["id_player"].'">'.$row["username"].'</a></td>
						<td>---</td>
						<td>'.$row["games_won"].' ('.round(($row["games_won"]/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$row["games_draw"].' ('.round(($row["games_draw"]/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$row["games_lost"].' ('.round(($row["games_lost"]/($total?$total:1))*10000)/100 .' %)</td>
						<td>'.$total.'</td>					
						<td>'.get_formated_duration($time - $row['time']).' ago</td>';
				//sólo si estamos loggeados podemos retar a otros jugadores (y no nos podemos retar a nosotros mismos)
				if (isLoggedIn())
				{
					if ($_SESSION['id_player'] == $row["id_player"])
						echo '<td>---</td>';
					else
						echo '<td><a href="create_challenge.php?challenged='.$row["id_player"].'">Challenge!</a></td>';
				}
				echo '</tr>';
				$i++;
			}
		}				echo '</table>';
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
}
include_once('inc/footer.php'); ?>