var noerror = true;
var retry_interval = 1000; //cada segundo intentamos conectar con el servidor de eventos
var time_board = 0;
var time_chat = 0;
var f_time = true;
var chat_load = false;
var username;
var move_index;

//tamanyo de las imagenes
var	cellsize = 45;

//tamanyo del tablero (8x8)
var	width = 8;
var	height = 8;

//constantes
var	NONE = 0;
var	BLACK = 1;
var	WHITE = 2;
var	BLACKTRANS = 3; //para la imagen semi-transparente negra
var	WHITETRANS = 4; //para la imagen semi-transparente blanca
var A = 5;
var B = 6;
var C = 7;
var D = 8;
var E = 9;
var F = 10;
var G = 11;
var H = 12;
var A2 = 13;
var B2 = 14;
var C2 = 15;
var D2 = 16;
var E2 = 17;
var F2 = 18;
var G2 = 19;
var H2 = 20;
var ONE = 21;
var TWO = 22;
var THR = 23;
var FOU = 24;
var FIV = 25;
var SIX = 26;
var SEV = 27;
var EIG = 28;
var ONE2 = 29;
var TWO2 = 30;
var THR2 = 31;
var FOU2 = 32;
var FIV2 = 33;
var SIX2 = 34;
var SEV2 = 35;
var EIG2 = 36;
var CORNW = 37;
var CORNE = 38;
var CORSE = 39;
var CORSW = 40;

//array de imagenes (indexado por las constantes de arriba)
var	picture = [];

//array que contiene el tablero en si
var	board = new Array(width);	

//lista de movimientos
var moves;

//booleano que indica si es tu turno o no
var	yourTurn;

//indica el rol (papel) que juegas en la partida: puede ser WHITE para jugador blanco, BLACK para jugador negro o NONE para espectador (ya que no influye en la partida)
var role;

//identificador de partida
var id_game;
var status;
var showofflinegamemenu = false;;

//crea el objeto XMLHttpRequest segun el navegador
function ajaxObject()
{
    try
    {
        // Firefox, Opera 8.0+, Safari
        return new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            return new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e2)
        {
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
}

var globalXml = ajaxObject();

function is_ie7()
{
	return (document.documentElement && typeof document.documentElement.style.maxHeight!="undefined");
}

if (is_ie7())
{ 
	/*window.onerror = function() {};*/ 
	window.onbeforeunload = function() { globalXml.abort();};    
}
   
//obtiene el numero de la imagen del documento que tiene por nombre 'name'
function lookup(name) 
{
	var res = -1;
    var i = 0;

    for (i = 0; i < document.images.length; i++)
	{
        if (document.images[i].name == name)
		{				
			res = i;
		}
	}
		
    return res;
}

//definicion "constructor" del objeto piece
function piece(imagename) 
{	
    this.imagenum = lookup(imagename);
    this.player = NONE;    
}

//asocia una imagen con un numero (de las constantes definidas)
function LoadPieceImage(picnum, pictureURL) 
{
    picture[picnum] = new Image();
    picture[picnum].src = './images/' + pictureURL;
}

//coloca la imagen dada por src en la posicion del tablero x, y
function SetPieceImage(x, y, src) 
{	    
	//miramos si vamos a hacer un cambio para prevenir redibujado innecesario
    if (board[x][y].imagenum != -1)
    {
        if (document.images[board[x][y].imagenum].src != src) 
        {
            document.images[board[x][y].imagenum].src = src;
        }
    }
}

//carga las imagenes de las piezas
function LoadImages()
{    
    LoadPieceImage(NONE, "blank2.png");
	LoadPieceImage(WHITE, "white2.png");
	LoadPieceImage(BLACK, "black2.png");
	LoadPieceImage(WHITETRANS, "white-trans2.png");
	LoadPieceImage(BLACKTRANS, "black-trans2.png");
    LoadPieceImage(A, "a.png");
    LoadPieceImage(B, "b.png");
    LoadPieceImage(C, "c.png");
    LoadPieceImage(D, "d.png");
    LoadPieceImage(E, "e.png");
    LoadPieceImage(F, "f.png");
    LoadPieceImage(G, "g.png");
    LoadPieceImage(H, "h.png");   
    LoadPieceImage(A2, "a2.png");
    LoadPieceImage(B2, "b2.png");
    LoadPieceImage(C2, "c2.png");
    LoadPieceImage(D2, "d2.png");
    LoadPieceImage(E2, "e2.png");
    LoadPieceImage(F2, "f2b.png");
    LoadPieceImage(G2, "g2.png");
    LoadPieceImage(H2, "h2.png");    
    LoadPieceImage(ONE, "1.png");
    LoadPieceImage(TWO, "2.png");
    LoadPieceImage(THR, "3.png");
    LoadPieceImage(FOU, "4.png");
    LoadPieceImage(FIV, "5.png");
    LoadPieceImage(SIX, "6.png");
    LoadPieceImage(SEV, "7.png");
    LoadPieceImage(EIG, "8.png");    
    LoadPieceImage(ONE2, "12.png");
    LoadPieceImage(TWO2, "22.png");
    LoadPieceImage(THR2, "32.png");
    LoadPieceImage(FOU2, "42.png");
    LoadPieceImage(FIV2, "52.png");
    LoadPieceImage(SIX2, "62.png");
    LoadPieceImage(SEV2, "72.png");
    LoadPieceImage(EIG2, "82.png"); 
    LoadPieceImage(CORNW, "cornernw.png");   
    LoadPieceImage(CORNE, "cornerne.png");
    LoadPieceImage(CORSE, "cornerse.png");
    LoadPieceImage(CORSW, "cornersw.png");
}

//inicializa el estado del tablero
function InitializeBoard() 
{	    
    var x;
    
    //construye el array board, es un array de arrays
    for (x = 0; x < width; x++)
    {
		board[x] = new Array(height);
    }
	
    var y;
    document.write('<table id = "gamestyle">');
    
    //primera fila
    document.write('<tr><td><img src="' + picture[CORNW].src + '" border="0" height="' + cellsize/2 + '" width="' + cellsize/2 + '" /></td>');       
		
	//para cada celda
	for (x = 0; x < width; x++) 
	{
		//creamos la imagen que representa a la pieza que hay en esta celda, tiene una serie de eventos javascript asociados en onClick, onMouseOver y onMouseOut            
		document.write('<td><img src="' + picture[A+x].src + '" border="0" height="' + cellsize/2 + '" width="' + cellsize + '" /></td>');                  
	}		
	
    document.write('<td><img src="' + picture[CORNE].src + '" border="0" height="' + cellsize/2 + '" width="' + cellsize/2 + '" /></td></tr>');                
    
	//para cada fila
    for (y = 0; y < height; y++) 
	{	
        //empezamos en una linea nueva
        document.write('<tr><td><img src="' + picture[ONE + y].src + '" border="0" height="' + cellsize + '" width="' + cellsize/2 + '" /></td>');       
		
		//para cada celda
		for (x = 0; x < width; x++) 
		{
			//creamos la imagen que representa a la pieza que hay en esta celda, tiene una serie de eventos javascript asociados en onClick, onMouseOver y onMouseOut            
			document.write('<td><img name="c[' + x + ',' + y + ']" src="' + picture[NONE].src + '" border="0" height="' + cellsize + '" width="' + cellsize + '" onMouseOver="CheckPutPiece(' + x + ', ' + y + ')" onMouseOut="RestorePiece(' + x + ', ' + y + ')" onClick="PutPiece(' + x + ', ' + y + ')"></td>');            
			board[x][y] = new piece('c[' + x + ',' + y + ']');   
            board[x][y].player = NONE;            
		}		
		
        document.write('<td><img src="' + picture[ONE2 + y].src + '" border="0" height="' + cellsize + '" width="' + cellsize/2 + '" /></td></tr>');        
    }	        
    
    //ultima fila
    document.write('<tr><td><img src="' + picture[CORSW].src + '" border="0" height="' + cellsize/2 + '" width="' + cellsize/2 + '" /></td>');       
		
	//para cada celda
	for (x = 0; x < width; x++) 
	{
		//creamos la imagen que representa a la pieza que hay en esta celda, tiene una serie de eventos javascript asociados en onClick, onMouseOver y onMouseOut            
		document.write('<td><img src="' + picture[A2+x].src + '" border="0" height="' + cellsize/2 + '" width="' + cellsize + '" /></td>');                  
	}		
	
    document.write('<td><img src="' + picture[CORSE].src + '" border="0" height="' + cellsize/2 + '" width="' + cellsize/2 + '" /></td></tr>');
    document.write('</table>');
}

//cuenta el numero de piezas que capturariamos si pusiesemos una pieza en la posicion board[x,y] (cuenta en las 8 posibles direcciones de captura, horizontal, vertical y diagonal)
function NumFlips(x, y, player) 
{	    
    var deltax, deltay, distance;
    var posx, posy;
    var count = 0;	            
    
    for (deltay = -1; deltay <= 1; deltay++) 		
    {
	    for(deltax = -1; deltax <= 1; deltax++) 	
	    {
		    for (distance = 1;; distance++) 		
			{			
				posx = x + (distance * deltax);
				posy = y + (distance * deltay);
				
				//paramos si nos salimos del tablero
				if (posx < 0 || posx >= width || posy < 0 || posy >= height)
                {
					break;
                }
                
				//paramos si encontramos una casilla vacia
				if (board[posx][posy].player == NONE)
                {
					break;
                }
                
				//si encontramos una pieza nuestra, actualizamos la cuenta de piezas capturadas
				if (board[posx][posy].player == player)
				{ 				
					count += distance - 1;                    
					break;
				}
			}	   
		}
	}
			      
    return count;
}

//pone una pieza de player (puede valer WHITE o BLACK)  en board[x][y], sin chequear que sea posible (ya se habra hecho antes)
function RawPutPiece(x, y, player) 
{	
	board[x][y].player = player;  
    SetPieceImage(x, y, picture[player].src);
}

//colocamos una pieza en board[x,y] y realizamos las capturas correspondientes
function FlipPieces(x, y) 
{
    var deltax, deltay, distance;
    var posx, posy;    

    //primero ponemos la pieza en board[x,y]
    RawPutPiece(x, y, role);

    for (deltay = -1; deltay <= 1; deltay++)
	{
		for (deltax = -1; deltax <= 1; deltax++) 	
		{		
			for (distance = 1;; distance++)
			{
				posx = x + (distance * deltax);
				posy = y + (distance * deltay);
				
				//paramos si nos salimos del tablero
				if (posx < 0 || posx >= width || posy < 0 || posy >= height)
                {
					break;
                }
                
				//paramos si encontramos una casilla vacia
				if(board[posx][posy].player == NONE)
                {
					break;
                }
                
				//si encontramos una pieza nuestra, capturamos todo lo que haya entre esta y la que pusimos orginalmente
				if(board[posx][posy].player == role) 
				{					
					for(distance -= 1; distance > 0; distance--) 
					{
						posx = x + (distance * deltax);
						posy = y + (distance * deltay);
						RawPutPiece(posx, posy, role);
					}
					break;
				}
			}		    
		}
	}
}

//indica si el jugador (WHITE o BLACK) puede hacer algun movimiento
function AnyMoves(player) 
{
    var x, y;

    for (y = 0; y < height; y++)
    { 
		for (x = 0; x < width; x++) 
		{
			if (board[x][y].player != NONE)
			{ 
				continue;
            }
			if (NumFlips(x, y, player) > 0)
			{
				return true;
            }
		} 
    }
    
    return false;
}

//determina si podemos poner una pieza en board[x,y]
function CanPutPiece(x, y) 
{	    
    //si no es tu turno no puedes poner
    if (!yourTurn)
    {
		return false;
    }
    
    //si la casilla no esta vacia no puedes poner
    if (board[x][y].player != NONE)
    {
		return false;		
    }
    
    //puedes poner si es tu turno, la casilla esta vacia y al poner capturarias al menos una pieza rival
    return (NumFlips(x, y, role) > 0);
}

//si es posible poner una pieza en board[x,y] dibuja la version semi-transparente de la pieza para indicar al jugador que puede poner ahi
function CheckPutPiece(x, y) 
{
    var over;      
   
    if (CanPutPiece(x, y)) 
	{		
	    //la pieza semitransparente sera una u otra de acuerdo a nuestro rol (recordemos que si podemos poner pieza nuestro rol no va a ser NONE)
	    if (role == WHITE) 
        {        
			over = WHITETRANS;
        }
	    else	
        {
			over = BLACKTRANS;		
        }
	    
		SetPieceImage(x, y, picture[over].src);
	}
}

//dibuja la pieza board[x,y] de acuerdo a la informacion que hay en board (sirve para volver al estado normal despues de haber dibujado una version semi-transparente)
function RestorePiece(x, y) 
{   
    SetPieceImage(x, y, picture[board[x][y].player].src);
}

//devuelve el jugador que no somos nosotros
function OtherPlayer()
{
    if (role == BLACK)
    {
        return WHITE;
    }
    else if (role == WHITE)
    {
        return BLACK;
    }
}


//recibe una cadena con el estado del tablero, y lo actualiza (E: Empty, B: Black, W: White)
function SetBoardState(state)
{
	var i, ch;	    	    
    
	for(i = 0; i < state.length; i++) 		
	{            
		ch = state.charAt(i);
		if (ch == "B")       
        {
			RawPutPiece(i % 8, Math.floor(i / 8), BLACK);                    
        }
		else if (ch == "W")
        {
			RawPutPiece(i % 8, Math.floor(i / 8), WHITE);	        
        }
		else
		{
			RawPutPiece(i % 8, Math.floor(i / 8), NONE);
		}
	}
		
}

//devuelve una cadena con el estado del tablero
function GetBoardState()
{
    var state = "";		
    var x, y;
    
    for (y = 0; y < 8; y++) 
    {
        for(x = 0; x < 8; x++)             
        {
            if (board[x][y].player == NONE)
            {
                state += "E";
            }
            else if (board[x][y].player == BLACK)
            {
                state += "B";
            }
            else if (board[x][y].player == WHITE)
            {
                state += "W";
            }
        }
	}
    
    return state;
}

//devuelve si una cadena recibida es un tablero valido o no
function ValidBoard(state)
{
    //si no mide 64 no es valido
    if (state.length != 64)
	{
        return false;
    }
        
    //si algun elemento no es ni E ni B ni W no es valido    
    for(i = 0; i < state.length; i++) 		
	{            
		ch = state.charAt(i);
		
		if (ch != "B" && ch != "W" && ch != "E")       
        {       
		    return false;
        }		
	}  
    
    return true;
}

//obtiene el numero de piezas que tiene el jugador player
function GetScore(player)
{
    var score = 0;
    
    for (x = 0; x < width; x++)   
    {
        for (y = 0; y < height; y++)         
        {
            if (board[x][y].player == player) 
            {
                score++;    
            }
        }
    }
    
    return score;
}

//obtiene el numero de piezas que hay en tablero
function GetPieces(state)
{
    var pieces = 0;
        
    //todo lo que no sea E es una pieza   
    for(i = 0; i < state.length; i++) 		
	{            
		ch = state.charAt(i);
		
		if (ch != "E")       
        {       
		    pieces++;
        }		
	}  
    
    return pieces;
}

//construye la lista de movimientos bonita
function BuildMovesList(moves_r)
{
	var moves_string = '';
	var i;
				
	for(i = 0; i < moves_r.length; i += 2)
	{
		moves_string += (i/2+1) + '. ' + moves_r.charAt(i) + moves_r.charAt(i+1) + ' ';
		
		if ((i + 2) % 20 === 0)
        {
			moves_string += '<br />';
        }
	}
				
	return moves_string;
}

function GameEnded()
{
    return 'Game finished.';
}

function WriteMessageChat(username, msg)
{
	document.getElementById('CHAT').innerHTML = username+ " says:  " + msg + "<br />" +document.getElementById('CHAT').innerHTML;
}

function SendMsg(msg)
{
	if(msg != "")
	{
		var xmlHttp = ajaxObject();

		xmlHttp.open("GET","send_chat.php?id="+id_game+"&msg="+msg, true);
		xmlHttp.send(null);
		
		WriteMessageChat(username, msg);
	}
}


function clearinputmsg(input)
{
	if(f_time)
	{
		input.value = "";
		f_time = false;
	}
}

function setinputmsg(input)
{
	//input.value = "Chat...";
}


function pressedEnter(event,input) 
{
	var code = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	
	if (code == 13) 
	{
		SendMsg(input.value);
		input.value = "";
		return false;
	} 
	else 
	{ 
		return true;
	}
}

function SetglobalXml(x)
{
	globalXml = x;
}

function UpdateMove(moves_r)
{	
	moves = moves_r;
	document.getElementById('moves').innerHTML = BuildMovesList(moves);
}

function UpdateStatus()
{
	document.getElementById('turn').innerHTML = status;
}

function UpdateScore()
{
	//actualizamos el numero de piezas de cada jugador
    document.getElementById('pwhite').innerHTML = GetScore(WHITE);
    document.getElementById('pblack').innerHTML = GetScore(BLACK);
}

function SetUsername(x)
{
	username = x;
}

function UpdateStatusWatcher()
{
	var r, c;
	
	//si ha habido movimientos
	if (moves.length > 0)
	{
		//buscamos de quien fue el ultimo movimiento
		c = moves.charCodeAt(moves.length - 2) - 97;
		r = moves.charAt(moves.length - 1) - 1;
		
		//si el ultimo en poner fue el negro
		if (board[c][r].player == BLACK)
		{
			if (AnyMoves(WHITE))
            {
				status = "White's turn.";
            }
			else if (AnyMoves(BLACK))
            {
				status = "Black's turn.";
            }
			else
			{
				status = GameEnded();
				run_game_end();
			}	
		}
		//si el ultimo en poner fue el blanco
		else
		{
			if (AnyMoves(BLACK))
            {
				status = "Black's turn.";
            }
			else if (AnyMoves(WHITE))
            {
				status = "White's turn.";
            }
			else
			{
				status = GameEnded();
				run_game_end();
			}
		}
	}
	else
	{
		if (AnyMoves(BLACK))
		{
			status = "Black's turn.";
		}
		else
		{
			status = GameEnded();
			run_game_end();
		}
	}
}

function UpdateTitle()
{
	//actualizamos el titulo de la ventana
	if (role != NONE)
	{
		if (yourTurn)
        {
			document.title = "Your turn - eOthello";
        }
		else
		{
			if (status == GameEnded())
            {
				document.title = "Game finished - eOthello";
            }
			else
            {
				document.title = "Opponent's turn - eOthello";
            }
		}
	}
	else
	{
		document.title = status.substring(0,status.length - 1) + " - eOthello";
	}
}

function connect_game()
{
	var xmlHttp = ajaxObject();
	SetglobalXml(xmlHttp);
	
	xmlHttp.onreadystatechange = function() 
	{
		if (xmlHttp.readyState == 4 && xmlHttp.status >= 200 && xmlHttp.status < 300) // si la peticion tiene exito
		{    	
			var response = '';
			
		    try 
	        {
				response = eval('(' + xmlHttp.responseText + ')');
	        } 
	        catch(e) 
	        {
	            noerror = false;
	        }
		
			if(response != '' && response != '()')
			{
				var board = response.board;
				var chat = response.chat;
				
				if(typeof board != "undefined")
				{
					var update = ValidBoard(board.board) && GetPieces(board.board) > GetPieces(GetBoardState()) && (!yourTurn || role == NONE);
					
					if (update)
					{
						//actualizamos el tablero con la cadena recibida
						SetBoardState(board.board);
						UpdateMove(board.moves);
						UpdateScore();
						
						//si somos jugadores
						if (role != NONE)
						{
							//si con el nuevo tablero podemos mover
							if (AnyMoves(role))
							{
								//entonces es nuestro turno
								status = 'Your turn.';
								yourTurn = true;
							} 
							else
							{
								yourTurn = false;
								
								if (AnyMoves(OtherPlayer()))
								{
									status = 'Opponent\'s turn again (you have no possible moves).';
								}
								else
								{
									status = GameEnded();
									run_game_end();
								}
							}
							
							UpdateTitle();
						}
						else
						{
							UpdateStatusWatcher();
						}
						
						UpdateStatus();
					}
				
					time_board = board.timestamp;
				}
				if(typeof chat != "undefined" && chat != "")
				{
					var max_time = 0;
					
					for(i = 0; i < chat.length; i++)
					{
						var username = chat[i].username;
						var msg = chat[i].message;
						
						if(chat[i].timestamp > max_time) 
	                    {
	                        max_time = chat[i].timestamp;
	                    }
						
						if(role != NONE)
	                    {
	                        WriteMessageChat(username, msg);
	                    }
					}
					
					time_chat = max_time;
				}
				
				noerror = true;	
			}
			else
			{
				noerror = false;
			}
		}
		
		if (xmlHttp.readyState == 4) //si la peticion se completa
		{      
			
			if (!noerror)
	        {
				setTimeout(function(){ connect_game(); }, retry_interval); 
	        }
			else
	        {
				connect_game();
	        }
	
			noerror = false;
		}
	};
	
	xmlHttp.open("GET","game_events.php?time_board="+time_board+"&time_chat="+time_chat+"&game_id="+id_game, true);
	xmlHttp.send(null);
}

function SetMoves(x)
{
	moves = x;
}

function UpdateMoves(x, y)
{
	var r, c;
	
	r = String.fromCharCode((97 + x));
	c = y + 1;
	moves += r;
	moves += c;
	
	document.getElementById('moves').innerHTML = BuildMovesList(moves);
}

function SetStatus(x)
{	
    status = x;     
}

//establece si es tu turno o no
function SetTurn(x)
{	
    if (x == 1)
    {
		yourTurn = true;
    }
    else
    {
        yourTurn = false; 
    }
}

//establece el rol de esta persona en la partida (puede ser un jugador blanco, negro o un espectador)
function SetRole(x)
{	
    role = x;     
}

//establece el identificador de partida
function SetIdGame(x) 
{	
    id_game = x;            
}

function SendMove(x, y)
{
    //mandamos el movimiento usando AJAX
    //importante:indicamos el numero de movimiento que es (para evitar el problema de que lleguen 2 movimientos en el orden inverso cuando un jugador mueve 2 veces)

	var xmlHttp = ajaxObject();
        
    xmlHttp.open("GET","updateboard.php?num=" + (((moves.length) / 2) + 1) + "&x=" + x + "&y=" + y + "&id=" + id_game, true);
    xmlHttp.send(null); 	   
}

//pone una pieza en board[x,y] pero solo en caso de que se pueda, y ademas captura todas las piezas resultado de este movimiento (por tanto esta funcion es claramente diferente de RawPutPiece)
function PutPiece(x, y) 
{   
    if (!CanPutPiece(x, y)) 
    {
		return;
    }
	
	FlipPieces(x, y); 
	SendMove(x, y);
	UpdateScore();
	UpdateMoves(x, y);
	
	//tras hacer el movimiento ya no es nuestro turno si el rival puede hacer alguno 
	if (AnyMoves(OtherPlayer()))
	{
		status = 'Opponent\'s turn.';
		yourTurn = false;
	}
	else
	{
		//si podemos hacer algun movimiento nosotros
		if (AnyMoves(role))
		{
			yourTurn = true;
			status = 'Your turn again (your opponent has no possible moves).';
		}
		//si no, la partida ha terminado
		else
		{
			yourTurn = false;
			status = GameEnded();
			run_game_end();
		}
	}
	
	UpdateStatus();
	UpdateTitle();
}

function ini_board(board_r, game_id, role_r, turn, status_string,moves_r,username)
{
	LoadImages();
	InitializeBoard();
	SetBoardState(board_r);
	SetIdGame(game_id);
	SetUsername(username);
	SetRole(role_r);
	SetMoves(moves_r);
	SetTurn(turn);
	SetStatus(status_string);
	UpdateStatus();
	UpdateTitle();
	UpdateScore();
	
	if (role_r == NONE)
	{
		UpdateStatusWatcher();
		UpdateStatus();
	}
}

function showOffLineGameMenu()
{
	if(showofflinegamemenu) document.getElementById('MOVES_INTERACTIVE').innerHTML = '<table class = "centered-table"><tr><td><img src="./images/f4.png"  Onclick="go_ini();" /></td><td><img src="./images/f1.png"  Onclick="last_move();" /></td><td><img src="./images/f2.png"  Onclick="next_move();" /></td><td><img src="./images/f3.png"  Onclick="go_end();" /></td></tr></table>';
}

function ini_offline_game(moves, status_string)
{
	LoadImages();
	InitializeBoard();
	SetStatus(status_string);
	UpdateStatus();
	
	//showOffLineGameMenu();
	
	showofflinegamemenu = true;
	
	SetMoves(moves);
	move_index = moves.length;
	
	run_game();
}	

function go_end()
{
	move_index = moves.length;
	run_game();
}

function go_ini()
{
	move_index = 0;
	run_game();
}

function next_move()
{
	if(move_index < moves.length)
	{
		move_index += 2;
		run_game();
	}
}

function last_move()
{
	if(move_index > 0)
	{
		move_index -= 2;
		run_game();
	}
}

function run_game()
{
	SetBoardState("EEEEEEEEEEEEEEEEEEEEEEEEEEEWBEEEEEEBWEEEEEEEEEEEEEEEEEEEEEEEEEEE");
	role = BLACK;
	var moves_offline = "";
	
	for(var i = 0; i < move_index; i +=2)
	{
		var move_x = moves.charCodeAt(i) - 97;
		var move_y = moves.charCodeAt(i+1) - 49;
		
		FlipPieces(move_x, move_y); 
		
		if (AnyMoves(OtherPlayer()))
		{
			role = OtherPlayer();
		}
		
		moves_offline += String.fromCharCode((97 + move_x));
		moves_offline += move_y + 1;
	}
	
	if(document.getElementById('moves'))
	{
		document.getElementById('moves').innerHTML = BuildMovesList(moves_offline);
	}

	UpdateScore();
}

function run_game_end()
{
	showofflinegamemenu = true;
	showOffLineGameMenu();
	move_index = moves.length;
	
	run_game();
}