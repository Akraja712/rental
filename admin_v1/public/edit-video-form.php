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

	$url = $db->escapeString($_POST['url']);
    $duration = $db->escapeString($_POST['duration']);
    $status = $db->escapeString($_POST['status']);
	$error = array();

		$sql_query = "UPDATE video SET url='$url',status = '$status',duration = '$duration' WHERE id =  $ID";
		$db->sql($sql_query);
		$update_result = $db->getResult();
		if (!empty($update_result)) {
			$update_result = 0;
		} else {
			$update_result = 1;
		}

		// check update result
		if ($update_result == 1) {
			$error['update_languages'] = " <section class='content-header'><span class='label label-success'>Video updated Successfully</span></section>";
		} else {
			$error['update_languages'] = " <span class='label label-danger'>Failed to Update</span>";
		}
	}



// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM video WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "video.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Video<small><a href='video.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Video</a></small></h1>
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
									<label for="exampleInputEmail1">URL</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="url" value="<?php echo $res[0]['url']; ?>">
								</div>
                                <div class="form-group">
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Duration</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="duration"  value="<?php echo $res[0]['duration']; ?>">
								</div>
                             </div>
                          </div>
                      </div>
                      <br>
                      <div class="row">
                      <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Status</label><br>
                                    <input type="checkbox" id="status_button" class="js-switch" <?= isset($res[0]['status']) && $res[0]['status'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="status" name="status" value="<?= isset($res[0]['status']) && $res[0]['status'] == 1 ? 1 : 0 ?>">
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

<script>
    var changeCheckbox = document.querySelector('#status_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#status').val(1);

        } else {
            $('#status').val(0);
            }
    };
</script>

<script>
    // Get the input element
    var durationInput = document.getElementById('duration');

    // Add an event listener for the input event
    durationInput.addEventListener('input', function(event) {
        // Get the current value of the input
        var value = event.target.value;

        // Check if the length of the value is 2
        if (value.length === 2) {
            // If so, add a colon after the second character
            value = value + ':';
        }

        // Set the modified value back to the input
        event.target.value = value;
    });
</script>