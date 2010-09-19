<?php 
// define page-specific and other variables
$pageid = 'map.php'; 
$pagetype = ''; 

require ('includes/config.php');
require ('includes/dbconnect.php');
require ('includes/session.php');
require ('includes/functions.php');

if ($action == "logout") {
	// Unset all of the session variables.
	$_SESSION = array();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	
	// Finally, destroy the session.
	session_destroy();
}

if ($action == "leaveroom") {
	// The user wants to leave the room
	unset($_SESSION['roomID']);
}

$pagename = 'Room ' . $roomID;
$path = get_path() . 'instance.php?id=' . $_SESSION['roomID'];

$filename = $_FILES['mapload'];

if (isset($filename)) {
	$ext = strtolower(strrchr($filename['name'], '.'));
	$newfilename = $roomID . $ext;
	$themap = $newfilename;
	$_SESSION['themap']=$themap;
	// Process map file
	$target = "uploaded/";
	$target = $target . $newfilename;
	$ok=1;
	$mapload_size=$_FILES['mapload']['size'];
	$mapload_type=$_FILES['mapload']['type'];
	//This is our size condition
	if ($mapload_size > 6291456) { 
		echo "Your file is too large.<br>";
		$ok=0;
	}
	//This is our limit file type condition
	if ($mapload_type !="image/jpeg" && $mapload_type !="image/png" && $maplaod_type !="image/gif") {
		echo "Incorrect File Type.<br>";
		$ok=0;
	}
	//Here we check that $ok was not set to 0 by an error
	if ($ok==0) {
		Echo "Sorry your file was not uploaded.";
	} else {
		if(move_uploaded_file($_FILES['mapload']['tmp_name'], $target)) {
			echo "The file ". basename( $_FILES['mapload']['name']). " has been uploaded";
			$map = $_FILES['mapload']['name'];
		} else {
			echo "Sorry, there was a problem uploading your file.";
		}
	}
}

?>
<?php include ('includes/header.php'); ?>

<body>

<div id="wrapper" class="clearfix">

	<section id="header">
		<img src="images/rollforit.png" alt="Rollfor.it" class="logo" />
	</section>
	<!-- end #header -->

<?php include ('includes/pagenav.php'); ?>

	<section id="content2">
		<header id="getrolling">
			<?php
				if (empty($themap) || $ok==0) {
					echo "Upload a map.";
				} else {
					echo "<a href=" . $path . ">Continue</a>";
				}
			?>
		</header>
		<form enctype="multipart/form-data" action="map.php" method="post">
			<input type="file" name="mapload" />
			<input type="submit" value="Submit">
		</form>
		<section id="map">
			<?php
				if (isset($themap) && $ok!=0) {
					echo "<img src=uploaded/" . $themap . " alt='the map' />";
					$query = "REPLACE INTO " . $roomID . " (map) VALUES ('" . $themap . "')";
					$result = mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
				}
			?>
		</section>
		<div style="clear:both;"></div>
	</section><!-- end #content -->
	
	
</div><!-- end #wrapper -->

<?php include ('includes/footer.php'); ?>