<?php
$DBName = "flynn2";
$DBConnect = @mysql_connect("127.0.0.1", "flynn2", "learning25");
if ($DBConnect === FALSE)
{
     echo "<p>Connection error: "
               . mysql_error() . "</p>\n";
} 
else 
{
     if (@mysql_select_db($DBName, $DBConnect) === FALSE) {
          echo "<p>Could not select the \"$DBName\" " .
               "database: " . mysql_error($DBConnect) . "</p>\n";
          mysql_close($DBConnect);
          $DBConnect = FALSE;
     }
}
?>
