<?php
session_start();
header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once('includes/crud.php');
$db = new Database();
$db->connect();
// start session

// set time for session timeout
$currentTime = time() + 25200;
$expired = 720000;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
   
    
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;


if (isset($_POST['btnDelete'])) {
		
	if (isset($_GET['id'])) {
		$ID = $db->escapeString($_GET['id']);
	} else {
		// $ID = "";
		return false;
		exit(0);
	}

	// delete data from menu table
	$sql_query = "DELETE FROM plan_slides WHERE id =" . $ID;
	$db->sql($sql_query);
	$delete_result = $db->getResult();
	header("location: plan_slides.php");
}

if (isset($_POST['btnNo'])) {
header("location: plan_slides.php");
}
if (isset($_POST['btncancel'])) {
header("location: plan_slides.php");
}
?>

<?php include "header.php"; ?>
<html>

<head>
    <title>Delete Plan Slide | - Dashboard</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
	
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	    <h1>Confirm Action</h1>
		<hr />
		<form method="post">
			<p>Are you sure want to delete this Plan Slide?</p>
			<input type="submit" class="btn btn-primary" value="Delete" name="btnDelete" />
			<input type="submit" class="btn btn-danger" value="Cancel" name="btnNo" />
			<input type="submit" class="btn btn-warning" value="Back" name="btncancel" />
		</form>
    </div><!-- /.content-wrapper -->
</body>

</html>
<?php include "footer.php"; ?>