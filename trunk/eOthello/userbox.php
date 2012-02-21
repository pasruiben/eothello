<div class="headfootstyle">
<?php 

function show_user_box()
{
	echo '<div id="userbox">                
					<div id="greeting">						<p>							Hi ' . $_SESSION['username'] . ', have a nice stay! - 							<a href="./logout.php">Log out</a> - 							<a href="./changepassword.php">Change password</a>						</p>						<div id="CHALLNOT"></div>						<script type="text/javascript">Start(\'CHALLNOT\',\'chall_noti.php\');</script>					</div>
          <table class = "centered-table-aux">
						<tr>
							<td><a href="./index.php">Index</a></td>
                <td>-</td>
							<td><a href="./create_game.php">Create game</a></td>
                <td>-</td>
							<td><a href="./games.php?cond=pending">Join game</a></td>
                <td>-</td>
							<td><a href="./games.php?cond=mine">My games</a></td>
                <td>-</td>
							<td><a href="./stats.php?player='.$_SESSION['id_player'].'">My stats</a></td>
                <td>-</td>
							<td><a href="./opponents.php?player='.$_SESSION['id_player'].'">My opponents</a></td>
						</tr>
        	</table>
				</div>';
}
function show_login_form()
{
	echo '
		<div id="loginError"></div>
		<div id="loginbox">
			<form id="login-form" method="post" action="./index.php" onsubmit="return checkLogin()"> 
      	<p>
    			<label title="Username">Username</label>
        	<br/>           	
					<input class="textinput" tabindex="1" accesskey="u" name="username" type="text" maxlength="30" id="username" /> 
  				<br/>		
    			<label title="Password">Password</label>
    			<br/>
    			<input class="textinput" tabindex="2" accesskey="p" name="password" type="password" maxlength="15" id="password" />  
					<br/>
					<input class="button" type="submit" name="login" value="Go!" />
					<br/>
        </p> 
			</form>			<a href="./lostpassword.php" title="Lost Password">Lost password?</a>			<br/>
			No account? 			<a href="./register.php" title="Register">Sign up!</a>        
  	</div>';
}
global $seed;
//si no estamos conectados
if (!isLoggedIn())
{
    //entra cuando le das a Go!
	if (isset($_REQUEST['login']))
	{        
		$errors = validateLogin($_REQUEST['username'], $_REQUEST['password']);
		if(!$errors)
		{
			$idd = get_id_player($_REQUEST['username']);
			if($idd != "")			{
				$_SESSION['id_player'] = $idd;
				$_SESSION['username'] = $_REQUEST['username'];
				$cookie = time().":".$idd.":".time();
				setcookie ("ident", XOREncrypt($cookie, $seed));
				show_user_box();
				SetLoggedIn($idd, 1);
			}
			else			{
				echo 'error at login'; 
			}
		}
		else
		{
   		echo $errors;
			show_login_form();
		}
	}
	else	
		show_login_form();	
} 
else
	show_user_box();
?>
</div>
<hr class="line"/>