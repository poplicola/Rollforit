<?php 
// define page-specific and other variables
$pageid = 'instance.php'; 
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

// Get the submitted dice roll and save it to the database
if ($_POST['num'] && $_POST['die']) {
	$num = $_POST['num'];
	$die = $_POST['die'];
	$outcome = process_roll($username, $roomID, $num, $die);
}

?>
<?php include ('includes/header.php'); ?>
<body>

<div id="wrapper" class="clearfix">

	<?php
		$username=$_SESSION['username'];
		
		$query = "SELECT map FROM $roomID";
		$result = mysql_query($query, $link) or die("A MySQL error has occurred.<br />Query: " . $query . "<br />Error: (" . mysql_errno() . ") " . mysql_error());
		$roomdata = mysql_fetch_row($result);
		$themap = "uploaded/" . $roomdata[0];
	?>

	<div id="header">
	
		<h1>Rollfor.it Room ID: <?php echo $roomID; ?></h1>
        <p>Share this page's URL with your friends to invite them to roll with you.</p>

	</div><!-- end #header -->

<?php include ('includes/pagenav.php'); ?>

	<div id="content" class="clearfix">
		<section id="map">
			<?php
				echo "<img src=" . $themap . " alt='the map' />";
				char_tokens($roomID, $username, $link);
			?>
		</section>
		
		<section id="rolldata">
	        <section id="userdice">
	        <h2><?php echo $username; ?></h2>
	        <form action="instance.php<?php echo '?id=' . $roomID; ?>" method="post">
	        <div>
	            <select id="num" name="num">
	                <option value="1">1</option>
	                <option value="2">2</option>
	                <option value="3">3</option>
	            </select>
	            d
	            <select id="die" name="die">
	                <option value="4">4</option>
	                <option value="6">6</option>
	                <option value="8">8</option>
	                <option value="10">10</option>
	                <option value="12">12</option>
	                <option value="20">20</option>
	                <option value="100">100</option>
	            </select>
	            <input class="button" id="submit" title="Roll" type="submit" value="Roll" />
	        </div>
	        </form>
	        <p><?php if ($outcome) echo 'You rolled a ' . $num . 'd' . $die . ' with an outcome of <strong>' . $outcome . '</strong>.'; ?></p>
	        <p><a href="<?php echo 'instance.php?id=' . $roomID; ?>">Refresh</a></p>
	        </section>

			<div id="dicehistory"><?php // This area is populated via Ajax ?></div>
		</section>
	<div style="clear:both;"></div>
	</div><!-- end #content -->
</div><!-- end #wrapper -->

<?php include ('includes/footer.php'); ?>