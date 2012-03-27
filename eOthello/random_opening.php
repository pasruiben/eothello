<?php include_once('inc/h_header.php');	?>
<script type="text/javascript" src="js/othello.js"></script>
<?php 
function rotateSequence($sequence, $startRotation, $endRotation)

if ($stmt->rowCount() == 1)
	echo 'This page let\'s you select one of the ' . $numOpenings . ' 8-move-opening positions that are considered to be +-2 by two of the strongest othello programs (Edax and NTest).<br />
            The idea is that players can use this to pick random openings that are assured to be more or less even and that (hopefully) they haven\'t played many times before.<br />
            In that way, players are "out of book" from the beginning of the game and no moves are played simply because they were memorized. It should be a lot of fun. :)<br />
            The list was compiled by Matthias Berg and Borja Moreno.<br /><br />';
	if (!isset($_REQUEST['opening'])) 
    	$opening = (int)$_REQUEST['opening'];
	$query = "SELECT sequence FROM openings WHERE id = $opening";
	echo '<form action="./random_opening.php" method="post">';
	if ($stmt->rowCount() == 1)
		echo 'Have fun with opening nr. ' . $opening . ', in the rotation you prefer: ';
	echo '<input size="6" name="choose" type="submit" value="Random one!" class="button" />
	echo '<form action="./random_opening.php" method="post">         
            <label id = "openinglabel" for="rated">You may was well choose an specific opening:</label>            
            <input size="5" maxlength="5" name="opening" />       
            <input size="6" name="choose" type="submit" value="This one!" class="button" />
          </form>';

include_once('inc/footer.php'); 