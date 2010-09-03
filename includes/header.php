<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<title>RollFor.It - <?php echo $pagename; ?></title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<meta name="description" content="Rollfor.it is an online application that allows tabletop RPG players to meet online when they can't in person to play their game.">

<script src="js/modernizr-1.5.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script>
window.onload = function () {
// focus on the input field for easy access...
var input = document.getElementById ('username');
input.focus ();
};

$(document).ready(function(){
	$("input").focus(function() {
		$(this).closest("#one,#two,#three").addClass("focus")
	});
	$("input").blur(function() {
		$(this).closest("#one,#two,#three").removeClass("focus")
	});
});
</script>

<?php if ($pageid == 'map.php') { ?>

<?php } ?>

<?php if ($pageid == 'instance.php') { ?>

<script src="js/ajax-instance-history-html.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script>
function timedRefresh() {
/* Refresh the list of rolls every X seconds */
	getExternalHTML('includes/ajax-instance-history-html.php?roomID=<?php echo $roomID; ?>', 'dicehistory');
	var t=setTimeout("timedRefresh()",5000);
}
window.onload = timedRefresh();
</script>

<script>       
	  $(function() {
	    $("#draggable").draggable({
	            drag: function(event,ui){ 
	            $(this).html("Top: "+ ui.position.top + "<br />Left: "+ ui.position.left);
	        }
	    });
	});
</script>

<?php } ?>
</head>
