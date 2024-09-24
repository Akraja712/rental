<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $video_link = $db->escapeString($_POST['video_link']);


    if (empty($video_link)) {
        $error['video_link'] = " <span class='label label-danger'>Required!</span>";
    }

    // Validate and process the image upload
    if ($_FILES['qr_image']['size'] != 0 && $_FILES['qr_image']['error'] == 0 && !empty($_FILES['qr_image'])) {
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

        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO payment_setting (qr_image,video_link) VALUES ('$upload_image','$video_link')";
        $db->sql($sql);
    } else {
        // Image is not uploaded or empty, insert only the name
        $sql = "INSERT INTO payment_setting (video_link) VALUES ('$video_link')";
        $db->sql($sql);
    }

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_slide'] = "<section class='content-header'>
                                            <span class='label label-success'>Payment Setting Added Successfully</span> </section>";
    } else {
        $error['add_slide'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add Payment Setting <small><a href='payment_setting.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Payment Setting</a></small></h1>

    <?php echo isset($error['add_slide']) ? $error['add_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-8">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_slide_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Video Link</label> <i class="text-danger asterik">*</i><?php echo isset($error['video_link']) ? $error['video_link'] : ''; ?>
                                        <input type="text" class="form-control" name="video_link" id="video_link" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <label for="exampleInputFile">QR Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['qr_image']) ? $error['qr_image'] : ''; ?>
                                        <input type="file" name="qr_image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="qr_image" required/><br>
                                        <img id="blah" src="#" alt="" />
                                    </div>
                                </div>
                            </div> 
                        </div>
        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_mahabharatham_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
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
