<hr class="line" />

$time = time();
//actualiza el timestamp y logged_in
if(isLoggedIn())
{
	$id_p = $_SESSION['id_player'];
	$query = "UPDATE players SET time = $time, logged_in = 1 WHERE id_player = '$id_p'";
	$dbh->exec($query);
}
//disconnect($dbh);
?>