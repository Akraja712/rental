<?php session_start();

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$function = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;
// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}
// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}
$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
$yes_dt = date("Y-m-d 00:00:00", strtotime("yesterday"));
$yesterday = date("Y-m-d", strtotime("yesterday"));
$yes_dt_ = $yesterday . " " . date("H:i:s");
// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;
$function = new custom_functions;
include "header.php";
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Money Book- Dashboard</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Home</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                </li>
            </ol>
        </section>
        <?php
                if ($_SESSION['role'] == 'admin') {
                  ?>
        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php
                            $sql = "SELECT id FROM users ";
                            $db->sql($sql);
                            $res = $db->getResult();
                            $num = $db->numRows($res);
                            echo $num;
                             ?></h3>
                            <p>Users</p>
                        </div>
                        <div class="icon"><i class="fa fa-users"></i></div>
                        <a href="users.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                        <?php
                          $currentdate = date("Y-m-d"); // Get the current date
                          $sql = "SELECT COUNT(id) AS total FROM users WHERE DATE(registered_datetime) = '$currentdate'";
                          $db->sql($sql);
                          $res = $db->getResult();
                          $num = $res[0]['total']; // Fetch the count from the result
                           ?>
                          <h3><?php echo $num; ?></h3>
                          <p>Today Registration </p>
                          </div>
                        
                        <a href="users.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                        <h3><?php
                            $sql = "SELECT SUM(amount) AS amount FROM withdrawals WHERE  status = 0 ";
                            $db->sql($sql);
                            $res = $db->getResult();
                            $totalamount = $res[0]['amount'];
                            echo "₹".$totalamount;
                             ?></h3>
                            <p>Unpaid Withdrawals</p>
                        </div>
                        
                        <a href="withdrawals.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?php
                             $sql = "SELECT COUNT(id) AS count  FROM users WHERE today_income > 0 AND valid = 1";
                             $db->sql($sql);
                             $res = $db->getResult();
                             $count = $res[0]['count'];
                             echo $count;
                              ?></h3>
                            <p>Active Valid Users</p>
                        </div>
                       
                        <a href="users.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-primary">
                    <div class="inner">
                            <h3><?php
                             $sql = "SELECT COUNT(id) AS count  FROM recharge_orders WHERE status = 0";
                             $db->sql($sql);
                             $res = $db->getResult();
                             $count = $res[0]['count'];
                             echo $count;
                              ?></h3>
                            <p>Pending Recharge</p>
                        </div>
                        
                        <a href="recharge_orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                        <h3><?php
                        $currentdate = date("Y-m-d");
                            $sql = "SELECT SUM(amount) AS amount  FROM  transactions  WHERE type = 'recharge_orders' AND DATE(datetime) = '$currentdate'";
                            $db->sql($sql);
                            $res = $db->getResult();
                            $totalamount = $res[0]['amount'];
                            echo "₹".$totalamount;
                             ?></h3>
                            <p>Today Recharge Amount</p>
                        </div>
                        
                        <a href="transactions.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
             </div>
        </section>
        <?php } ?>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>