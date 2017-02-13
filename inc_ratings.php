<?php
include("inc_dvdflix.php");
$result = mysql_query("SELECT DISTINCT RATING FROM dvds");
$array = mysql_fetch_array($result);

$length = count($array);
for($i = 0;$i < $length;$i++)
{
	echo "<option>";
	echo $array[$i];
	echo "</option>";
}
?>