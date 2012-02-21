<?php 
//se conecta a la base de datos, incluye las funciones php necesarias y abre el head
include_once('inc/h_header.php');
//cierra el head y abre el body, añadiendo la información sobre la página (usuarios registrados, [partidas completadas]? y partidas en juego)
include_once('inc/h_footer_body.php');    //muestra el formulario de conexión si no estás conectado o la barra de opciones si sí lo estás
include_once('userbox.php');
?>
<h1>Useful sites</h1>
	<ul>
		<li>
			<a href="http://othellogateway.com/rose/book.pdf">Othello: A Minute to Learn... A Lifetime to Master</a> by Brian Rose.
		</li>
		<li>
			Many other links at <a href="http://othellogateway.com/">http://othellogateway.com/</a>.
		</li>
		<li>
			<a href="http://othello.federation.free.fr/livres/beginner-Randy-Fang.pdf">Othello: From Beginner to Master</a> by Randy Fang.
		</li>
		<li>
			<a href="http://www.othello.dk/book/index.php/Strategy">The Othello Wiki Book Project</a>.
		</li>
		<li>
			<a href="http://home.swipnet.se/~w-50714/othello/tutorial/intro.htm">Othello: An Introduction to Strategy and Tactics</a>.
		</li>
		<li>
			<a href="http://www.geocities.com/jjjlll_77/estra.html">Estrategias y tácticas de Othello</a>.
		</li>
		<li>
			<a href="http://jorgebandres.googlepages.com/manualesylibros">Manuales y libros</a>.
		</li>
		<li>
			<a href="http://www.ffothello.org/FFORUM/lectures.php">Les meilleures lectures</a>.
		</li>
		<li>
			<a href="http://www.site-constructor.com/othello/Present/Basic_Strategy.html">Basic Othello Strategy</a>.
		</li>
		<li>
			<a href="http://samsoft.org.uk/reversi/">Reversi - An Animated Guide</a>.
		</li>
		<li>
			<a href="http://www.cs.ualberta.ca/~mburo/log.html">Logistello</a>.
		</li>
		<li>
			<a href="http://www.cs.ualberta.ca/~mburo/ps/opening.ps.gz">L'apprentissage des ouvertures chez Logistello</a>.
		</li>
	</ul>
<?php 
//muestra las opciones del footer y las imágenes de valid xhtml y css
include_once('inc/footer.php');     
?>