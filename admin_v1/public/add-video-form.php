<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $url = $db->escapeString(($_POST['url']));
        $duration = $db->escapeString(($_POST['duration']));
        $error = array();
       
        if (empty($url)) {
            $error['url'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($duration)) {
            $error['duration'] = " <span class='label label-danger'>Required!</span>";
        }

       
       
       if (!empty($url) && !empty($duration)) 
       {
           
            $sql_query = "INSERT INTO video (url,duration)VALUES('$url','$duration')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_languages'] = "<section class='content-header'>
                                                <span class='label label-success'>Video Added Successfully</span> </section>";
            } else {
                $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add New Video <small><a href='video.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Video</a></small></h1>

    <?php echo isset($error['add_languages']) ? $error['add_languages'] : ''; ?>
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
                <form url="add-languages-form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">URL</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="url" required>
                                </div>
                                <div class='col-md-6'>
                                  <label for="exampleInputtitle">Duration</label> <i class="text-danger asterisk">*</i>
                                  <input type="text" class="form-control" name="duration" id="duration" placeholder="MM:SS" title="Please enter duration in MM:SS format" required>
                               </div>
                            </div>
                        </div>

                        <br>
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
    $('#add_leave_form').validate({

        ignore: [],
        debug: false,
        rules: {
        reason: "required",
            date: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
        width: 'element',
        placeholder: 'Type in name to search',

    });
    });

    if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

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

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>

<?php $db->disconnect(); ?>
