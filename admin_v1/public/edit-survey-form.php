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

    $plan_id = $db->escapeString(($_POST['plan_id']));
    $question = $db->escapeString(($_POST['question']));
    $correct_option = $db->escapeString(($_POST['correct_option']));
    $option_1 = $db->escapeString(($_POST['option_1']));
    $option_2 = $db->escapeString(($_POST['option_2']));
    $option_3 = $db->escapeString(($_POST['option_3']));
	$video_id = $db->escapeString(($_POST['video_id']));
	$error = array();

		$sql_query = "UPDATE survey SET plan_id='$plan_id',question='$question',correct_option='$correct_option',option_1='$option_1',option_2='$option_2',option_3='$option_3',video_id = '$video_id' WHERE id =  $ID";
		$db->sql($sql_query);
		$update_result = $db->getResult();
		if (!empty($update_result)) {
			$update_result = 0;
		} else {
			$update_result = 1;
		}

		// check update result
		if ($update_result == 1) {
			$error['update_languages'] = " <section class='content-header'><span class='label label-success'>Survey updated Successfully</span></section>";
		} else {
			$error['update_languages'] = " <span class='label label-danger'>Failed to Update</span>";
		}
	}



// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM survey WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "survey.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Survey<small><a href='survey.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Survey</a></small></h1>
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
                                    <label for="exampleInputEmail1">Select Plan</label> <i class="text-danger asterik">*</i>
                                    <select id='plan_id' name="plan_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                  $sql = "SELECT id,name FROM `plan`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['plan_id'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                                  </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Select Video</label> <i class="text-danger asterik">*</i>
                                    <select id='video_id' name="video_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                  $sql = "SELECT id,url FROM `video`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['video_id'] ? 'selected="selected"' : '';?>><?= $value['url'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                                  </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
						    <div class="form-group">
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Question</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="question" value="<?php echo $res[0]['question']; ?>">
								</div>
                                <div class="col-md-6">
                                <label for="exampleInputEmail1">Correct Option</label> <i class="text-danger asterik">*</i>
                                    <select id='correct_option' name="correct_option" class='form-control'>
                                     <option value='option_1' <?php if ($res[0]['correct_option'] == 'option_1') echo 'selected'; ?>>Option 1</option>
                                      <option value='option_2' <?php if ($res[0]['correct_option'] == 'option_2') echo 'selected'; ?>>Option 2</option>
                                      <option value='option_3' <?php if ($res[0]['correct_option'] == 'option_3') echo 'selected'; ?>>Option 3</option>
                                    </select>
								</div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
						    <div class="form-group">
                                <div class="col-md-4">
									<label for="exampleInputEmail1">Option 1</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="option_1" value="<?php echo $res[0]['option_1']; ?>">
								</div>
                                <div class="col-md-4">
									<label for="exampleInputEmail1">Option 2</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="option_2" value="<?php echo $res[0]['option_2']; ?>">
								</div>
                                <div class="col-md-4">
									<label for="exampleInputEmail1">Option 3</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="option_3" value="<?php echo $res[0]['option_3']; ?>">
								</div>
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