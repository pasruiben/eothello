<?php
//se conecta a la base de datos, incluye las funciones php necesarias y abre el head
include_once('inc/h_header.php');
//cierra el head y abre el body, a�adiendo la informaci�n sobre la p�gina (usuarios registrados, [partidas completadas]? y partidas en juego)
include_once('inc/h_footer_body.php');
//muestra el formulario de conexi�n si no est�s conectado o la barra de opciones si s� lo est�s
include_once('userbox.php');
?>
//muestra las opciones del footer y las im�genes de valid xhtml y css
include_once('inc/footer.php');
?>