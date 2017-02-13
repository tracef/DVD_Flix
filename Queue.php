<?php
$memID = "";
$fname = "";
$lname = "";
$link = "";
$DisplayForm = true;
$dvdid = array();
$title = array();
$orderno = array();
$delete = array();
session_start();
if((isset($_SESSION['memberID'])) && (isset($_SESSION['firstName'])) && (isset($_SESSION['lastName'])))
{
	$memID = $_SESSION['memberID'];
	$fname = $_SESSION['firstName'];
	$lname = $_SESSION['lastName'];	
	if(isset($_POST['q']))
	{
		$link = "DVDs.php?PHPSESSID=" . session_id();
		echo "<script>location.assign('" . $link . "')</script>";
	}     
}
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Queue</title>
<link rel="stylesheet" href="CSS/DVDFlix.css" />
<script type="text/javascript" src="jquery.js"></script>

<!--get member information, and the arraydvdids of dvds that were checked as to be deleted
    make a loop to go through the array and execute an sql statement to delete
   make three arrays and loop through and populate them with order number, dvidid, and title using
   array_push
-->
<?php
if (isset($_POST['updatequeue']))
{
	$DisplayForm = false;
	$order = array();
	$dvdTitle = array();
	$delete = $_POST['delcheck'];
	try
	{
		include('inc_dvdflix.php');
		foreach ($delete as $id)
		{
			$query = mysql_query("DELETE FROM queue WHERE MEMBERID ='$memID' AND DVDID ='$id'");
		}
	}
	catch(exception $e)
	{
		$error = die(mysql_error());
		echo "<p>$error</p>";
		mysql_close($DBConnect);
	}
	mysql_close($DBConnect);
	$DisplayForm = true;
}
?>
</head>
<body>
<?php
if ($DisplayForm)
{
?>
<div id="container">
  <div id="banner">
    <div id="logo">
      DVD Flix
    </div>
    <a href="DVDs.php" id="q">See DVDs</a>
    <a href="Login.php" id="n"><?php echo $fname . " " . $lname;?>(logout)</a>
   </div>

  <form id="queueform" method="post" action="">
    <table id='dvdtable'>
    <!--create a table by looping through arrays and writing the data
        consider the following:-->
<?php
try
{	
	include('inc_dvdflix.php');
	$query = mysql_query("SELECT ORDERNO FROM queue WHERE MEMBERID ='$memID'");
	while($row = mysql_fetch_array($query))
	{
		array_push($orderno, $row['ORDERNO']);
	}
	$i = 0;
	foreach ($orderno as $position)
	{
		$query = mysql_query("SELECT DVDID FROM queue WHERE ORDERNO ='$position' AND MEMBERID ='$memID'");
		while($row = mysql_fetch_array($query))
		{
			array_push($dvdid, $row['DVDID']);
		}
		
		$query = mysql_query("SELECT TITLE FROM dvds WHERE DVDID ='$dvdid[$i]'");
		while($row = mysql_fetch_array($query))
		{
			array_push($title, $row['TITLE']);
		}
	    if((($i + 1) % 2) == 0)
	    {
	    	echo "<tr class='blueback'><td>$position</td><td>'$title[$i]'</td><td class='checks'>".
		    "<input type='checkbox' id='delcheck' name='delcheck[]' value='$dvdid[$i]' /></td</tr>";
	    }
	    else
	    {
		    echo "<tr class='ivoryback'><td>$position</td><td>'$title[$i]'</td><td class='checks'>".
		    "<input type='checkbox' id='delcheck' name='delcheck[]' value='$dvdid[$i]' /></td</tr>";
		}
	    $i++;
    }
    echo "<tr class='ivoryback'><td colspan='3' style='text-align:center'><input type='Submit' id='updatequeue' name='updatequeue' value='Update Queue'/></td></tr>";
}
catch(exception $e)
{
	$error = die(mysql_error());
	mysql_close($DBConnect);
	echo "<p>$error</p>";
}
mysql_close($DBConnect);
?>    
       <!-- if the row is an even number, make the background light blue-->


</table>  
</form>
</div>
<?php
}
?>
</body>
</html>