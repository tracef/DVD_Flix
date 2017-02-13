<?php
$memID = "";
$fname = "";
$lname = "";
$dvdsArray = array();
$result = "";
$link = "Queue.php";

session_start();
if((isset($_SESSION['memberID'])) && (isset($_SESSION['firstName'])) && (isset($_SESSION['lastName'])))
{
	$memID = $_SESSION['memberID'];
	$fname = $_SESSION['firstName'];
	$lname = $_SESSION['lastName'];
	$link = "queue.php?PHPSESSID=" . session_id();
	queue($memID, $link);	
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Add DVDs</title>
</head>

<body>
<?php 
function queue($memID, $link)
{
	$errors = 2;
	$dvdsArray = $_POST['addcheck'];
	$orderNo = "";
	try
	{	
		include('inc_dvdflix.php');
		
		foreach ($dvdsArray as $dvds)
		{
			if(duplicate($memID, $dvds))
			{
				continue;
			}
			else
			{
				$orderNo = findnextorder($memID);
				$query = "INSERT INTO queue (MEMBERID, DVDID, ORDERNO) VALUES ('$memID', '$dvds', '$orderNo')";
				$return = mysql_query($query);	
			}	
		}
		$errors = 0;
	}
	catch(exception $e) 
	{
		die(mysql_error());
		$errors = 1;
	} 
	if ($errors == 1)
	{
		mysql_close($DBConnect);
		echo "<p>Error adding DVDs to Queue.</p>";
	}
	else if($errors == 0)
	{
		mysql_close($DBConnect);
		echo "<script>location.assign('" . $link . "')</script>";	
	}
}
function findnextorder($memID)
{
	$max = "";
	try
	{
		$query = mysql_query("SELECT MAX( ORDERNO ) AS max FROM queue WHERE MEMBERID ='$memID'");
		$row = mysql_fetch_array($query);
		if(!empty($row))
		{
			$max = $row['max'] + 1;
		}
		else
		{
			$max = 1;
		}
	}
	catch(exception $e)
	{
		die(mysql_error());
	}
	return $max;
}
function duplicate($memID, $dvdID)
{
	try
	{	
		$query = mysql_query("SELECT DVDID FROM queue WHERE MEMBERID ='$memID' AND DVDID ='$dvdID'");
		while($row = mysql_fetch_array($query))
		{
			if(!empty($row['DVDID']))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	catch(exception $e)
	{
		die(mysql_error());
		echo "Problem querying database.";
	}
}
?>
</body>
</html>
