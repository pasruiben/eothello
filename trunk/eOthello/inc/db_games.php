<?php
//en realidad ya no manda solo la board, tambien la lista de movimientos... separados por un espacio
function get_board($id_game){
	global $dbh;
	$board = 'N';
	$query = "SELECT board, moves FROM games WHERE id_game = $id_game";
	try 	{
		$stmt = $dbh->query($query);
		$info = $stmt->fetch();
		$board = $info['board'] . ' '. $info['moves'];
	}
	catch(PDOException $e ) 	{
		echo "error: ".$e->GetMessage();
	}	
	return $board;
}
function clear_game($id_game,$id_player){
	global $dbh;
	$ret1 = false;
	$ret2 = false;
	$sql1 = "UPDATE games SET clearw = '1' WHERE id_game = '$id_game' AND white = '$id_player' AND turn = 'finished'";
	$sql2 = "UPDATE games SET clearb = '1' WHERE id_game = '$id_game' AND black = '$id_player' AND turn = 'finished'";
	try 	{
		if ($dbh->exec($sql1))
		{
			$ret1 = true;
		}
		if ($dbh->exec($sql2))
		{
			$ret2 = true;
		}
	}
	catch(PDOException $e ) 	{		die("error: ".$e->GetMessage());
	}
	return ($ret1 ^ $ret2);  // ;)
}
function num_active_games($id){
	global $dbh;	
	$ret = -1;
	$query = "SELECT id_game FROM games WHERE (white = '$id' OR black = '$id') AND turn <> 'finished'";

	try 	{
		$stmt = $dbh->query($query);
		$ret = $stmt->rowCount();
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
	return $ret;
}
function create_new_game($creator){
	global $dbh;
	$time = time();
	$ret = false;
	$sql = "INSERT INTO games (black, turn, time) VALUE ('$creator', 'pending', $time)";
	try 	{
		if ($dbh->exec($sql))
		{
			$ret = true;
		}
	}
	catch(PDOException $e) 	{
		die("error: ".$e->GetMessage());
	}
	return $ret;
}
function join_game($id_game, $n_player){	global $dbh;
	$time = time();
	$ret = false;
	$query = "UPDATE games SET white = '$n_player', turn = 'black', time = '$time' WHERE id_game = '$id_game'";
	try 	{
		if ($dbh->exec($query))
		{
			$ret = true;
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}	
	return $ret;
}
function exist_game($id_game)
{
	global $dbh;	
	$ret = false;
	$query = "SELECT id_game FROM games WHERE id_game = '$id_game'";

	try 	{
		$stmt = $dbh->query($query);		
		if ($stmt->rowCount() == 1)
		{
			$ret = true;
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}

	return $ret;
}
function player($id_game, $id_player)
{
	global $dbh;	
	$ret = false;
	$query = "SELECT id_game FROM games WHERE id_game = '$id_game' AND (black = '$id_player' || white = '$id_player')";
	try 	{
		$stmt = $dbh->query($query);
		if ($stmt->rowCount() == 1)
		{
			$ret = true;
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
	return $ret;
}
?>