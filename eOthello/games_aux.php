<?php
//error_reporting(E_ALL);
session_start();
include_once('inc/db_connect.php');
include_once('inc/misc.php');
$pagination_number = 50;
//recoge la condición que las partidas deben cumplir para ser mostradas
if (isset($_REQUEST['cond']))
{
	$condition = $_REQUEST['cond'];
	$needLoggedIn = false;
	$constant = '';
	$web = '';
	$pagination = false;
	$num_pages = 1;
	$page = 1;
	if(isset($_REQUEST['page'])) $page = (int)$_REQUEST['page'];
	switch($condition)
	{
		case 'current':
			$needLoggedIn = false;
			$pagination = true;
			$web = 'games_aux.php?cond=current';
			$constant = 'CGAMES'; //current games
			$query = "SELECT id_game, rated, p1.id_player AS p1id, p1.username AS p1us, p1.score AS p1sc, p2.id_player AS p2id, p2.username AS p2us, p2.score AS p2sc, games.time AS time FROM games, players AS p1, players AS p2 WHERE turn <> 'pending' AND turn <> 'finished' AND black = p1.id_player AND white = p2.id_player ORDER BY games.time DESC";
			$noGamesMessage = '<p>There are no games taking place at the moment.</p>';
			$columns = array('Black (Score)', 'White (Score)', 'Rated', 'Last change', 'Watch?');
			break;
		case 'finished':
			$needLoggedIn = false;
			$pagination = true;
			$web = 'games_aux.php?cond=finished';
			$constant = 'FGAMES'; //finished games
			$query = "SELECT id_game, rated, winner, p1.id_player AS p1id, p1.username AS p1us, p1.score AS p1sc, p2.id_player AS p2id, p2.username AS p2us, p2.score AS p2sc, games.time AS time FROM games, players AS p1, players AS p2 WHERE turn = 'finished' AND black = p1.id_player AND white = p2.id_player ORDER BY games.time DESC";
			$noGamesMessage = '<p>There are no finished games.</p>';
			$columns = array('Black (Score)', 'White (Score)', 'Winner', 'Rated', 'Finished', 'Watch?');
			break;
		case 'pending':
			$needLoggedIn = true;
			$pagination = true;
			$web = 'games_aux.php?cond=pending';
			$constant = 'PGAMES'; //pending games
			$id = $_SESSION['id_player'];
			$query = "SELECT id_game, rated, username, id_player, score, random_opening, black, white, games.time AS time FROM games, players WHERE turn = 'pending' AND black = id_player AND black <> $id ORDER BY games.time DESC";
			$noGamesMessage = '<p>There are no games to join.</p>';
			$columns = array('Creator (Score)', 'Rated', 'Random', 'Your colour', 'Created', 'Join?');
			break;
		case 'mine':
			$needLoggedIn = true;
			$pagination = false;
			$web = 'games_aux.php?cond=mine';
			$constant = 'MGAMES'; //my games
			$id_player = $_SESSION['id_player'];
			$query = "SELECT id_game, rated, black, white, turn, games.time AS time FROM games WHERE ((white = $id_player AND clearw = false) OR (black = $id_player AND clearb = false)) ORDER BY games.time DESC";
			$noGamesMessage = '<p>You have no games in progress. What about <a href="create_game.php">creating</a> or <a href="games.php?cond=pending">joining</a> one?</p>';
			$columns = array('Opponent (Score)', 'Status', 'Rated', 'Last change', 'Play?', 'Clear?');
			$any_finished = false;
			break;
	}
	if ((isLoggedIn() || !$needLoggedIn) && $constant != '')
	{
		try 		{
			$stmt = $dbh->query($query);
			if ($stmt->rowCount() > 0)
			{
				if($pagination)
				{
					$num_pages = ceil(($stmt->rowCount()) / $pagination_number);
					if($page>$num_pages || $page <= 0)
					{
						echo "Invalid num page!!!";
					}
				}
				//cabecera de la tabla
				echo   '<table class="centered-table">
		            	<tr class="head-table">';
				for ($i = 0; $i < count($columns); $i++)
					echo '<td>'.$columns[$i].'</td>';						echo '</tr>';
				//cuerpo de la tabla
				$i = 1;
				$time = time();				
				//para cada fila
				foreach ($stmt as $row)
				{
					if((!$pagination)  || (   $pagination   &&   (  ( $i-1 >= (($page-1)*$pagination_number))  &&  ( $i-1 < ((($page-1)*$pagination_number)+$pagination_number)) )))
					{
						//echo $i;
						//comienza fila
						echo '<tr class = "'.(($i % 2) ? "oddrow" : "evenrow").'">';
						if ($condition == 'current')
						{
							echo '<td><a href = "stats.php?player='.$row['p1id'].'">'.$row['p1us'].'</a> ('.round($row['p1sc']).')</td>
									  <td><a href = "stats.php?player='.$row['p2id'].'">'.$row['p2us'].'</a> ('.round($row['p2sc']).')</td>
                    <td>'.(($row['rated'])?"Yes":"No").'</td>
									  <td>'.get_formated_duration($time - $row['time']).' ago</td>
									  <td><a href = "game.php?id='.$row['id_game'].'&mode=spectator">Watch!</a></td>';   
						}
						else if ($condition == 'finished')
						{
							echo '<td><a href = "stats.php?player='.$row['p1id'].'">'.$row['p1us'].'</a> ('.round($row['p1sc']).')</td>
									  <td><a href = "stats.php?player='.$row['p2id'].'">'.$row['p2us'].'</a> ('.round($row['p2sc']).')</td>';
							//winner
							if ($row['winner'] == 'black')
								echo '<td><a href = "stats.php?player='.$row['p1id'].'">'.$row['p1us'].'</a></td>';
							else if ($row['winner'] == 'white')
								echo '<td><a href = "stats.php?player='.$row['p2id'].'">'.$row['p2us'].'</a></td>';
							else
								echo '<td>Draw</td>';								
							echo '<td>'.(($row['rated'])?"Yes":"No").'</td>										<td>'.get_formated_duration($time - $row['time']).' ago</td>
									  <td><a href = "game.php?id='.$row['id_game'].'&mode=spectator">Watch!</a></td>';   
						}
						else if ($condition == 'pending')
						{							if ($row['black'] != null && $row['white'] != null)							{								$mycolor = "Random"; 							}							else if ($row['black'] != null)							{								$mycolor = "White";							}							else 							{								$mycolor = "Black";							}							
							echo '	<td><a href = "stats.php?player='.$row['id_player'].'">'.$row['username'].'</a> ('.round($row['score']).')</td>
				                    <td>'.(($row['rated'])?"Yes":"No").'</td>				                    <td>'.(($row['random_opening'])?"Yes":"No").'</td>
				                    <td>'.$mycolor.'</td>
									<td>'.get_formated_duration($time - $row['time']).' ago</td>
									<td><a href = "join.php?id_game='.$row['id_game'].'">Join!</a></td>';   	                    
						}
						else if ($condition == 'mine')
						{
							//obtenemos nuestro color
							$my_color = ($row['black'] == $id_player) ? 'black' : 'white';
							//obtenemos el id del oponente
							$opponent_id = ($row['black'] == $id_player) ? $row['white'] : $row['black'];
							//indica si la partida ha acabado
							$finished = false;
							//obtenemos el nombre del oponente
							if (!$opponent_id)
								$opponent = 'n/a';
							else
							{
								$query = "SELECT username, score FROM players WHERE id_player = '$opponent_id'";
								$stmt2 = $dbh->query($query);
								$opponent = $stmt2->fetch();
								$score = round($opponent['score']);
								$opponent = $opponent['username'];
							}
							//obtenemos el mensaje de estado
							switch($row['turn'])
							{
								case 'black':
									$status = ($row['black'] == $id_player) ? 'Your turn.' : 'Opponent\'s turn.';
									break;
								case 'white':
									$status = ($row['white'] == $id_player) ? 'Your turn.' : 'Opponent\'s turn.';
									break;
								case 'pending':
									$status = 'Waiting for an opponent.';
									break;
								case 'finished':
									$status = 'Game finished.';
									$finished = true;
									$any_finished = true;
									break;
							}							
							echo '<td>'.(($opponent_id) ? ('<a href ="stats.php?player='."$opponent_id".'">'."$opponent</a> ($score)"):("$opponent")).'</td>
									  <td>'.$status.'</td>  
                    <td>'.(($row['rated'])?"Yes":"No").'</td>
									  <td>'.get_formated_duration($time - $row['time']).' ago</td>
									  <td>'.(($row['turn'] == 'pending') ? ('---') : ('<a href ="game.php?id='.$row['id_game'].'&mode=player">Go!</a>')).'</td>										  
									  <td>'.($finished? ('<a href="./clear.php?id='.$row['id_game'].'">Clear</a>') : ('---')).'</td>';	
						}
						//acaba fila
						echo '</tr>';
					}
					$i++;
				}				
				//fin de la tabla
				echo '</table>';
				if($pagination && $num_pages > 1)				{
					echo '<table class ="centered-table-aux"><tr>';
					for($i = 1 ; $i <= $num_pages ; $i++)
					{
						if ($i == 21 || ($i > 21 && ($i - 1) % 20 == 0))
							echo '</tr><tr>';							
						echo '<td>';
						if($i != $page)
						{
							$fweb = $web.'&page='.$i;
							echo '<a onClick="Start(\''.$constant.'\',\''.$fweb.'\');" onMouseOver=\'style.cursor="pointer";\'>'.$i.'</a>';
						}
						else
						{
							echo $i;
						}
						echo '</td>';
					}
					echo'</tr></table>';
				}
			}
			else
				echo $noGamesMessage;
		}
		catch(PDOException $e ) 		{
			echo "error: ".$e->GetMessage();
		}
	}
}
?>