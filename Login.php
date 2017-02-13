<?php

$error = "";
$emailCookie = "";
$passCookie = "";
$checked = "";
if((isset($_COOKIE['email'])) && (isset($_COOKIE['pass'])))
{
	$emailCookie = $_COOKIE['email'];
	$passCookie = $_COOKIE['pass'];
	$checked = "checked";
}
$DisplayForm = true;

?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
document.write("<php echo $script; ?>");
</script>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="CSS/DVDFlix.css"/>

<!--Check for cookie and get data
   Check for form data.  If exists:
     Check to see if email exists in database.  If not, make error message visible.
     If does exist, write to database, write session data, write cookie if checked.
     Send control to login.
 Set background of container to 'dodgerblue' with jquery.-->
</head>

<body style="text-align:center">
<?php
if($DisplayForm)
{
?>
<div id="container">
<div id="banner">
<p id="logo">DVD Flix</p>
</div>
<div id="loginbox">
<div id="img">
<img src="Theater.jpg" alt=""/>
</div>
<form name="logform" method="post" action="" id="logform"><br />
<h1>Member Sign In</h1><br />
<div id="formitems">
Member ID: <input type="text" id="membertxt" name="membertxt" value="<?php echo $emailCookie;?>" /><br />
Password: <input type="password" id="passtxt" name="passtxt" value="<?php echo $passCookie;?>" /><br />
<span id="errmsg">
<?php echo $error; ?></span>
</div>
<a href="ForgotPass.php">Email my password to me</a><br />
<a href="Member.php">I am a new member</a><br />
<p id="rem">&nbsp;<input type="checkbox" id="remch" name="remch" value="1" <?php echo $checked; ?>/>Remember me on this computer.</p><br />
<input type="submit" id="submit" name="submit" value="submit" /><br /><br /><br />

<div id="hitcount">
Hits: <?php include('inc_hit_counter.php'); mysql_close($DBConnect);?>
</div>
</form>
</div>
</div>
<?php
}
if(isset($_POST['submit']))
{	
	$DisplayForm = false;
	
	include("inc_dvdflix.php");
	
	if((!empty($_POST['membertxt'])) && (!empty($_POST['passtxt'])))
	{
		$memberEmail = $_POST['membertxt'];
		$memberPass = $_POST['passtxt'];
		$query = mysql_query("SELECT EMAIL, PASSWORD FROM members WHERE EMAIL ='$memberEmail' AND PASSWORD ='$memberPass'");
		while($row = mysql_fetch_array($query) or die(mysql_error()))
		{
			if((!empty($row['EMAIL']) || !empty($row['PASSWORD'])))
			{
				if(($memberEmail != $row['EMAIL']) || ($memberPass != $row['PASSWORD']))
				{
	    	 		mysql_close($DBConnect);
	    	 		$error = "Invalid Login";
	    	 		$DisplayForm = true;
	    	 		

	    	 		
	    	 	}
	    	 	else
	    	 	{
	    	 		session_start();
	    	 		$query = mysql_query("SELECT MEMBERID, FIRSTNAME, LASTNAME FROM members WHERE EMAIL = '$memberEmail' AND PASSWORD = '$memberPass'");
					$row = mysql_fetch_array($query);
					$_SESSION['memberID'] = $row['MEMBERID'];
					$_SESSION['firstName'] = $row['FIRSTNAME'];
					$_SESSION['lastName'] = $row['LASTNAME'];
	    	 		if (isset($_POST['remch']))
					{
						setcookie("email", $memberEmail, time()+(60*60*24*7));
						setcookie("pass", $memberPass, time()+(60*60*24*7));
					}
					mysql_close($DBConnect);
	    	 		echo "<script>".
	    	 		 "location.assign('DVDs.php?" . 
	    	 		 	session_id() . "')".
	    	 		 "</script>";	 
				}	
			}
			else
			{
				mysql_close($DBConnect);
				$error = "Invalid Login";
				$DisplayForm = true;
			}
		}
	}
	else
	{
		mysql_close($DBConnect);
			
		$error = "Invalid Login";

		$DisplayForm = true;
	
	}
}

?>
</body>
</html>