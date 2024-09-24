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
    $name = $db->escapeString($fn->xss_clean($_POST['name']));
    $link = $db->escapeString($fn->xss_clean($_POST['link']));
    $sql = "UPDATE home_slides SET name='$name',link='$link' WHERE id = '$ID'";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Home slide Updated Successfully</span>";
    }

    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        //image isn't empty and update the image
        $old_image = $db->escapeString($_POST['old_image']);
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
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "UPDATE home_slides SET `image`='$upload_image' WHERE `id`='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Home slide updated Successfully</span></section>";
        } else {
            $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$data = array();

$sql_query = "SELECT * FROM `home_slides` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
?>

<section class="content-header">
    <h1>Edit Home Slide <small><a href='home_slides.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Home Slide</a></small></h1>
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
                                    <label for="exampleInputEmail1"> Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']?>">
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1"> Link</label> <i class="text-danger asterik">*</i><?php echo isset($error['link']) ? $error['link'] : ''; ?>
                                    <input type="text" class="form-control" name="link" value="<?php echo $res[0]['link']?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="exampleInputFile">Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['image']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['image']) ? 'style="display: none;"' : ''; ?> />
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
