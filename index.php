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

// Get the submitted username and save it as a session variable, then create a room if necessary
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
		// Redirect
		header("Location: $path");
		// Make sure that code below does not get executed when we redirect
		exit;
	}
}

if (isset($_SESSION['username']) && isset($_SESSION['roomID'])) {
	// If we have a username and a roomID, send the user to the room
	// Ex: a user has clicked on a link to a room and has logged in, redirect to the room
	$path = get_path() . 'instance.php?id=' . $_SESSION['roomID'];
	// Redirect
	header("Location: $path");
	// Make sure that code below does not get executed when we redirect
	exit;
}?>

<?php include ('includes/header.php'); ?>

<body>

<div id="wrapper" class="clearfix">

	<div id="header">
	
		<h1>Please log in</h1>
        <p>You can start rolling dice with just a username and a room ID.</p>

	</div><!-- end #header -->

<?php include ('includes/pagenav.php'); ?>

	<div id="content">
    
<?php if (empty($_SESSION['username'])) { ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div>
        <label for="username">Enter a username</label>
	   	<input type="text" id="username" name="username" value="" />
    </div>
    <div>
        <label for="roomID">Create a new room ID</label>
    	<input type="checkbox" id="createroom" name="createroom" value="yes" />
    </div>
    <div>
        <label for="roomID">Enter a room ID</label>
    	<input type="text" id="roomID" name="roomID" value="<?php echo $_SESSION['roomID']; ?>" />
    </div>
    <div>
        <input type="submit" class="button" id="submit" title="Submit" value="Submit" />
    </div>
    </form>
    
<?php } ?>    
    
<?php if (isset($_SESSION['username']) && !isset($_SESSION['roomID'])) {
	// A user is logged in, but isn't in a room yet
	
	// Assign the variable $username from the session
	$username = $_SESSION['username'];
?>

	<h2>Hello, <?php echo $username; ?></h2>
    
    <p>You aren't currently connected to a room.  You can choose to:</p>

    <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=create">Create a room</a></p>
	
    <p>Join a room if you have an room ID</p>
    <form action="instance.php" method="get">
    <div>
    	<input type="text" id="roomID" name="roomID" value="" />
    <div>
    </div>
        <input type="submit" class="button" value="Submit" />
    </div>
    </form>
	
<?php } ?>
   
	</div><!-- end #content -->
	
</div><!-- end #wrapper -->

<?php include ('includes/footer.php'); ?>