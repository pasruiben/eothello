<?php 

include_once ('inc/db_connect.php');

try
{
	$nombre_fichero = "openings.txt";
	$fichero_texto = fopen ($nombre_fichero, "r");
	$contenido_fichero = fread($fichero_texto, filesize($nombre_fichero));
	
	$lines = explode("\n", $contenido_fichero);
	
	$id = 1;
	
	foreach ($lines as $line)
	{
		$query = "INSERT INTO openings (id, sequence) VALUES('" .$id ."','" . $line . "');";
		echo $query;
		$dbh->exec($query);
		$id++;
	}
}
catch(PDOException $e ) 
{
	// tratamiento del error
	die("error: ".$e->GetMessage());
}

?>