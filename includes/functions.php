<?php
// The functions are kept here

function generateID() {
	// Generate a 10-digit random alphanumeric GUID
	$guid = substr(uniqid(md5(rand())), 0, 10);
	return $guid;
}

function get_users($roomID) {
	// Get a list of users in the room
	
}

function create_room() {
	// Generate a randoom string for use as a table name
	// $roomID = date('ymd') . generateID();
	global $link;
	$roomID = generateID();
	$roomDB = "room_" . $roomID;
	$rollDB = "rolls_" . $roomID;
	$mapsDB = "maps_" . $roomID;
	
	// Construct the SQL to create table
	$query = "CREATE TABLE IF NOT EXISTS `" . $rollDB . "` (`timestamp` INT UNSIGNED, `username` VARCHAR(60), `num` TINYINT UNSIGNED, `die` TINYINT UNSIGNED, `outcome` SMALLINT UNSIGNED, INDEX (username)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	$query2 = "CREATE TABLE IF NOT EXISTS `" . $mapsDB . "` (`username` VARCHAR(60), `xvar` SMALLINT UNSIGNED, `yvar` SMALLINT UNSIGNED, PRIMARY KEY (username), INDEX (username)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	$query3 = "CREATE TABLE IF NOT EXISTS `" . $roomDB . "` (`map` VARCHAR(60), PRIMARY KEY (map), INDEX (map)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	// Execute the query
	$result = mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	$result2 = mysql_query($query2, $link) or die("A MySQL error has occurred.<br />Query: " . $query2 . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	$result3 = mysql_query($query3, $link) or die("A MySQL error has occurred.<br />Query: " . $query3 . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	
	// Save the roomID to the session
	$_SESSION['roomID'] = $roomID;
	
	return $roomID;
}

function process_roll($username, $roomID, $num, $die) {
	// Calculate the outcome of a dice roll and save it to the database
	global $link;
	$rollDB = "rolls_" . $roomID;
	$mapsDB = "maps_" . $roomID;
	for ($i=0; $i<$num; $i++) { 
		$outcome += rand(1, $die);
	}
	
	$query = sprintf("INSERT INTO `$rollDB` (`timestamp`, `username`, `num`, `die`, `outcome`) VALUES ('%d','%s','%d','%d','%d')",
	time(),
	mysql_real_escape_string($username, $link),
	$_POST['num'],
	$_POST['die'],
	$outcome);
	$result = mysql_query($query, $link) or die(mysql_error());
	
	return $outcome;
}

function get_rolls($roomID, $link) {
	// Get a list of rolls in the room
//	$sql = sprintf("SELECT * FROM '%s'",
//	mysql_real_escape_string($roomID, $link));
	$rollDB = "rolls_" . $roomID;
	$mapsDB = "maps_" . $roomID;

	$query = "SELECT * FROM $rollDB ORDER BY timestamp DESC";

	$result = mysql_query($query, $link) or die(mysql_error());
	
	while($row = mysql_fetch_array($result)) {
		echo '</tr>';
		echo '<td class="t">' . date('g:i:s a', $row['timestamp']) . '</td>';
		echo '<td class="u">' . $row['username'] . '</td>';
		echo '<td class="n">' . $row['num'] . '</td>';
		echo '<td class="d">' . $row['die'] . '</td>';
		echo '<td class="o">' . $row['outcome'] . '</td>';
		echo "</tr>\n";
	}
}

function char_tokens($roomID, $username, $torf) {
	global $link;
	$mapsDB = "maps_" . $roomID;
	
	$query2 = "SELECT username FROM " . $mapsDB . " WHERE username= '" . $username . "'";
	$result2 = mysql_query($query2, $link) or die("A MySQL error has occurred.<br />Query: " . $query2 . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	// If user does not exist
	if(mysql_affected_rows()==0) {
		echo "POO";
		$query3 = "INSERT INTO " . $mapsDB . " (xvar, yvar, username) VALUES ('0', '0', '".$username."')";
		$result3 = mysql_query($query3, $link) or die("A MySQL error has occurred.<br />Query: " . $query3 . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	}
	
	$query = "SELECT * FROM " . $mapsDB;
	$result = mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	
	while($row = mysql_fetch_array($result)) {
		$userid=$row['username'];
		$xvar=$row['xvar'];
		$yvar=$row['yvar'];
		if ($userid==$username && $torf==0) {
			echo "<div class='draggable ui-widget-content ui-draggable " . $userid . "' style='left:" . $xvar . "px; top:" . $yvar . "px;z-index:5;'>You</div>";
		} elseif ($userid!=$username && $torf==1) {
			echo "<div class='ui-widget-content ui-draggable " . $userid . "' style='left:" . $xvar . "px; top:" . $yvar . "px;'></div>";
		}
	}
}

function get_path() {
	// Get the path of the folder where the executing script resides, with the trailing slash
	
	// Determine HTTPS or HTTP
	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
	$url .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Convert the trailing backslash (on Windows root) to a forward slash
	$url = str_replace('\\', '/', $url);
	// Determine whether the current location is root by looking for a trailing slash (Windows or Linux)
	if (strlen($url) != strrpos($url, '/') +1) {
		$url .= '/';
	}
	return $url;
}

?>