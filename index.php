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
	}
}

if (isset($_POST['username']) && isset($_POST['roomID']) && !isset($_POST['createroom'])) {
	$_SESSION['roomID']=$_POST['roomID'];
	$path = get_path() . 'instance.php?id=' . $_POST['roomID'];
	// Redirect	
	header("Location: $path");
	// Make sure that code below does not get executed when we redirect
	exit;
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
			<form name="startgame" 
			<?php
				// If a new room has been created, the page will refresh and $roomID will contain the room ID.  If not, we can assume the user is entering a pre-existing room, in which case the form will direct them to instance.php
				if(isset($roomID) || $_POST['createroom']=='yes') {
					$action = "action='" . get_path() . "map.php?id=" . $roomID . "' method='post'";
					echo $action;
				} else {
					echo "action='" . $_SERVER['PHP_SELF'] . "' method='post'";
				}
			?>>
			<section id="one">
				<header><h1>1. Create a Name</h1></header>
					<table width="280px">
						<tbody>
							<tr>
								<td class="c1"><label for="username">Username</label></td>
								<!-- Passes username to hidden input variable, and echoes it in div id=3 -->
								<td><input type="text" name="username" id="username" value="<?php echo $username; ?>" onkeyup="document.getElementById('dynamicusername').innerHTML=$('#username').val(); if ($('#username').val() == '') {document.getElementById('dynamicusername').innerHTML = '--';}" /></td>
							</tr>
						</tbody>
					</table>
					<?php
						if (!empty($roomID)) {
							echo "<input type='hidden' value='" . $roomID . "' name='roomID' />";
						}
					?>
			</section>
			
			<section id="two">
				<header><h1>2. Find a Room</h1></header>
					<table width="280px">
						<tbody>
							<tr>
								<td class="c1"><label for="roomID">Room ID</label></td>
								<!-- Passes room ID to hidden input variable, and echoes it in div id=3 -->
								<td><input type="text" id="roomID" name="roomID" value="<?php echo $_SESSION['roomID']; ?>" onkeyup="document.getElementById('dynamicroomname').innerHTML=$('#roomID').val(); if ($('#roomID').val() == '') {document.getElementById('dynamicroomname').innerHTML = '--';}" /></td>
							</tr>
							<tr>
								<td class="c1" colspan="2" style="padding:0 0 10px;text-align:center;"><strong>Or</strong></td>
							</tr>
							<tr>
								<td><!-- Placeholder to push "create a new room" to the right --></td>
								<td class="c1" style="padding:5px;background:#efefef;"><input type="checkbox" id="createroom" name="createroom" value="yes" onclick="this.form.submit()" /> <label for="roomID">Create a new room</label></td>
							</tr>	
						</tbody>
					</table>
					<?php
						if (!empty($username)) {
							echo "<input type='hidden' value='" . $username . "' name='username' />";
						}
					?>
			</section>
			
			<section id="three">
				<header><h1>Start the Game</h1></header>
					<table width="280px">
						<tbody>
							<tr>
								<td class="c1"><strong>Username</strong></td>
								<td id="dynamicusername">
									<?php if (isset($username)) {
										echo $username;
									} else {
										echo "--";
									} ?>
								</td>
							</tr>
							<tr>
								<td class="c1"><strong>Room ID</strong></td>
								<td id="dynamicroomname">
									<?php if (isset($roomID)) {
										echo $roomID;
									} else {
										echo "--";
									}?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="submit" value="Log In" class="button2" />
								</td>
							</tr>
						</tbody>
					</table>
			</section>
			</form>
			<div style="clear:both;"></div><!-- fix to force div to contain the elements inside -->
		</section><!-- end #boxes -->
    
	<div style="clear:both;"></div>
	</section><!-- end #content -->
	
</div><!-- end #wrapper -->

<?php include ('includes/footer.php'); ?>