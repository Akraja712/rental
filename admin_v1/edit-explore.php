<?php
	// start session
	
	session_start();
	
	// set time for session timeout
	$currentTime = time() + 25200;
	$expired = 720000;
	
	
	// if current time is more than session timeout back to login page
	if($currentTime > $_SESSION['timeout']){
		session_destroy();
		
	}
	
	// destroy previous session timeout and create new one
	unset($_SESSION['timeout']);
	$_SESSION['timeout'] = $currentTime + $expired;
	
?>
<?php include "header.php";?>
<html>
<head>
<title>Edit Explore | - Dashboard</title>
</head>
</body>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php include('public/edit-explore-form.php'); ?>
      </div><!-- /.content-wrapper -->
  </body>
</html>
<?php include "footer.php";?>