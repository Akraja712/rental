<?php
include_once('includes/functions.php');
include_once('includes/custom-functions.php');

$function = new functions;
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $title = $db->escapeString($_POST['title']);
    $description = $db->escapeString($_POST['description']);
    $link = $db->escapeString($_POST['link']);
    $existing_image = $db->escapeString($_POST['existing_image']);
    $error = array();

    if (empty($title)) {
        $error['title'] = "<span class='label label-danger'>Required!</span>";
    }
    if (empty($description)) {
        $error['description'] = "<span class='label label-danger'>Required!</span>";
    }

    // Validate and process the image upload
    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0) {
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $result = $fn->validate_image($_FILES['image']);
        $target_path = 'upload/images/';
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . $filename;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $full_path)) {
            echo '<p class="alert alert-danger">Cannot upload image.</p>';
            return;
        }

        $upload_image = $full_path;
    } else {
        // Use existing image if no new image is uploaded
        $upload_image = $existing_image;
    }

    $datetime = date('Y-m-d H:i:s');
    $sql = "INSERT INTO notifications (title, image, link, description, datetime) VALUES ('$title', '$upload_image', '$link', '$description', '$datetime')";
    $db->sql($sql);

    $result = $db->getResult();
    $result = empty($result) ? 1 : 0;

    if ($result == 1) {
        $error['add_notification'] = "<section class='content-header'><span class='label label-success'>Notification Added Successfully</span></section>";
        header("Location: add-notification.php?success=1");
        exit();
    } else {
        $error['add_notification'] = "<span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add New Notification <small><a href='notifications.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Notifications</a></small></h1>
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<section class='content-header'><span class='label label-success'>Notification Added Successfully</span></section>";
    }
    echo isset($error['add_notification']) ? $error['add_notification'] : '';
    ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <!-- form start -->
                <form id='add-notification-form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="title">Title</label> <i class="text-danger asterik">*</i>
                                <?php echo isset($error['title']) ? $error['title'] : ''; ?>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="description">Description</label><i class="text-danger asterik">*</i>
                                <?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                <textarea rows="3" class="form-control" name="description" required></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="link">Link</label>
                                <input type="text" class="form-control" name="link">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="image">Image</label> <i class="text-danger asterik">*</i>
                                <?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image"/>
                                <input type="hidden" name="existing_image" value="upload/images/applogo.jpeg">
                                <br>
                                <img id="blah" src="upload/images/applogo.jpeg" alt="" style="width: 150px; height: 200px;" />
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onclick="refreshPage()" class="btn btn-warning" value="Clear" />
                    </div>
                </form>
                <div id="result"></div>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#notification_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                $('#result').html(result.message);
                $('#result').show().delay(6000).fadeOut();
                $('#notification_form').each(function() {
                    this.reset();
                });
            }
        });
    });

    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });

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

    function refreshPage() {
        window.location.reload();
    }
</script>
<!-- code for page clear -->

<?php $db->disconnect(); ?>
