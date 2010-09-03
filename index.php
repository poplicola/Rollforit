<?php 
// define page-specific and other variables
$pageid = 'index.php'; 
$pagetype = '';

require ('includes/config.php');
require ('includes/dbconnect.php');
require ('includes/session.php');
require ('includes/functions.php');

$pagename = 'Home';

// Get the 'action', which is a general instruction
$action = $_GET['action'];

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

if ($action == "create") {
	// The user wants to create a new room
	$roomID = create_room();
	$path = get_path() . 'instance.php?id=' . $roomID;;
	// Redirect
	header("Location: $path");
	// Make sure that code below does not get executed when we redirect
	exit;
}

if ($_POST['username'] || $_POST['createroom']) {
	$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
	// Save the username to the session
	$_SESSION['username'] = $username;
	if ($_POST['createroom'] == 'yes') {
		// The user wants to create a new room
		$roomID = create_room();
		// Save the username to the session
		$_SESSION['roomID'] = $roomID;
		$path = get_path() . 'instance.php?id=' . $roomID;;
	}
}

// Get the submitted username and save it as a session variable, then create a room if necessary
if ($_POST['username']) {
	$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
	// Save the username to the session
	$_SESSION['username'] = $username;
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

	<section id="content">
    

		<header id="getrolling">Get rolling with just a username and a room ID.</header>
		<section id="boxes">
			<section id="one">
				<header><h1>1. Create a Name</h1></header>
				<form name="createname" action="" method="post">
					<table width="280px">
						<tbody>
							<tr>
								<td class="c1"><label for="username">Username</label></td>
								<td><input type="text" name="username" id="username" value="<?php echo $username; ?>" /></td>
							</tr>
						</tbody>
					</table>
					<?php
						if (!empty($roomID)) {
							echo "<input type='hidden' value='" . $roomID . "' name='roomID' />";
						}
					?>
					<input type="submit" value="Submit" title="Submit" class="button1" />
				</form>
			</section>
			<section id="two">
				<header><h1>2. Find a Room</h1></header>
				<form name="findroom" action="" method="post">
					<table width="280px">
						<tbody>
							<tr>
								
							</tr>
							<tr>
								<td class="c1"><label for="roomID">Room ID</label></td>
								<td><input type="text" id="roomID" name="roomID" value="<?php echo $_SESSION['roomID']; ?>" /></td>
							</tr>
							<tr>
								<td class="c1" colspan="2" style="padding:0 0 10px;text-align:center;"><strong>Or</strong></td>
							</tr>
							<tr>
								<td></td>
								<td class="c1" style="padding:5px;background:#efefef;"><input type="checkbox" id="createroom" name="createroom" value="yes" /> <label for="roomID">Create a new room </label></td>
							</tr>	
						</tbody>
					</table>
					<?php
						if (!empty($username)) {
							echo "<input type='hidden' value='" . $username . "' name='username' />";
						}
					?>
					<input type="submit" value="Submit" title="Submit" class="button1" />
				</form>
			</section>
			<section id="three">
				<header><h1>Start the Game</h1></header>
					<table width="280px">
						<tbody>
							<tr>
								<td class="c1"><strong>Username</strong></td>
								<td><?php echo $username; ?></td>
							</tr>
							<tr>
								<td class="c1"><strong>Room ID</strong></td>
								<td><?php echo $roomID; ?></td>
							</tr>
							<tr>
								<td colspan="2">
									<form name="startgame" action="
									<?php
										$path = get_path() . 'map.php?id=' . $roomID;;
										echo $path;
									?>
									" method="post">
										<input type="hidden" value="<?php $username; ?>" name="username">
										<input type="hidden" value="<?php $roomID; ?>" name="roomID">
										<input type="submit" value="Log In" class="button2" />
									</form>
								</td>
							</tr>
						</tbody>
					</table>
			</section>
			<div style="clear:both;"></div>
		</section>   
    
	<div style="clear:both;"></div>
	</section><!-- end #content -->
	
</div><!-- end #wrapper -->

<?php include ('includes/footer.php'); ?>