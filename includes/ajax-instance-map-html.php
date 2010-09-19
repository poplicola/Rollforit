<?php
// This document outputs HTML, rather than XML
header('Content-Type: text/html');
header("Cache-Control: no-cache, must-revalidate");
// A date in the past
header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");

require ('config.php');
require ('dbconnect.php');
require ('functions.php');

if(get_magic_quotes_gpc()) {
	$roomID = stripslashes(htmlspecialchars($_GET['roomID'], ENT_QUOTES));
} else {
	$roomID = htmlspecialchars($_GET['roomID'], ENT_QUOTES);
}
?>
<?php
	$username=$_SESSION['username'];
	
	$query = "SELECT map FROM $roomID";
	$result = mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	$roomdata = mysql_fetch_row($result);
	$themap = "uploaded/" . $roomdata[0];
	
	
?>