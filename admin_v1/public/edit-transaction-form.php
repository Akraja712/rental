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

	$name = $db->escapeString($_POST['name']);
	$amount = $db->escapeString($_POST['amount']);
	$remarks = $db->escapeString($_POST['remarks']);
	$error = array();

	if (!empty($name) && !empty($type) && !empty($amount) && !empty($remarks)) {
		$sql_query = "UPDATE transactions SET user_id='$name',type='$type',amount='$amount',remarks='$remarks' WHERE id =  $ID";
		$db->sql($sql_query);
		$update_result = $db->getResult();
		if (!empty($update_result)) {
			$update_result = 0;
		} else {
			$update_result = 1;
		}

		// check update result
		if ($update_result == 1) {
			$error['update_transaction'] = " <section class='content-header'><span class='label label-success'>Transaction updated Successfully</span></section>";
		} else {
			$error['update_transaction'] = " <span class='label label-danger'>Failed to Update</span>";
		}
	}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM transactions WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "transactions.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Transaction<small><a href='transactions.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Transactions</a></small></h1>
	<small><?php echo isset($error['update_transaction']) ? $error['update_transaction'] : ''; ?></small>
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
				<form id="edit_user_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="row">
							<div class="form-group">
								<div class="col-md-6">
									<label for="exampleInputEmail1">Name</label><i class="text-danger asterik">*</i>
									<select id='name' name="name" class='form-control' required>
										<option value="none">-- Select --</option>
										<?php
										$sql = "SELECT id,name FROM `users`";
										$db->sql($sql);
										$result = $db->getResult();
										foreach ($result as $value) {
										?>
											<option value='<?= $value['id'] ?>' <?= $value['id'] == $res[0]['user_id'] ? 'selected="selected"' : ''; ?>><?= $value['name'] ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-6">
									<label for="exampleInputEmail1">Amount</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="amount" value="<?php echo $res[0]['amount']; ?>">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="form-group">

								<div class="col-md-6">
									<label for="exampleInputEmail1">Remarks</label><i class="text-danger asterik">*</i>
									<textarea type="text" rows="3" class="form-control" name="remarks"><?php echo $res[0]['remarks']; ?></textarea>
								</div>
							</div>
						</div>
					</div><!-- /.box-body -->

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