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
    <table class="rolls" id="<?php echo $roomID; ?>">
    	<tr>
        	<th class="t">Time</th>
        	<th class="u">User</th>
        	<th class="n">Num</th>
        	<th class="d">Die</th>
        	<th class="o">Outcome</th>
        </tr>
    
<?php echo get_rolls($roomID, $link); ?>

	</table>











