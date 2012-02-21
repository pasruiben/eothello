		<script	type="text/javascript" src="js/validator.js"></script>		<script	type="text/javascript" src="js/ajaxmod.js"></script>	</head>	<body>	<?php		
	//estadísticas de la web
	try 	{
		$registered_users = registered_users();	
		//obtenemos el número de partidas en juego
		$query = "SELECT id_game FROM games WHERE turn <> 'pending' AND turn <> 'finished'";		
		$stmt = $dbh->query($query);
		$games_in_progress = $stmt->rowCount();		
		//obtenemos el número de partidas completadas
		$query = "SELECT id_game FROM games WHERE turn = 'finished'";
		$stmt = $dbh->query($query);
		$completed_games = $stmt->rowCount();	
		echo '
				<div id="informationlist">
					<ul>
						<li><a href="./players.php?start=0&amp;count=1000">Registered users: '.$registered_users.'</a></li>		
						<li><a href="games.php?cond=current">Games in progress: '.$games_in_progress.'</a></li>
						<li><a href="games.php?cond=finished">Completed games: '.$completed_games.'</a></li>
					</ul>
				</div>
			';
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
	?>