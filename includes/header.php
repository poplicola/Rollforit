<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RollFor.It - <?php echo $pagename; ?></title>
<link rel="stylesheet" href="css/style.css" type="text/css" />

<?php if ($pageid = 'instance.php') { ?>

<script src="js/ajax-instance-history-html.js" type="text/javascript"></script>
<script type="text/javascript">
function timedRefresh() {
/* Refresh the list of rolls every X seconds */
	getExternalHTML('includes/ajax-instance-history-html.php?roomID=<?php echo $roomID; ?>', 'tableholder');
	var t=setTimeout("timedRefresh()",5000);
}
window.onload = timedRefresh();
</script>

<?php } ?>
</head>
