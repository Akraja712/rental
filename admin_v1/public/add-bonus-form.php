<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
?>
<?php
$error = array();
$ID = isset($_GET['id']) ? $db->escapeString($_GET['id']) : null;

if (isset($_POST['btnAdd'])) {
        $bonus = $db->escapeString(($_POST['bonus']));
        $error = array();
       
        if (empty($bonus)) {
            $error['bonus'] = " <span class='label label-danger'>Required!</span>";
        }
       
            if (!empty($bonus)) 
            {
                $datetime = date('Y-m-d H:i:s');
                $type = 'bonus';
                $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`)VALUES('$ID','$bonus','$datetime','$type')";
                $db->sql($sql);
                $sql = "UPDATE users SET balance = balance + $bonus, today_income = today_income + $bonus, total_income = total_income + $bonus WHERE id = $ID";
                $db->sql($sql);
                 $result = $db->getResult();
                 if (!empty($result)) {
                     $result = 0;
                 } else {
                     $result = 1;
                 }
     
                 if ($result == 1) {
                    header("Location: add-bonus.php?status=success");
                    exit();
                } else {
                    $error['add_balance'] = "<section class='content-header'>
                                                <span class='label label-danger'>Failed</span>
                                             </section>";
                }
                 
                 }

        }
?>
<section class="content-header">
    <h1>Add Bonus <small><a href='users.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Bonus</a></small></h1>
    <?php 
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<section class='content-header'>
                <span class='label label-success'>Bonus Added Successfully</span>
              </section>";
    } else {
        echo isset($error['add_balance']) ? $error['add_balance'] : ''; 
    }
    ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_balance_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-8'>
                                    <label for="exampleInputEmail1">Bonus</label> <i class="text-danger asterik">*</i><?php echo isset($error['bonus']) ? $error['bonus'] : ''; ?>
                                    <input type="number" class="form-control" name="bonus" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>

<?php $db->disconnect(); ?>