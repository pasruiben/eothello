<?php

	ignore_user_abort(false);
	session_cache_limiter("nocache");
	header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	
	
	$max_executions = 5;
	
	include_once('inc/db_connect.php'); 
	include_once('inc/db_games.php');
	include_once('inc/misc.php');
	
	global $seed;
	
	$cookie = $_COOKIE['ident'];
	$ident = XORDecrypt($cookie, $seed);
	$data_array = split(":",$ident);
	$user_id = $data_array[1];
	
	
	//flushHard(); 
	
	
	$response = array();
	
	$board_event = false;
	$chat_event = false;
	
	$player = false;
	
		
	if(isset($_REQUEST['time_board']) && isset($_REQUEST['time_chat']) && isset($_REQUEST['game_id']))
	{
		$timestamp_board = (int) $_REQUEST['time_board'];
		$timestamp_chat = (int) $_REQUEST['time_chat'];
		$game_id = (int) $_REQUEST['game_id'];
			
		
		if(exist_game($game_id))
		{
		
			if($cookie && player($game_id, $user_id))
			{
				$player = true;
			}
			
			$i = 0;
			
			while(!$board_event && !$chat_event)
			{
				if ($i >= $max_executions)
				{
					die();
				}
				
				if(board_event($game_id, $timestamp_board))
				{
					$board_event = true;
				}
				usleep(500000); 
				if($player && chat_event($game_id, $timestamp_chat, $user_id))
				{
					$chat_event = true;
				}
				usleep(500000); 
				
				$i++;
				
			}
						
			if($board_event)
			{
				$response['board'] = get_new_board($game_id,$timestamp_board);
			}
			
			if($chat_event)
			{
				$response['chat'] = get_new_chat($game_id,$timestamp_chat,$user_id);
			}
			
			if(($timestamp_board == 0 && $timestamp_chat == 0))
			{
				$response['chat'] = get_new_chat($game_id,0,0);
			}

			echo json_encode($response);
			flushHard();
		}
	
	}
	
	
	function board_event($game_id,$timestamp)
	{	
		global $dbh;
	
		$ret = false;
		
		$query = "SELECT id_game FROM games WHERE id_game = '$game_id' AND time > $timestamp";
		try {		
			$stmt = $dbh->query($query);
			if($stmt->rowCount() > 0)
			{
				$ret = true;
			}
		}
		catch(PDOException $e ) {
			die("error: ".$e->GetMessage());
		}
		
		return $ret;

	}
	
	
	function chat_event($game_id,$timestamp,$user_id)
	{	
		global $dbh;
		
		$ret = false;
		
		$query = "SELECT id_game FROM chat WHERE id_game = '$game_id' AND  id_user <> '$user_id' AND timestamp > $timestamp";
		
		try {		
			$stmt = $dbh->query($query);
			if($stmt->rowCount() > 0)
			{
				$ret = true;
			}
		}
		catch(PDOException $e ) {
			die("error: ".$e->GetMessage());
		}
		
		return $ret;

	}
	
	function get_new_board($game_id,$timestamp)
	{
		global $dbh;
		
		$response = array();
		
		$query = "SELECT board, moves, time FROM games WHERE id_game = '$game_id' AND time > $timestamp";
		
		try {		
			$stmt = $dbh->query($query);
			$row = $stmt->fetch();
			$response['board'] = $row['board'];	
			$response['moves'] = $row['moves'];	
			$response['timestamp'] = $row['time'];	
		}
		catch(PDOException $e ) {
			die("error: ".$e->GetMessage());
		}
		
		return $response;
	
	}
	
	function get_new_chat($game_id,$timestamp,$user_id)
	{
		global $dbh;
		
		$response = array();
		
		$query = "SELECT message, c.timestamp AS timestamp, username FROM chat AS c, players AS p WHERE p.id_player = c.id_user AND c.id_user <> '$user_id' AND c.id_game = '$game_id' AND c.timestamp > $timestamp ORDER BY c.timestamp ASC";

		
		try {		
			$stmt = $dbh->query($query);
			foreach ($stmt as $row) 
			{
				$res = array();
				$res['message'] = html_entity_decode($row['message']);	
				$res['timestamp'] = $row['timestamp'];	
				$res['username'] = $row['username'];
				$response []= $res;
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("error: ".$e->GetMessage());
		}
		
		return $response;
	}
	
	
	
	function flushHard()
	{
		// echo an extra 256 byte to the browswer - Fix for IE.
		for($i=1;$i<=256;++$i)
		{
			echo ' ';
		}
		flush();
		ob_flush();
	}

	
	
?>