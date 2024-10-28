<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $plan_id = $db->escapeString(($_POST['plan_id']));
        $question = $db->escapeString(($_POST['question']));
        $correct_option = $db->escapeString(($_POST['correct_option']));
        $option_1 = $db->escapeString(($_POST['option_1']));
        $option_2 = $db->escapeString(($_POST['option_2']));
        $option_3 = $db->escapeString(($_POST['option_3']));
        $video_id = $db->escapeString(($_POST['video_id']));
        $error = array();
       
      
        if (empty($plan_id)) {
            $error['plan_id'] = " <span class='label label-danger'>Required!</span>";
        }
       
       
       if (!empty($plan_id) && !empty($question)&& !empty($correct_option) && !empty($option_1) && !empty($option_2) && !empty($option_3) && !empty($video_id)) 
       {
           
            $sql_query = "INSERT INTO survey (plan_id,question,correct_option,option_1,option_2,option_3,video_id)VALUES('$plan_id','$question','$correct_option','$option_1','$option_2','$option_3','$video_id')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_languages'] = "<section class='content-header'>
                                                <span class='label label-success'>Survey Added Successfully</span> </section>";
            } else {
                $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add New Survey<small><a href='survey.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Survey</a></small></h1>

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
                              <div class="col-md-6">
                                    <label for="exampleInputEmail1">Select Plan</label><i class="text-danger asterik">*</i><?php echo isset($error['plan_id']) ? $error['plan_id'] : ''; ?>
                                    <select id='plan_id' name="plan_id" class='form-control' required>
                                        <option value="">select</option>
                                        <?php
                                        $sql = "SELECT id,name FROM `plan`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            
                              <div class="col-md-6">
                                    <label for="exampleInputEmail1">Select Video</label><i class="text-danger asterik">*</i><?php echo isset($error['video_id']) ? $error['video_id'] : ''; ?>
                                    <select id='video_id' name="video_id" class='form-control' required>
                                        <option value="">select</option>
                                        <?php
                                        $sql = "SELECT id,url FROM `video`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>'><?= $value['url'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                              
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Question</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="question" required>
                                </div>
                                <div class='col-md-6'>
                                <label for="exampleInputEmail1">Correct Option</label> <i class="text-danger asterik">*</i><?php echo isset($error['correct_option']) ? $error['correct_option'] : ''; ?>
                                    <select id='correct_option' name="correct_option" class='form-control'>
                                    <option value=''>--select--</option>
                                    <option value='option_1'>Option 1</option>
                                      <option value='option_2'>Option 2</option>
                                      <option value='option_3'>Option 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Option 1</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="option_1" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Option 2</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="option_2" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputtitle">Option 3</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="option_3" required>
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

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>

<?php $db->disconnect(); ?>
