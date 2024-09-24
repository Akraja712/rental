<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {


    $name = $db->escapeString($_POST['name']);
    $amount = $db->escapeString($_POST['amount']);
    $remarks = $db->escapeString($_POST['remarks']);


    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($type)) {
        $error['type'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($amount)) {
        $error['amount'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($remarks)) {
        $error['remarks'] = " <span class='label label-danger'>Required!</span>";
    }




    if (!empty($name) && !empty($type) && !empty($amount) && !empty($remarks)) {

        $sql_query = "INSERT INTO transactions (user_id,type,amount,remarks)VALUES('$name','$type','$amount','$remarks')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            $error['add_transaction'] = " <section class='content-header'><span class='label label-success'>Transaction Added Successfully</span></section>";
        } else {
            $error['add_transaction'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add Transaction <small><a href='transactions.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Transactions</a></small></h1>

    <?php echo isset($error['add_transaction']) ? $error['add_transaction'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="add_transaction_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <select id='name' name="name" class='form-control' required>
                                        <option value="">select</option>
                                        <?php
                                        $sql = "SELECT id,name FROM `users`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Amount</label><i class="text-danger asterik">*</i><?php echo isset($error['amount']) ? $error['amount'] : ''; ?>
                                    <input type="number" class="form-control" name="amount" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Remarks</label><i class="text-danger asterik">*</i><?php echo isset($error['remarks']) ? $error['remarks'] : ''; ?>
                                    <textarea type="text" rows="3" class="form-control" name="remarks" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!--code for page clear-->
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>
<?php $db->disconnect(); ?>