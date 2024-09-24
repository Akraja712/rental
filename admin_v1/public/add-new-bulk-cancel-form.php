<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnd'])) {

        $title = $db->escapeString(($_POST['title']));
        $description = $db->escapeString($_POST['description']);
        $link = $db->escapeString($_POST['link']);
        $error = array();
        if (empty($title)) {
            $error['title'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($description)) {
            $error['description'] = " <span class='label label-danger'>Required!</span>";
        }
       
       
       
       if (!empty($title) && !empty($description)) 
       {
            $datetime = date('Y-m-d H:i:s');
            $sql_query = "INSERT INTO notifications (title,description,link,datetime)VALUES('$title','$description','$link','$datetime')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_notification'] = "<section class='content-header'>
                                                <span class='label label-success'>Notification Added Successfully</span> </section>";
            } else {
                $error['add_notification'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">

    <?php echo isset($error['add_notification']) ? $error['add_notification'] : ''; ?>
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
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form id='add_form' method="post" action="public/db-operation.php" enctype="multipart/form-data">
                <input type="hidden" id="cancel_withdrawal" name="cancel_withdrawal" required="" value="1" aria-required="true">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                <label for="">CSV File</label>
                                <input type="file" name="upload_file" class="form-control" accept=".csv" />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">

<div id="result" style="display: none;"></div>
</div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
                <div id="result"></div>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if ($("#add_form").validate().form()) {
            if (confirm('Are you sure?Want to upload')) {
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    beforeSend: function() {
                        $('#submit_btn').html('Please wait..').attr('disabled', 'true');
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $('#result').html(result);
                        $('#result').show().delay(6000).fadeOut();
                        $('#submit_btn').html('Upload').removeAttr('disabled');
                        $('#add_form')[0].reset();
                    }
                });
            }
        }
    });
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>

<?php $db->disconnect(); ?>