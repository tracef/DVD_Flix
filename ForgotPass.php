<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Forgot Password</title>
<link rel="stylesheet" href="CSS/DVDFlix.css" />
<script type="text/javascript" src="jquery.js"></script>
</head>     
<body>
<?php
$emailError = "";
$sentMsg = "";
$email = "";
$password = "";
$DisplayForm = true;

if (isset($_POST['submit']))
{
	if(!empty($_POST['emailin']))
	{
		$email = $_POST['emailin'];
		include('inc_dvdflix.php');
		$query = mysql_query("SELECT EMAIL, PASSWORD FROM members WHERE EMAIL = '$email'");
		$row = mysql_fetch_array($query);
		
		if((!empty($row['EMAIL'])) && (!empty($row['PASSWORD'])))
		{
			$password = $row['PASSWORD'];
			$To = "mlanti00@pnc.edu";
			$Subject = "CNIT 363 Forgot Pass";
			$Message = "Your email address and password are: <Email: $email><Password: $password>.";
			
			$Result = mail($To, $Subject, $Message);
			
			if ($Result)
			{
				$sentMsg = "<div id='container'><p>Your message has been sent successfully.</p><br /><a href='Login.php'>Goto Login</a></div>";
				echo "<script>document.getElementById('forgotmessage').style.visibility = 'visible'</script>";
				$DisplayForm = false;
			}
			else
			{
				$emailError = "There was a problem sending your message.";
				echo "<script>document.getElementById('forgotmessage').style.visibility = 'visible'</script>";
				$DisplayForm = true;
			}
		}
		else
		{
			$emailError = "Invalid Email Address";
			echo "<script>document.getElementById('forgotmessage').style.visibility = 'visible'</script>";
			$DisplayForm = true;
		}
	}
	else
	{
		$emailError = "Invalid Email Address";
		echo "<script>document.getElementById('forgotmessage').style.visibility = 'visible'</script>";
		$DisplayForm = true;
	}
}
if ($DisplayForm)
{
?>
<div id="container">
<div id="banner">
<div id="logo">
DVD Flix
</div>
<a href="Login.php" id="n">Return to login page</a>
</div>
<form id="passform" method="post" action="" style="width:399px">
<fieldset style="width: 402px">
<legend style="font-family:Arial, Helvetica, sans-serif;font-size:12pt;font-weight:bold">Forgot Password</legend>
<br />  
<div><label style="font-family:Arial, Helvetica, sans-serif;font-size:10pt;">
Please enter your member email address:</label><input type="text" id="emailin" name="emailin" value="<?php echo $email; ?>"/></div><div id="forgotmessage">&nbsp;<?php echo $emailError; ?></div><br />
<input type="submit" id="submit" name="submit" value="Submit" /><br /><br /><br />
</fieldset> 
</form>
</div>
<?php 
}
else
{
	echo "<div id='forgotmessage'>" . $sentMsg . "</div>";
}
?>
</body>
</html>