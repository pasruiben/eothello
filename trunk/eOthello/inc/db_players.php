<?php
function update_scores($black, $white, $result)
{
	global $dbh;
	//obtenemos la puntuación de cada jugador
	$query = "SELECT score FROM players WHERE id_player = $black";
	$stmt = $dbh->query($query);
	$row = $stmt->fetch();
	$black_score = $row['score'];
	$query = "SELECT score FROM players WHERE id_player = $white";
	$stmt = $dbh->query($query);
	$row = $stmt->fetch();
	$white_score = $row['score'];
	//echo "&nbsp;&nbsp;black's old score was $black_score<br/>";
	//echo "&nbsp;&nbsp;white's old score was $white_score<br/>";
	//y calculamos la nueva
	/*New = Old + (oppts rating - Old) * 16 / 25 + Result * 16
	where Result = +1 for win, 0 for draw, and -1 for loss and where
	(oppts rating - Old) is bounded by +400 and -400.*/
	//nueva para el negro
	$diff = $white_score - $black_score;
	if ($diff > 400) $diff = 400;
	else if ($diff < -400)	$diff = -400;
	$black_score += $diff/25 + $result * 16;	
	//echo "&nbsp;&nbsp;black's new score is $black_score<br/>";
	$query = "UPDATE players SET score = $black_score WHERE id_player = $black";
	$stmt = $dbh->exec($query);
	//nueva para blanco
	$diff = $black_score - $white_score;
	if ($diff > 400) $diff = 400;
	else if ($diff < -400) $diff = -400;
	$white_score += $diff/25 + $result * (-1) * 16;
	//echo "&nbsp;&nbsp;white's new score is $white_score<br/>";
	$query = "UPDATE players SET score = $white_score WHERE id_player = $white";
	$stmt = $dbh->exec($query);
}
function registered_users(){	global $dbh;	$num = -1;
	//obtenemos en número de usuarios registrados	$query = "SELECT id_player FROM players WHERE activated = 1";		try 	{		$stmt = $dbh->query($query);
		$num = $stmt->rowCount();	}
	catch(PDOException $e) 	{		die("error: ".$e->GetMessage());
	}	
	return $num;
}
function SetLoggedIn($uid, $value)
{
	global $dbh;
	$ret = false;
	$sql = "UPDATE players SET logged_in = $value WHERE id_player = '$uid'";
	try 	{
		if ($dbh->exec($sql))
		$ret = true;
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}	
	return $ret;
}
function id_exist($id){	global $dbh;
	$ret = false;
	$query = "SELECT id_player FROM players WHERE id_player = '$id'";
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
function id_active($id){
	global $dbh;
	$ret = false;
	$query = "SELECT id_player FROM players WHERE id_player = '$id' AND activated = 1";
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
function valid_actcode($uid, $code){
	global $dbh;	
	$ret = false;
	$sql = "SELECT id_player FROM players WHERE id_player = '$uid' AND actcode = '$code'";

	try 	{
		$stmt = $dbh->query($sql);
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
function activateUser($uid)
{
	global $dbh;
	$ret = false;
	$sql = "UPDATE players SET activated = '1' WHERE id_player = '$uid'";
	try 	{
		if ($dbh->exec($sql))	$ret = true;
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}	
	return $ret;
}
function register($username,$password,$email,$code)
{
	global $seed;
	global $dbh;
	global $mail;

	$ret = 0;
	$ncpassword = sha1($password.$seed);
	if($mail)	{
		$query = "INSERT INTO players (username, password, email, actcode) VALUE ('$username', '$ncpassword', '$email', '$code')";
	}
	else	{
		$query = "INSERT INTO players (username, password, email, actcode, activated) VALUE ('$username', '$ncpassword', '$email', '$code','1')";
	}
	try 	{
		if ($dbh->exec($query))
		{
			$ret = $dbh->lastInsertId();
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}	
	return $ret;
}
function user_exist($username){
	global $dbh;	
	$ret = false;
	$query = "SELECT id_player FROM players WHERE username = '$username'";
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
function get_username($id)
{
	global $dbh;
	$ret = '';
	$query = "SELECT username FROM players WHERE id_player = '$id'";
	try 	{
		$stmt = $dbh->query($query);
		if ($stmt->rowCount() > 0)
		{
			$row = $stmt->fetch();
			$ret = $row['username'];
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
	return $ret;
}
function email_exist($email){
	global $dbh;
	$ret = false;
	$query = "SELECT id_player FROM players WHERE email = '$email'";
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
function active_user($username)
{
	global $dbh;
	$ret = false;
	$query = "SELECT id_player FROM players WHERE username = '$username' AND activated = 1";
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
function get_id_player($username){	global $dbh;
	$id = "";
	$query = "SELECT id_player FROM players WHERE username = '$username'";
	try 	{
		$stmt = $dbh->query($query);
		if ($stmt->rowCount() == 1)
		{
			$row = $stmt->fetch();
			$id = $row['id_player'];
		}
	}
	catch(PDOException $e ) 	{
		die("error: ".$e->GetMessage());
	}
	return $id;
}
function valid_pass($user,$password){ //true si la contraseña k me pasan corresponde con la del usuario
	global $seed;
	global $dbh;
	$ret = false;
	$pass = sha1($password.$seed);
	$query = "SELECT id_player FROM players WHERE username = '$user' AND password = '$pass'";
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
function change_password($username,$newpass)
{
	global $seed;
	global $dbh;	
	$ret = false;
	$npass = sha1($newpass.$seed);
	$query = "UPDATE players SET password = '$npass' WHERE username = '$username'";
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
?>