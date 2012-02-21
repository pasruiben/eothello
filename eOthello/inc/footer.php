<hr class="line" /><div class="headfootstyle">	<table class="centered-table-aux">		<tr>			<td><a href="./players.php?start=0&amp;count=1000">Top players</a></td>			<td>-</td>			<td><a href="http://en.wikipedia.org/wiki/Reversi#Rules">Rules</a></td>			<td>-</td>			<td><a href="references.php">References</a></td>			<td>-</td>			<td><a href="contact.php">Contact</a></td>			<td>-</td>			<td><script type="text/javascript" src="js/bookmark.js"></script></td>		</tr>	</table></div><div id="OUSERS">	<p><br /></p></div><script type="text/javascript">Start('OUSERS','online_users.php');</script><?php

$time = time();
//actualiza el timestamp y logged_in
if(isLoggedIn())
{
	$id_p = $_SESSION['id_player'];
	$query = "UPDATE players SET time = $time, logged_in = 1 WHERE id_player = '$id_p'";
	$dbh->exec($query);
}
//disconnect($dbh);
?>		<table>			<tr>				<td><a href="http://validator.w3.org/check?uri=referer"> <img					class="validationimg" src="images/valid-xhtml10-blue.png"					alt="Valid XHTML 1.0 Strict" /> </a></td>				<td><a href="http://jigsaw.w3.org/css-validator/check/referer"> <img					class="validationimg" src="images/vcss-blue.gif" alt="Valid CSS!" /></a></td>			</tr>		</table>				<p class="centeredsmall">Othello (R) is a registered trademark of Anjar Co.</p>	</body></html><?php  ob_end_flush();     ?>