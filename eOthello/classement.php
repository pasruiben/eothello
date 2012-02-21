<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>		
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
		<link rel="stylesheet" type="text/css" href="style.css">
        <title>Classement des joueurs </title>
	</head>
    <body>
    
<?php

    //país del que vamos a sacar información (por defecto todos)
    $pais = "ALL";
    if (isset($_GET['pays']))
        $pais = $_GET['pays'];    

    //obtenemos la información de jugadores en la variable $info
	$ch = curl_init('http://www.ffothello.org/classement/classement.php'); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$info = curl_exec ($ch); 
	curl_close ($ch); 
    
    //mostrar sector
    //echo '<div id="sector"><p><h1>SECTOR ' . $sector . '</h1></p></div>';
    
    //extracción de la información (6 trozos nos interesan)
    if (preg_match_all('/<tr>.*?<td>(<b>)?(.*?)(<\/b>)?<\/td>.*?<td>(<b>)?(.*?)(<\/b>)?<\/td>.*?<td>(<b>)?(.*?)(<\/b>)?<\/td>.*?<td>(<b>)?(.*?)(<\/b>)?<\/td>.*?<td>(<b>)?(.*?)(<\/b>)?<\/td>.*?<td>(<b>)?(.*?)(<\/b>)?<\/td>.*?<\/tr>/s', $info, $gr, PREG_SET_ORDER))
	{
        //1: posición
        //2: puntuación
        //3: incremento
        //4: partidas
        //5: nombre
        //6: país
        
        $paises = array("ALL");
        
        //rellenar array de paises
		foreach ($gr as $jugador)
        {            
            if (array_search($jugador[17], $paises) == false)
                array_push($paises, $jugador[17]);                        
        }
        
        echo '
        <form name="eligePais" action="classement.php" method="GET">        
        <table class="centered-table">
        	<tr>        		
                <td>
                    <select name="pays" onChange="document.forms[0].submit();">
                        <option value="">Pays';
                    
        foreach($paises as $p)
        {
            echo '<option value="' . $p . '">' . $p;
        }

        echo '
                    </select>
        		</td>
        	</tr>
        </table>
        </form>';
        
        //mostrar pais
        echo '<div id="sector"><p><h1>' . $pais . '</h1></p></div>';
    
        //mostrar tabla
        echo '<br><table class="centered-table">';
        echo '<tr class="no"><td></td><td>Class.</td><td>Inc.</td><td>Part.</td><td>Nom</td>';
        
        if ($pais == "ALL")
            echo '<td>Pays</td>';
            
        echo '</tr>';
        
        //recorremos la información obtenida de jugador en jugador
		foreach ($gr as $jugador)
        {            
            if ($pais == "ALL" || $jugador[17] == $pais)
            {
                echo '<tr class="yes">';
                
                echo '<td>' . $jugador[2] . '</td>';
                echo '<td>' . $jugador[5] . '</td>';
                echo '<td>' . $jugador[8] . '</td>';
                echo '<td>' . $jugador[11] . '</td>';
                echo '<td>' . $jugador[14] . '</td>';
                
                if ($pais == "ALL")
                    echo '<td>' . $jugador[17] . '</td>';
            
                echo '</tr>';
            }
        }
        
        echo '</table><br>';
	}
	else
		echo '<br />No players were found! :o';
        
?>

	</body>
</html>