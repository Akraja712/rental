<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
	$ID = $db->escapeString($_GET['id']);
} else {
	// $ID = "";
	return false;
	exit(0);
}
if (isset($_POST['btnEdit'])) {

    $main_content = $db->escapeString(($_POST['main_content']));
	$error = array();

{

$sql_query = "UPDATE explore SET main_content='$main_content' WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Explore Details updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM explore WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "explore.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Explore<small><a href='explore.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Explore</a></small></h1>
	<small><?php echo isset($error['update_jobs']) ? $error['update_jobs'] : ''; ?></small>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-10">

			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
				</div><!-- /.box-header -->
				<!-- form start -->
				<form name="edit_slide_form" method="post" enctype="multipart/form-data">
                <div class="box-body">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                   <label for="main_content">Main Content :</label> <i class="text-danger asterik">*</i><?php echo isset($error['main_content']) ? $error['main_content'] : ''; ?>
                                    <textarea name="main_content" id="main_content" class="form-control" rows="8"><?php echo $res[0]['main_content']; ?></textarea>
                                    <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                                    <script type="text/javascript">
                                       CKEDITOR.replace('main_content');
                                    </script>
                                </div>
                                </div>  
                            </div>
                        <br>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary" name="btnEdit">Update</button>

					</div>
				</form>
			</div><!-- /.box -->
		</div>
	</div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>