<?php include_once('inc/h_header.php');	?><script	type="text/javascript" src="js/validator.js"></script><?php include_once('inc/h_footer_body.php'); ?><div class="lhome"><a href="./index.php"> Index </a></div><hr class="line" /><?php
function register_form($user, $email){
	echo '
		<form action="./register.php" method="post" onsubmit="return checkRegister()"> 
			<table class ="changetable">
				<tr>
					<td class = "topcell"><label id = "userlabel" for="username"><b>Username:</b></label></td>
					<td>
						<table class = "collapseTable">
							<tr>
								<td><input name="username" type="text" id="username" value="'.$user.'" maxlength="30" onkeyup="dinamicUserV()"/></td>
							</tr>
							<tr>
								<td><div id="userError" class="valError"></div></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class = "topcell"><label  id = "p1label" for="password"><b>Password:</b></label></td>
					<td>
						<table class = "collapseTable">
								<tr>
									<td><input name="password" type="password" id="password" maxlength="15" onkeyup="dinamicPassV()"/></td>
								</tr>
								<tr>
									<td>
									<div id="passError" class ="valError"></div>
									<div id="passStrength"></div>
									</td>
								</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><label  id = "p2label" for="password2"><b>Re-type password:</b></label></td>
					<td><input name="password2" type="password" id="password2" maxlength="15" onkeyup="dinamic2PassV()"/></td>
				 </tr>	
				 <tr>
					<td><label  class = "topcell" id = "maillabel" for="email"><b>Email:</b></label></td>
					<td>
						<table class = "collapseTable">
								<tr>
									<td><input name="email" type="text" id="email" value ="'.$email.'" maxlength="255" onkeyup="dinamicEmailV()"/></td>
								</tr>
								<tr>
									<td><div id="emailError" class ="valError"></div></td>
								</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input name="register" type="submit" value="Register" class="button" /></td> 
				</tr>	
			</table>
		</form>';
}
global $mail;
if (isset($_REQUEST['register']))
{
	$errors = validateNewUser($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['password2'], $_REQUEST['email']);	
	if (!$errors)
	{
		$code = generate_code(20);
		$id = register($_REQUEST['username'],$_REQUEST['password'],$_REQUEST['email'],$code);
		if($id != 0)		{
			if($mail)			{
				if(sendActivationEmail($_REQUEST['username'], $_REQUEST['password'], $id, $_REQUEST['email'], $code))				{
					echo "Thank you for registering, an email has been sent to your inbox, please use it to activate your account.<br /><a href='./index.php'>Click here to login.</a>";
				}
				else				{
					// aki sabemos k a fallado el envio del mail, por lo k no podra activar su cuenta, las soluciones son, k nos envie un correo y se la activamos o k le pongamos direcamente el codigo de activacion
					$link = "http://www.eothello.com/activate.php?uid=$id&actcode=$code";
					//echo 'email: eothelloadmin@gmail.com';
					echo "your activation code is: $link";
				}
			}
			else			{
				echo "Thank you for registering.<br /><a href='./index.php'>Click here to login.</a>";
			}
		}		else		{
			echo 'error at register';
		}
	}
	else
	{
		$errors ='<div class="phpErrors"><ul>'.$errors.'</ul></div>';
		register_form($_REQUEST['username'],$_REQUEST['email']);
	}
}
else
{
	register_form('','');
}
include_once('inc/footer.php'); ?>