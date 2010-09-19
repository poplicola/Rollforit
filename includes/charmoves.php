<?php
	require ('config.php');
	require ('dbconnect.php');
	require ('session.php');
	require ('functions.php');
	$xvar=$_GET['x'];
	$yvar=$_GET['y'];
	$roomID=$_SESSION['roomID'];
	$mapsDB="maps_" . $roomID;
	$username=$_SESSION['username'];
	$check = mysql_query("SELECT username FROM " . $mapsDB . " WHERE username= " . $username . "");
	$query="REPLACE INTO " . $mapsDB . " (xvar, yvar, username) VALUES ('".$xvar."', '".$yvar."', '".$username."')";
	$result=mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
?>