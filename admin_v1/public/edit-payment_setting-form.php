<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    return false;
    exit(0);
}

if (isset($_POST['btnUpdate'])) {
    $video_link = $db->escapeString($fn->xss_clean($_POST['video_link']));
    $sql = "UPDATE payment_setting SET video_link='$video_link' WHERE id = '$ID'";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Payment Setting Updated Successfully</span>";
    }

    if ($_FILES['qr_image']['size'] != 0 && $_FILES['qr_image']['error'] == 0 && !empty($_FILES['qr_image'])) {
        //image isn't empty and update the image
        $old_image = $db->escapeString($_POST['old_image']);
        $extension = pathinfo($_FILES["qr_image"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["qr_image"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;
        if (!move_uploaded_file($_FILES["qr_image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "UPDATE payment_setting SET `qr_image`='$upload_image' WHERE `id`='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Payment Setting updated Successfully</span></section>";
        } else {
            $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$data = array();

$sql_query = "SELECT * FROM `payment_setting` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
?>

<section class="content-header">
    <h1>Edit Payment Setting  <small><a href='payment_setting.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Payment Setting </a></small></h1>
    <?php echo isset($error['update_slide']) ? $error['update_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id='edit_slide_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                    <input type="hidden" name="old_image" value="<?php echo isset($res[0]['image']) ? $res[0]['image'] : ''; ?>">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Video Link</label> <i class="text-danger asterik">*</i><?php echo isset($error['video_link']) ? $error['video_link'] : ''; ?>
                                    <input type="text" class="form-control" name="video_link" value="<?php echo $res[0]['video_link']?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="exampleInputFile">QR Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['qr_image']) ? $error['qr_image'] : ''; ?>
                                    <input type="file" name="qr_image" onchange="readURL(this);" accept="image/png, image/jpeg" id="qr_image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['qr_image']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['qr_image']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#edit_slide_form').validate({
        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
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
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
