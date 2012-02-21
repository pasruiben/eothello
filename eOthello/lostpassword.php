<?php include_once('inc/h_header.php');	?><script	type="text/javascript" src="js/validator.js"></script><?php include_once('inc/h_footer_body.php'); ?><div class="lhome"><a href="./index.php"> Index </a></div><hr class="line" /><?php
function lost_form($user,$email){
	echo '
			<div id = "errorList"></div>
      <form action="./lostpassword.php" method="post" onsubmit="return checkLost()"> 
    		<table class ="changetable">
        	<tr>
          	<td class ="topcell"><label id = "userlabel" for="username"><b>Username:</b></label></td>
            <td>
            	<table class = "collapseTable">
          	    <tr>
            	  	<td><input id="username" name="username" value="'.$user.'" type="text" maxlength="30" onkeyup="dinamicUserV()"></input></td>
             		</tr>
              	<tr>
       		      	<td><div id="userError" class ="valError"></div></td>
              	</tr>
            	</table>
          	</td>
        	</tr>
    			<tr>
          	<td class ="topcell"><label id = "maillabel" for="email"><b>Email:</b></label></td>
            <td>
          	  <table class = "collapseTable">
            	  <tr>
              	  <td><input id="email" name="email"  value="'.$email.'" type="text" maxlength="255" onkeyup="dinamicEmailV()"></input></td>
                </tr>
                <tr>
                	<td><div id="emailError" class ="valError"></div></td>
                </tr>
              </table>
            </td>
    			</tr>
    			<tr>
    				<td></td>
            <td><input name="lostpass" type="submit" value="Reset Password" class="button"></input></td>          	</tr>
    		</table>        
      </form>';
}
if (isset($_REQUEST['lostpass']))
{
	$errors = validateLostPassword($_REQUEST['username'], $_REQUEST['email']);
	if (!$errors)
	{
		$newpass = generate_code(10);		
		if(change_password($_REQUEST['username'],$newpass))		{
			if (sendLostPasswordEmail($_REQUEST['username'], $_REQUEST['email'], $newpass))
			{
				echo "Your password has been reset, an email containing your new password has been sent to your inbox.<br /><a href='./index.php'>Click here to return to the homepage.</a>";
			}
			else			{
				echo "try again";
			}
		}
		else		{
			echo 'error at lostpass';
		}
	}
	else
	{
		$errors ='<div class="phpErrors"><ul>'.$errors.'</ul></div>';
		echo $errors;
		lost_form($_REQUEST['username'], $_REQUEST['email']);
	}
}
else
{
	lost_form("","");
}
include_once('inc/footer.php'); ?>