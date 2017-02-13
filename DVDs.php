<?php
$memID = "";
$fname = "";
$lname = "";
$rating = "";
$year = "";
$search = "";
$searchArray = array();
$dvdArray = array();
$connError = "";
$DisplayForm = true;
session_start();
$link = "";
if((isset($_SESSION['memberID'])) && (isset($_SESSION['firstName'])) && (isset($_SESSION['lastName'])))
{
	$memID = $_SESSION['memberID'];
	$fname = $_SESSION['firstName'];
	$lname = $_SESSION['lastName'];
	if (isset($_POST['addsub']))
	{
		$link = "AddDVDs.php?PHPSESSID=" . session_id();
		echo "<script>location.assign('" . $link . "')</script>";

	}
	$link = "Queue.php?PHPSESSID=" . session_id();		     
}
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8" />

<title>DVDs</title>

<link rel="stylesheet" href="CSS/DVDFlix.css"/>

</head>

<body>
<?php
if (isset($_POST['q']))
{
	$link = "Queue.php?PHPSESSID=" . session_id();
	echo "<script>location.assign('" . $link . "')</script>";
}

if(isset($_POST['Submit']))
{
	$rating = $_POST['ratingselect'];
	$year = $_POST['yearselect'];
	$search = $_POST['searchtxt'];
	include("inc_dvdflix.php");	
	if($DBConnect !== FALSE)
	{
		$DisplayForm = false;
		echo "<div>".
		"<form id='dvdform' method='post' action='AddDVDs.php'>".
		"<table id='searchtable'>\n".
		"<tr class='add'><td colspan=4></td><td id='add'><input type='Submit'".
		" id='addsub' name='addsub' Value='Add' /></td></tr>".
		"<tr>".
		"<th>Title</th><th>Rating</th>".
		"<th>Description</th>".
		"<th>Release Date</th>".
		"<th>Add to Queue</tr>\n";
		if($rating == "*" && $year == "*")
		{
			if($search == "")
			{
				$QueryResult = mysql_query("SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds ORDER BY TITLE");
			}
			else
			{
				$i = 0;
				$searchArray = explode(" ", $search);
				$queryString = "SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE TITLE LIKE ";
				while($i < count($searchArray))
				{	
					$queryString .= "'%" . $searchArray[$i] . "%'"; 
					$i++; 
					if($i < count($searchArray)) 
					{
						$queryString .= "OR TITLE LIKE ";
					}
				}
				$queryString .= "ORDER BY TITLE";
				$QueryResult = mysql_query($queryString);																		
			}	
		}
		else if($rating == "*" && $year != "*")
		{
			if($search == "")
			{
				$QueryResult = mysql_query("SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE DATE_RELEASED = '$year' ORDER BY TITLE");
			}
			else
			{
				$i = 0;
				$searchArray = explode(" ", $search);
				$queryString = "SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE DATE_RELEASED = '$year' AND TITLE LIKE ";
				while($i < count($searchArray))
				{	
					$queryString .= "'%" . $searchArray[$i] . "%'"; 
					$i++; 
					if($i < count($searchArray)) 
					{
						$queryString .= "OR TITLE LIKE ";
					}
				}
				$queryString .= "ORDER BY TITLE";
				$QueryResult = mysql_query($queryString);	
			}
		}
		else if($rating != "*" && $year == "*")
		{
			if($search == "")
			{
				$QueryResult = mysql_query("SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE RATING = '$rating' ORDER BY TITLE");
			}
			else
			{
				$i = 0;
				$searchArray = explode(" ", $search);
				$queryString = "SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE RATING = '$rating' AND TITLE LIKE ";
				while($i < count($searchArray))
				{	
					$queryString .= "'%" . $searchArray[$i] . "%'"; 
					$i++; 
					if($i < count($searchArray)) 
					{
						$queryString .= "OR TITLE LIKE ";
					}
				}
				$queryString .= "ORDER BY TITLE";
				$QueryResult = mysql_query($queryString);
			}
		}
		else if($rating != "*" && $year != "*")
		{
			if($search == "")
			{
				$QueryResult = mysql_query("SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE RATING = '$rating' AND DATE_RELEASED = '$year' ORDER BY TITLE");
			}
			else
			{
				$i = 0;
				$searchArray = explode(" ", $search);
				$queryString = "SELECT TITLE, RATING, DESCRIPTION, DATE_RELEASED, DVDID FROM dvds WHERE RATING = '$rating', DATE_RELEASED = '$year' AND TITLE LIKE ";
				while($i < count($searchArray))
				{	
					$queryString .= "'%" . $searchArray[$i] . "%'"; 
					$i++; 
					if($i < count($searchArray)) 
					{
						$queryString .= "OR TITLE LIKE ";
					}
				}
				$queryString .= "ORDER BY TITLE";
				$QueryResult = mysql_query($queryString);
			}
		}	
		while (($Row = mysql_fetch_assoc($QueryResult)) !== FALSE) 
		{
			echo "<tr>".
			"<td class='nowrap'>{$Row['TITLE']}</td>".
		    "<td>{$Row['RATING']}</td>".
			"<td>{$Row['DESCRIPTION']}</td>".
			"<td class='nowrap'>{$Row['DATE_RELEASED']}</td>".
			"<td class='checks'><input type='checkbox' name='addcheck[]'".
			" value='{$Row['DVDID']}' /></td></tr>\n";
		}
		echo "</table>\n".
		"</form>".
		"</div>".
		mysql_close($DBConnect);

	}
	else
	{
		$DisplayForm = true;
		$connError = "<p>Error Connecting to Database.</p>";
	}
}

if($DisplayForm)
{
?>
<div id="container">
	<div id="banner">
		<div id="logo">
			DVD Flix
		</div>
		<a href="<?php echo $link;?>" id="q">See Queue</a> 
		<a href="Login.php" id="n"><?php echo $fname . " " . $lname;?>(logout)</a>
		<form id="dvdsearch" method="post" action="">
	
		<!--Populate Ratings with MySQL Data from dvds Table.-->
		Rating:<select id="ratingselect" name="ratingselect">
		<?php
			include("inc_dvdflix.php");
			$result = mysql_query("SELECT DISTINCT RATING FROM dvds ORDER BY RATING ASC");
			$i=0;
			echo "<option>".
				 "*".
				 "</option>";
			while($array = mysql_fetch_array($result))
			{
				echo "<option>".
					 "$array[$i]".
				 	 "</option>";
			}
			mysql_close($DBConnect);
		?>
		</select>
	
		<!--Populate Years with MySQL Data from dvds Table.-->
		&nbsp;&nbsp;Year:
		<select id="yearselect" name="yearselect">
		<?php
			include("inc_dvdflix.php");
			$result = mysql_query("SELECT DISTINCT DATE_RELEASED FROM dvds ORDER BY DATE_RELEASED ASC");
			$i=0;
			echo "<option>".
				 "*".
				 "</option>";

			while($array = mysql_fetch_array($result))
			{
				echo "<option>".
					 "$array[$i]".
					 "</option>";
			}
			mysql_close($DBConnect);
		?>
		</select>
	
		&nbsp;&nbsp;Title Search: <input type="text" id="searchtxt" name="searchtxt" />
		<input type="submit" id="Submit" name="Submit" value="Search" />
		<span><?php echo $connError;?></span>
		</form>
	</div>
<?php
}
?>	
</div>
</body>
</html>