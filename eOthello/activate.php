<?php
include_once('inc/h_header.php');
include_once('inc/h_footer_body.php');
include_once('userbox.php');
if(isset($_REQUEST['uid']) && isset($_REQUEST['actcode']))
{
	$uid = (int)$_REQUEST['uid'];
	$actcode = $_REQUEST['actcode'];
	if(id_exist($uid))	{
		if(!id_active($uid))		{
			if(valid_actcode($uid,$actcode))			{
				if(activateUser($uid))				{
					echo "Thank you for activating your account, you can now login.<br /><a href='./index.php'>Click here to login.</a>";
				}
				else				{
					echo "Activation failed! Please try again.<br />If the problem persists please contact the webmaster.";
				}
			}
			else			{
				echo 'invalid activation code';
			}
		}
		else		{
			echo 'user already active';
		}
	}
	else	{
		echo 'user not exists';
	}
}
 include_once('inc/footer.php');	?>