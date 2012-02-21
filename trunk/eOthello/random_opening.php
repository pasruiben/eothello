<?php include_once('inc/h_header.php');	?>
<script type="text/javascript" src="js/othello.js"></script>
<?php include_once('inc/h_footer_body.php');include_once('userbox.php');
function rotateSequence($sequence, $startRotation, $endRotation){                 	$newSequence = "";	$tempSequence = $sequence;	for($r = $startRotation ; $r < $endRotation; $r++)	{		for($i = 0; $i < strlen($tempSequence); $i += 2)		{			$move = substr($tempSequence, $i, 2);			$column = ord(substr($move, 0 ,1)) - ord('a') + 1;			$row = substr($move, 1 ,1);			if ($r % 2 == 0)			{				$newColumn = chr (ord('a') + 8 - $row);				 $newRow = 8 - $column + 1;			}			else			{				$newColumn = chr($row - 1 + ord('a'));				$newRow = $column;			}			$newMove = $newColumn . $newRow;			$newSequence .= $newMove;		}				$tempSequence = $newSequence;		$newSequence = "";	}	return $tempSequence;}
$query = "SELECT count(*) as num FROM openings";$stmt = $dbh->query($query);
if ($stmt->rowCount() == 1){			$row = $stmt->fetch();	$numOpenings = $row['num'];
	echo 'This page let\'s you select one of the ' . $numOpenings . ' 8-move-opening positions that are considered to be +-2 by two of the strongest othello programs (Edax and NTest).<br />
            The idea is that players can use this to pick random openings that are assured to be more or less even and that (hopefully) they haven\'t played many times before.<br />
            In that way, players are "out of book" from the beginning of the game and no moves are played simply because they were memorized. It should be a lot of fun. :)<br />
            The list was compiled by Matthias Berg and Borja Moreno.<br /><br />';
	if (!isset($_REQUEST['opening'])) 	{		$opening = rand(1,$numOpenings);		echo $opening;	}	else	{ 
    	$opening = (int)$_REQUEST['opening'];		echo $opening;	}
	$query = "SELECT sequence FROM openings WHERE id = $opening";	$stmt = $dbh->query($query);
	echo '<form action="./random_opening.php" method="post">';
	if ($stmt->rowCount() == 1)	{   				$row = $stmt->fetch();		$sequence = $row['sequence'];
		echo 'Have fun with opening nr. ' . $opening . ', in the rotation you prefer: ';		echo '<ul>';		echo '<li>' . strtoupper($sequence) . '</li>';		echo '<li>' . strtoupper(rotateSequence($sequence, 0, 1)) . '</li>';		echo '<li>' . strtoupper(rotateSequence($sequence, 0, 2)) . '</li>';		echo '<li>' . strtoupper(rotateSequence($sequence, 0, 3)) . '</li>';		echo '</ul>';	}	else	{		echo 'You must choose an opening from 1 to ' . $numOpenings . ' (inclusive).';	}
	echo '<input size="6" name="choose" type="submit" value="Random one!" class="button" />		</form>';	//echo '<br /><br />';
	echo '<form action="./random_opening.php" method="post">         
            <label id = "openinglabel" for="rated">You may was well choose an specific opening:</label>            
            <input size="5" maxlength="5" name="opening" />       
            <input size="6" name="choose" type="submit" value="This one!" class="button" />
          </form>';}

include_once('inc/footer.php'); ?>