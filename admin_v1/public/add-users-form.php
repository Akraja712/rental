<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {


    $name = $db->escapeString($_POST['name']);
    $age = $db->escapeString($_POST['age']);
    $email = $db->escapeString($_POST['email']);
    $city = $db->escapeString($_POST['city']);
    $state = $db->escapeString($_POST['state']);
    $referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
   

   
    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($age)) {
        $error['age'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($email)) {
        $error['email'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($city)) {
        $error['city'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($state)) {
        $error['state'] = " <span class='label label-danger'>Required!</span>";
    }
  
    if ( !empty($name) && !empty($age) && !empty($email)&& !empty($city) && !empty($state)) {
        $sql_query = "INSERT INTO users (name,age,email,city,state,referred_by)VALUES('$name','$age','$email','$city','$state','$referred_by')";
        $db->sql($sql_query);
        $result = $db->getResult();

        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;

         
            $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
            $db->sql($sql);
            $res = $db->getResult();
            $user_id = $res[0]['id'];
            if(empty($referred_by)){
                $refer_code = MAIN_REFER . $user_id;
        
            }
            else{
                $admincode = substr($referred_by, 0, -5);
                $sql = "SELECT refer_code FROM admin WHERE refer_code='$admincode'";
                $db->sql($sql);
                $result = $db->getResult();
                $num = $db->numRows($result);
                if($num>=1){
                    $refer_code = substr($referred_by, 0, -5) . $user_id;
                }
                else{
                    $refer_code = MAIN_REFER . $user_id;
                }
            }
            $sql_query = "UPDATE users SET refer_code='$refer_code' WHERE id =  $user_id";
            $db->sql($sql_query);
        }


        if ($result == 1) {
            
            $error['add_project'] = "<section class='content-header'>
                                            <span class='label label-success'>User Added Successfully</span> </section>";
        } else {
            $error['add_project'] = " <span class='label label-danger'>Failed</span>";
        }
        }
    }

?>
<section class="content-header">
    <h1>Add Users <small><a href='users.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Users</a></small></h1>
    <?php echo isset($error['add_project']) ? $error['add_project'] : ''; ?>
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
                <form name="add_project_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" rows="4" class="form-control" name="name" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                    <input type="email" rows="4" class="form-control" name="email" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Age</label> <i class="text-danger asterik">*</i><?php echo isset($error['age']) ? $error['age'] : ''; ?>
                                    <input type="number" rows="4" class="form-control" name="age" required></textarea>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1"> State</label> <i class="text-danger asterik">*</i><?php echo isset($error['state']) ? $error['state'] : ''; ?>
                                    <input type="text" rows="4" class="form-control" name="state" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">City</label> <i class="text-danger asterik">*</i><?php echo isset($error['city']) ? $error['city'] : ''; ?>
                                    <input type="text" rows="4" class="form-control" name="city" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Referred By</label><i class="text-danger asterik">*</i><?php echo isset($error['referred_by']) ? $error['referred_by'] : ''; ?>
                                    <input type="text" class="form-control" name="referred_by" >
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
    $('#add_project_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            client_name: "required",
            description: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>

<!--code for page clear-->
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>

<?php $db->disconnect(); ?>


