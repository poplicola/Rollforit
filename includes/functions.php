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
	
	// Construct the SQL to create table
	$query = "CREATE TABLE IF NOT EXISTS `" . $roomID . "` (`timestamp` INT UNSIGNED, `username` VARCHAR(60), `num` TINYINT UNSIGNED, `die` TINYINT UNSIGNED, `outcome` SMALLINT UNSIGNED, INDEX (username)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	// Execute the query
	$result = mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
	
	// Save the roomID to the session
	$_SESSION['roomID'] = $roomID;
	
	return $roomID;
}

function process_roll($username, $roomID, $num, $die) {
	// Calculate the outcome of a dice roll and save it to the database
	global $link;
	for ($i=0; $i<$num; $i++) { 
		$outcome += rand(1, $die);
	}
	
	$query = sprintf("INSERT INTO `$roomID` (`timestamp`, `username`, `num`, `die`, `outcome`) VALUES ('%d','%s','%d','%d','%d')",
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

	$query = "SELECT * FROM $roomID ORDER BY timestamp DESC";

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


function deleteattachment($id) {
	// Delete an attachment (screenshot, etc.) from the database, and unlink() the file
	global $link;
	global $target_path;
	$query = sprintf("SELECT * FROM attachments WHERE servername = '%s'",
	mysql_real_escape_string($id, $link));
	$result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($result) >= 1) {
		// Delete the record from the database
		$query = sprintf("DELETE FROM attachments WHERE servername = '%s' LIMIT 1",
		mysql_real_escape_string($id, $link));
		$result = mysql_query($query) or die(mysql_error());
		// Delete the file from the server
		$thefile = $target_path . $id;
		if (is_file("$thefile")) {
			unlink("$thefile");
		}
		$message = "filedeleted";
	} else {
		$message = "filenotfound";
	}
	return $message;
}
?>