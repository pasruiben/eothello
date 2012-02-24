<?php 
include_once('../inc/db_connect.php'); include_once('../inc/db_players.php'); 
/* Cuando pasan 15 minutos de inactividad evitamos k el usuario salga en online users */
$time = time();
$query = "SELECT id_player FROM players WHERE (($time - time) > 900) AND logged_in = 1";
$stmt = $dbh->query($query);
foreach ($stmt as $row)
{
	$id_player = $row['id_player'];
	SetLoggedIn($id_player, 0);
}
?>