<?php

if (!isset($_SESSION))
{
	session_start();
}
include_once('inc/db_connect.php'); include_once('inc/misc.php');include_once ('inc/db_players.php');
function EndGame($board){	$white = 0;	$black = 0;
	for($y = 0; $y < 8; $y++)
	{
		for($x = 0; $x < 8; $x++)
		{           			if($board[$x][$y] == 'B')
			{
				$black++;
			}
            elseif($board[$x][$y] == 'W')
            {                   
            	$white++;
           	}
		}
	} 
	if($black == $white)
	{ 
		return 'draw';
	}	else 
	{
		return (($black>$white)?'black':'white');    
	}}
function CanPutPiece($x, $y, $turn, $board){                 	if ($board[$x][$y] != "E")
	{
		return (false);		
	}          
	
	return (NumFlips($x, $y, $turn, $board) > 0);}
function NumFlips($x, $y, $turn, $board) {	    
	$count = 0;	
	for ($deltay = -1; $deltay <= 1; $deltay++) 			{
		for($deltax = -1; $deltax <= 1; $deltax++) 			{
			for ($distance = 1;; $distance++) 		
			{							$posx = $x + ($distance * $deltax);				$posy = $y + ($distance * $deltay);
				//stop if we go off the board
				if ($posx < 0 || $posx >= 8 || $posy < 0 || $posy >= 8)				{
					break;				}
                    
				//stop when we reach an empty square				if ($board[$posx][$posy] == "E")				{
					break;
				}
				
				//only update the flip count when we reach another of the player's pieces				if (($board[$posx][$posy] == "W" && $turn == "white") || ($board[$posx][$posy] == "B" && $turn == "black"))				{ 				
					$count += $distance - 1;					break;
				}
			}	   		}
	}
        
	return ($count);}
function FlipPieces($x, $y, $turn, $board) {                        	if ($turn == "black")	{
		$p = "B";
	}	else
	{
		$p = "W";
	}
	//put a piece down at the desired location	$board[$x][$y] = $p;
	for ($deltay = -1; $deltay <= 1; $deltay++)	{           
		for ($deltax = -1; $deltax <= 1; $deltax++) 		
		{
			for ($distance = 1;; $distance++)
			{
				$posx = $x + ($distance * $deltax);
				$posy = $y + ($distance * $deltay);
				//stop if we go off the board
				if ($posx < 0 || $posx >= 8 || $posy < 0 || $posy >= 8)
				{
					break;				}
                    
				//stop when we reach an empty square				if($board[$posx][$posy] == "E") 
				{
					break;
				}				
				//only update the flip count when we reach another of the player's pieces
				if($board[$posx][$posy] == $p) 
				{
					//backtrack, flipping piecesç
					for($distance--; $distance > 0; $distance--)
					{
						$posx = $x + ($distance * $deltax);
						$posy = $y + ($distance * $deltay);
						$board[$posx][$posy] = $p;					}					
					break;
				}
			}
		}
	}		            
	return $board;}        
//receives a string indicating the new state of the board, and updates the board accordingly (E: Empty, B: Black, W: White)function SetBoardState($state){	        		//add white and black pieces	for($i = 0; $i < strlen($state); $i++) 			{		$ch = substr($state, $i, 1);                       		$board[$i % 8][round(floor($i / 8))] = $ch;            	}   			             
	return $board;}           
function GetBoardState($board){	$res = "";			
	for($y = 0; $y < 8; $y++) 	{
		for($x = 0; $x < 8; $x++)             		{
			$res .= $board[$x][$y];
		}
        return $res;
    }}
	
function AnyMoves($turn, $board) {	for($y = 0; $y < 8; $y++)
	{ 		for($x = 0; $x < 8; $x++) 
		{			if ($board[$x][$y] != 'E')
			{
				continue;
			}
			
			if (NumFlips($x, $y, $turn, $board) > 0) 
			{
				return(true);
			}
		}	}			
	return(false);}
if (isLoggedIn()){                         	if(isset($_REQUEST['x']) && isset($_REQUEST['y']) && isset($_REQUEST['id']) && isset($_REQUEST['num']))	{                            		$x = (int)$_REQUEST['x'];		$y = (int)$_REQUEST['y'];            
		$id_game = (int)$_REQUEST['id'];
		$num = (int)$_REQUEST['num'];		$id_user = $_SESSION['id_player'];
		$query = "SELECT board, turn, moves, rated FROM games WHERE id_game = '$id_game' AND ((black = '$id_user' && turn = 'black') || (white = '$id_user' && turn = 'white'))";            
		try 
		{			$stmt = $dbh->query($query);			                        
			if ($stmt->rowCount() == 1) // sabemos que existe el game que nos han pasao y que el turno es correcto y que el usuario que nos envia esta peticion es white o black			{	                 				$info = $stmt->fetch();				$board = SetBoardState($info['board']);				$turn = $info['turn'];				$moves = $info['moves'];				$rated = $info['rated'];
				//si el movimiento es correcto y num es correcto				$next_mov = strlen($moves) / 2 + 1;
				if ($next_mov == $num && CanPutPiece($x, $y, $turn, $board))				{                                                                  					$board = FlipPieces($x, $y, $turn, $board);
					if ($turn == 'black')
					{
						$other_turn = 'white';
					}
					else
					{
						$other_turn = 'black';
					}
					if (AnyMoves($other_turn, $board)) 
					{
						$turn = $other_turn;										
					}					//si el otro no tiene movimientos y nosotros tampoco, la partida ha acabado					else if (!AnyMoves($turn, $board))					{                    						$turn = 'finished';                    						$query = "SELECT p1.id_player AS whitep, p2.id_player AS blackp, p1.score AS whites, p2.score AS blacks FROM games, players AS p1, players AS p2  WHERE id_game = '$id_game' AND white = p1.id_player AND black = p2.id_player";            
						$stmt = $dbh->query($query);						$info = $stmt->fetch();
						$whitep = $info['whitep']; //white's id_player
						$blackp = $info['blackp']; //black's id_player						$f_game = EndGame($board);
						if($f_game == 'draw')						{							$query_black = "UPDATE players SET games_draw = games_draw + 1 WHERE id_player = '$blackp'";							$query_white = "UPDATE players SET games_draw = games_draw + 1 WHERE id_player = '$whitep'";                                        
							if ($rated)							{
								update_scores($blackp, $whitep, 0);
							}
						}						else						{							if($f_game == 'white') // gana white							{ 								$query_black = "UPDATE players SET games_lost = games_lost + 1 WHERE id_player = '$blackp'";								$query_white = "UPDATE players SET games_won = games_won + 1 WHERE id_player = '$whitep'"; 
								if ($rated)
								{
									update_scores($blackp, $whitep, -1);
								}							}							else // gana black							{  								$query_black = "UPDATE players SET games_won = games_won + 1 WHERE id_player = '$blackp'";								$query_white = "UPDATE players SET games_lost = games_lost + 1 WHERE id_player = '$whitep'"; 
								if ($rated)
								{
									update_scores($blackp, $whitep, 1);
								}							}   						}
						$dbh->exec($query_black);
						$dbh->exec($query_white);    
						//actualizamos el campo winner
						$query_winner = "UPDATE games SET winner = '$f_game' WHERE id_game = '$id_game'";
						$dbh->exec($query_winner);
					}
					
					//actualizamos la lista de movimientos
					$r = chr(ord('a') + $x);
					$c = $y + 1;
					$moves .= $r.$c;
					$board = GetBoardState($board);                
					$query = "UPDATE games SET board = '$board', turn = '$turn', moves = '$moves' WHERE id_game = '$id_game'";
					$dbh->exec($query);  
					//actualizamos el timestamp
					$time = time();
					$query = "UPDATE players SET time = '$time' WHERE id_player = '$id_user'"; 
					$dbh->exec($query);
					$query = "UPDATE games SET time = '$time' WHERE id_game = '$id_game'"; 
					$dbh->exec($query);
				}
			}
		}
		catch(PDOException $e ) 
		{
			echo "error: ".$e->GetMessage();
		}
	}
}
?>