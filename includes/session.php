<?php
	// Start the session
	session_start();
	
	// Get the logged-in user's username from the session
	$username = $_SESSION['username'];
	
	// Get the roomID from the session
	$roomID = $_SESSION['roomID'];
	
	// If the roomID is passed as a GET (from a shared link), save it as a session variable so we can redirect the user after logging in
	if ($_GET['id'] && empty($_SESSION['roomID'])) {
		$redirectid = htmlspecialchars($_GET['id'], ENT_QUOTES);
		// Save the roomID to the session
		$_SESSION['roomID'] = $redirectid;
	}
	
	// Check for a valid username on all pages but the index page
	if (empty($_SESSION['username']) && $pageid != 'index.php') {
		// Redirect
		header("Location: index.php");
		// Make sure that code below does not get executed when we redirect
		exit;
	}
	
// May need to sanitize these variables if session variables can be user-submitted via cookie
?>