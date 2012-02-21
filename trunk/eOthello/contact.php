<?php

include_once('inc/h_header.php');
include_once('inc/h_footer_body.php');
include_once('userbox.php');

if(!isLoggedIn()) echo '<div class="lhome"><a href= "./index.php">Index</a></div>';

?>

<p>	
		Don't hesitate to contact us if you have any doubts, suggestions or
		feedback about the website. We are easily reachable at the following
		e-mail address:
</p>

<div id="email">
	<p><b>eothelloadmin@gmail.com</b></p>
</div>

<?php 

include_once('inc/footer.php')

?>