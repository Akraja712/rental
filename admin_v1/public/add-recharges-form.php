<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
?>
<?php
if (isset($_POST['btnAdd'])) {
    $user_id = $db->escapeString(($_POST['user_id']));
    $recharge_amount = $db->escapeString(($_POST['recharge_amount']));
    if (empty($user_id)) {
        $error['user_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($recharge_amount)) {
        $error['recharge_amount'] = " <span class='label label-danger'>Required!</span>";
    }
   
    
    // Validate and process the image upload
    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $extension = pathinfo($_FILES["image"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';

        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
        $datetime = date('Y-m-d H:i:s');
        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO recharge (user_id, image,recharge_amount,datetime) VALUES ('$user_id', '$upload_image','$recharge_amount','$datetime')";
        $db->sql($sql);
    } else {
        // Image is not uploaded or empty, insert only the name
        $sql = "INSERT INTO plan_slides (user_id,recharge_amount,datetime) VALUES ('$user_id','$recharge_amount','$datetime')";
        $db->sql($sql);
    }

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_result'] = "<section class='content-header'>
                                            <span class='label label-success'>Recahrge Added Successfully</span> </section>";
    } else {
        $error['add_result'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>
<section class="content-header">
    <h1>Add Recharge <small><a href='recharge.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Recharge</a></small></h1>
    <?php echo isset($error['add_result']) ? $error['add_result'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_project_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                        <label for="">Users</label>
                        <input type="text" id="details" name="user_id" class="form-control">
                       </div>
                        <div class="form-group">
                            <label for="">Recharge Amount</label>
                            <input type="number" class="form-control" name="recharge_amount" id="recharge_amount">
                        </div>
                                <div class="form-group">
                                        <label for="exampleInputFile">Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                        <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
                                        <img id="blah" src="#" alt="" />
                                  
                                </div> 
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="submit_btn" name="btnAdd">Submit</button>
                        <input type="reset" class="btn-warning btn" value="Clear" />
                    </div>
                    <div class="form-group">
                        <div id="result" style="display: none;"></div>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
        <!-- Left col -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Users</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="users" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-trim-on-search="false" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "users-list-<?= date('d-m-y') ?>",
                        "ignoreColumn": ["state"]
                    }'>
                        <thead>
                            <tr>
                                <th data-field="state" data-radio="true"></th>
                                <th data-field="id" data-sortable="true">ID</th>
                                <th data-field="name" data-sortable="true">Name</th>
                                <th data-field="balance" data-sortable="true">Balance</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="separator"> </div>
    </div>
</section>
<script>
  $('#users').on('check.bs.table', function(e, row) {
    $('#details').val(row.id + " | " + row.name + " | " + row.email);
    $('#user_id').val(row.id); // Update 'user_id' with the selected user's id
  });
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block'); // Show the image after setting the source
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>
<?php $db->disconnect(); ?>
