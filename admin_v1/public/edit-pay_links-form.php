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

	$links = $db->escapeString($_POST['links']);
    $amount = $db->escapeString($_POST['amount']);
	$error = array();

		$sql_query = "UPDATE pay_links SET links='$links',amount='$amount' WHERE id =  $ID";
		$db->sql($sql_query);
		$update_result = $db->getResult();
		if (!empty($update_result)) {
			$update_result = 0;
		} else {
			$update_result = 1;
		}

		// check update result
		if ($update_result == 1) {
			$error['update_languages'] = " <section class='content-header'><span class='label label-success'>Pay Links updated Successfully</span></section>";
		} else {
			$error['update_languages'] = " <span class='label label-danger'>Failed to Update</span>";
		}
	}



// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM pay_links WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "pay_links.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Pay Links<small><a href='pay_links.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Pay Links</a></small></h1>
	<small><?php echo isset($error['update_languages']) ? $error['update_languages'] : ''; ?></small>
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
				<form id="edit_languages_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
                        <div class="row">
						    <div class="form-group">
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Links</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="links" value="<?php echo $res[0]['links']; ?>">
								</div>
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Amount</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="amount" value="<?php echo $res[0]['amount']; ?>">
								</div>
                            </div>
                        </div>
                    </div>
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