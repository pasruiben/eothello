<?php include_once('inc/h_header.php');include_once('inc/h_footer_body.php');include_once('userbox.php');

//muestra el formulario de cambiar contraseña
function changep_form(){
	echo '
		<div id = "errorList"></div>
			<form action="./changepassword.php" method="post" onsubmit ="return checkChange()"> 
		  	<table class ="changetable">
		      <tr>
			      <td  class ="topcell"><label id="p0label" for="oldpassword"><b>Current Password:</b></label> </td>
			      <td>
			        <table class = "collapseTable">
			          <tr>			          	<td><input id="oldpassword" name="oldpassword" type="password" maxlength="15" onkeyup="dinamic3PassV()"></input></td>
			          </tr>			          <tr>
			          	<td><div id="pass2Error" class ="valError"></div></td>
			    	    </tr>
			    		</table>		      	</td>  
	      	</tr>
	       	<tr>
	       		<td  class ="topcell"><label id="p1label" for="password"><b>New Password:</b></label> </td>
	       		<td>
	        		<table class = "collapseTable">
	    	      	<tr>
	  	          	<td><input id="password" name="password" type="password" maxlength="15" onkeyup="dinamicPassV()"></input></td>
	            	</tr>
	            	<tr>
	            		<td>	            			<div id="passError" class ="valError"></div>
										<div id="passStrength"></div>
									</td>
	            	</tr>
	          	</table>
	       		</td>
	      	</tr>
	      	<tr>
	        	<td><label id="p2label" for="password2"><b>Re-type new password:</b></label> </td>
	        	<td><input id="password2" name="password2" type="password" maxlength="15" onkeyup="dinamic2PassV()"></input> </td>
	      	</tr>
	      	<tr>
	        	<td></td>
	        	<td><input class="button" name="change" type="submit" value="Change"></input>  </td>
	      	</tr>
   	 		</table>
 			</form>
';
}
if(isLoggedIn())
{
  if (isset($_REQUEST['change']))
	{
		//validación
    $errors = validateChangePassword($_SESSION['username'],$_REQUEST['oldpassword'], $_REQUEST['password'],$_REQUEST['password2']);
		//si no hay errores de validación
		if (!$errors)
		{
			if(change_password($_SESSION['username'],$_REQUEST['password']))			{
				echo "Your password has been changed! <br /> <a href='./index.php'>Return to homepage</a>";
			}
			else			{
				echo 'error at change pass';
			}
		} 
		//si hay errores los comunicamos, y volvemos a mostrar el formulario
		else
		{			$errors ='<div class="phpErrors"><ul>'.$errors.'</ul></div>';
      echo $errors;
			changep_form();
		}
	} 
	else
		changep_form();
}
include_once('inc/footer.php'); ?>