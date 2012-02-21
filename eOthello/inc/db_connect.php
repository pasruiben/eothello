<?php
function connect() {	$dbh = null;
	$hostname = 'localhost';
	// $num = (int) rand(0,50);
	//echo $num;	
	$username = "eothello_user";
	$password = 'iothellopass';
	$dbname = 'eothello_trigeekz_iothelloweb';
	try 	{
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
		return $dbh;
	}
	catch (PDOException $e) 	{
		die('Could not connect!<br />Please contact the site\'s administrator.');
	}
	return false;
}

function disconnect($dbh) {
	$dbh = null;
}$dbh = connect();
?>