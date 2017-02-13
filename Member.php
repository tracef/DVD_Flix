<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
<title>Members</title>
<style type="text/css">
</style>
<link rel="stylesheet" href="CSS/DVDFlix.css"/>
</head>

<!--get form data
    If email is already in table, make error message visible
    Otherwise insert it into the table and send control back to the login page
    -->

<body>
<?php
$error = "";
$emailError = "";
$firstName = "";
$lastName = "";
$street = "";
$city = "";
$state = "";
$zip = "";
$email = "";		
$password = "";
$DisplayForm = true;

if (isset($_POST['submit']))
{

	if((!empty($_POST['fname'])) && (!empty($_POST['lname'])) && (!empty($_POST['street'])) && (!empty($_POST['city'])) && (!empty($_POST['state'])) && (!empty($_POST['zip'])) && (!empty($_POST['email'])) && (!empty($_POST['pass'])))
	{
		$firstName = $_POST['fname'];
		$lastName = $_POST['lname'];
		$street = $_POST['street'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$email = $_POST['email'];		
		$password = $_POST['pass'];
		if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-zA-Z]{2,3})$/",$email)!=1)
		{
			$emailError = "Invalid Email Address";
			$DisplayForm = true;

		}
		else
		{
			include('inc_dvdflix.php');
			$query = mysql_query("SELECT EMAIL FROM members WHERE EMAIL = '$email'");
			$row = mysql_fetch_array($query);
			
				if (!empty($row['EMAIL']))
				{
					$emailError = "An account with this email already exists.";
					$DisplayForm = true;
				}
				else
				{
					mysql_query("INSERT INTO members (LASTNAME, FIRSTNAME, ADDRESS, CITY, STATE, POSTAL_CODE, EMAIL, PASSWORD)".
					" VALUES ('$lastName', '$firstName', '$street', '$city', '$state', '$zip', '$email', '$password')");
					$DisplayForm = false;
				}
			
		}	
	}
	else
	{
		$error = "Please enter all fields provided.";
		$DisplayForm = true;
	}
}
else
{
	$DisplayForm = true;
}

if ($DisplayForm)
{
?>
<div id="container">
	<div id="banner">
		<div id="logo">DVD Flix</div>
	</div>
	
	<form id="member" action="Member.php" method="post">
	<fieldset id="memberfieldset">
	<legend>New Member Information</legend>
   	<br />
   	
	<label for="fname">First, Last Name</label>
	<input name="fname" id="fname" value="<?php if(isset($_POST['fname'])) echo $_POST['fname'];?>" />&nbsp;
	<input name="lname" id="lname" value="<?php if(isset($_POST['lname'])) echo $_POST['lname'];?>" />
	<br />
	
    <label for="street">Street Address&nbsp;</label>
    <input name="street" id="street" value="<?php if(isset($_POST['street'])) echo $_POST['street'];?>" />
    <br />
    
    <label for="city">City, State, Zip&nbsp;</label>
    <input name="city" id="city"  value="<?php if(isset($_POST['city'])) echo $_POST['city'];?>"/>,&nbsp;
    <select id="state" name="state" >
	    <option>AL</option>
	    <option>AK</option>
	    <option>AZ</option>
	    <option>AR</option>
	    <option>CA</option>
	    <option>CO</option>
	    <option>CT</option>
	    <option>DE</option>
	    <option>FL</option>
	    <option>GA</option>
	    <option>HI</option>
	    <option>ID</option>
	    <option>IL</option>
	    <option>IN</option>
	    <option>IA</option>
	    <option>KS</option>
	    <option>KY</option>
	    <option>LA</option>
	    <option>ME</option>
	    <option>MD</option>
	    <option>MA</option>
	    <option>MI</option>
	    <option>MN</option>
	    <option>MS</option>
	    <option>MO</option>
	    <option>MT</option>
	    <option>NE</option>
	    <option>NV</option>
	    <option>NH</option>
	    <option>NJ</option>
	    <option>NM</option>
	    <option>NY</option>
	    <option>NC</option>
	    <option>ND</option>
	    <option>OH</option>
	    <option>OK</option>
	    <option>OR</option>
	    <option>PA</option>
	    <option>RI</option>
	    <option>SC</option>
	    <option>SD</option>
	    <option>TN</option>
	    <option>TX</option>
	    <option>UT</option>
	    <option>VT</option>
	    <option>VA</option>
	    <option>WA</option>
	    <option>WV</option>
	    <option>WI</option>
	    <option>WY</option>
    </select>
    <input name="zip" id="zip"  value="<?php if(isset($_POST['zip'])) echo $_POST['zip'];?>"/>
    <br />
    
    <label for="email">Email Address&nbsp;</label>
    <input name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" />
    <span id="emailmsg" style="z-index:5"><?php echo $emailError; ?></span>
    <br />
    
    <label for="pass">Password&nbsp; </label>
    <input type="password" name="pass" id="pass" value="<?php if(isset($_POST['pass'])) echo $_POST['pass'];?>"/>
    <input type="submit" id="submit" name="submit" value="submit" />
    <br />
    
    <span id="emailmsg" style="z-index:5"><?php echo $error; ?></span>
    </fieldset>
    </form>
</div>
<?php
}
if (!$DisplayForm)
{
	echo "<script>location.href='Login.php';</script>";	
}
?>
</body>

</html>