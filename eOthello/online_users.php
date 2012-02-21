<?php


    include_once('inc/db_connect.php'); 
    include_once('inc/misc.php');
	
	
	//obtiene usuarios activos
	$query = "SELECT id_player, username FROM players WHERE logged_in = 1 ORDER BY time DESC";
	$stmt = $dbh->query($query);

	if (($stmt->rowCount()) > 0)
	{			
		echo '<p>Users online: ';
			
		$i = 0;
		foreach ($stmt as $row)
		{
			if ($i > 0)
				echo ', ';
			echo '<a href= "stats.php?player='.$row["id_player"].'">'.$row["username"].'</a>';
			$i++;
		}
		
		echo '.</p>';
	}
	
?>