<?php


include('./includes/variables.php');
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnLogin'])) {

    // get email and password
    $email = $db->escapeString($_POST['email']);
    $password = $db->escapeString($_POST['password']);

    // set time for session timeout
    $currentTime = time() + 25200;
    $expired = 3600;

    // create array variable to handle error
    $error = array();

    // check whether $email is empty or not
    if (empty($email)) {
        $error['email'] = "*Email should be filled.";
    }

    // check whether $password is empty or not
    if (empty($password)) {
        $error['password'] = "*Password should be filled.";
    }

    // if email and password is not empty, check in database
    if (!empty($email) && !empty($password)) {
        if (($email == 'admin' && $password == 'admin123') || ($email == 'subadmin' && $password == 'subadmin123')) {
            if ($email == 'admin') {
                $_SESSION['id'] = '1';
                $_SESSION['role'] ='admin';
                $_SESSION['username'] = 'admin';
                $_SESSION['email'] = 'admin@gmail.com';
                $_SESSION['timeout'] = $currentTime + $expired;
                header("location: home.php");
            } elseif ($email == 'subadmin') {
                $_SESSION['id'] = '2'; 
                $_SESSION['role'] ='subadmin'; 
                $_SESSION['username'] = 'subadmin'; 
                $_SESSION['email'] = 'subadmin@gmail.com';
                $_SESSION['timeout'] = $currentTime + $expired;
                header("location: home.php");
            }
        } else {
            $error['failed'] = "<span class='label label-danger'>Invalid Email or Password!</span>";
        }
    }
}
?>
<?php echo isset($error['update_user']) ? $error['update_user'] : ''; ?>
<div class="col-md-4 col-md-offset-4 " style="margin-top:150px;">
    <!-- general form elements -->
    <div class='row'>
        <div class="col-md-12 text-center">
            <img src="dist/img/money.jpeg" height="110">
            <h3>Money Book-Dashboard</h3>
        </div>
        <div class="box box-info col-md-12">
            <div class="box-header with-border">
                <h3 class="box-title">Admin Login</h3>
                <center>
                    <div class="msg"><?php echo isset($error['failed']) ? $error['failed'] : ''; ?></div>
                </center>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">User Name :</label>
                        <input type="text" name="email" class="form-control" value="<?= defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0 ? 'admin' : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Password :</label>
                        <input type="password" class="form-control" name="password" value="<?= defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0 ? 'admin123' : '' ?>" required>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="btnLogin" class="btn btn-info pull-left">Login</button>
                    </div>
                </div>
            </form>
        </div><!-- /.box -->
    </div>
</div>
<?php include('footer.php'); ?>