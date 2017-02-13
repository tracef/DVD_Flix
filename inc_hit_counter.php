<?php
include("inc_dvdflix.php");
if($DBConnect !== FALSE)
{
	mysql_query("UPDATE counter SET counter = counter + 1");
	$query = mysql_query("SELECT counter FROM counter");
	$arr = mysql_fetch_array($query);
	$result = $arr[0];
	echo "$result";
}
?>
