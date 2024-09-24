<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

$response = array();
$currentdate = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['coupon_num'])) {
    $response['success'] = false;
    $response['message'] = "Coupon Number  is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$coupon_num = $db->escapeString($_POST['coupon_num']);

$sql = "SELECT id FROM users WHERE id = $user_id";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$sql = "SELECT * FROM coupons WHERE coupon_num = '$coupon_num' AND valid_date = '$currentdate'";
$db->sql($sql);
$coupon = $db->getResult();
if (empty($coupon)) {
    $response['success'] = false;
    $response['message'] = "Coupon not found";
    echo json_encode($response);
    return;
}
$coupon_id = $coupon[0]['id'];
$amount = $coupon[0]['amount'];
$min_refers = $coupon[0]['min_refers'];

if($coupon_num == "REFERPLATINUM200"){
    $sql = "SELECT id FROM transactions WHERE amount = 1650 AND type = 'invite_bonus' AND user_id = $user_id AND DATE(datetime) = '$currentdate'";
    $db->sql($sql);
    $user_coupons = $db->getResult();
    $ucnum = $db->numRows($user_coupons);
    if (empty($user_coupons)) {
        $response['success'] = false;
        $response['message'] = "Invalid Coupon";
        echo json_encode($response);
        return;
    }

    $sql = "SELECT id FROM user_coupons WHERE user_id = $user_id AND coupon_id = $coupon_id";
    $db->sql($sql);
    $user_coupons = $db->getResult();
    $num = $db->numRows($user_coupons);
    if ($num >= $ucnum) {
        $response['success'] = false;
        $response['message'] = "Already Claimed";
        echo json_encode($response);
        return;
    }
}

if($coupon_num == "JOINPLATINUM200"){
     $sql = "SELECT id FROM user_plan WHERE  plan_id = 6 AND user_id = $user_id AND DATE(joined_date) = '$currentdate'";
     $db->sql($sql);
     $user_plans = $db->getResult();

    if (empty($user_plans)) {
      $response['success'] = false;
      $response['message'] = "Invalid Coupon";
      echo json_encode($response);
      return;
    }
    $sql = "SELECT id FROM user_coupons WHERE user_id = $user_id AND coupon_id = $coupon_id";
    $db->sql($sql);
    $user_coupons = $db->getResult();
    $num = $db->numRows($user_coupons);
    if ($num >= 1) {
        $response['success'] = false;
        $response['message'] = "Already Claimed";
        echo json_encode($response);
        return;
    }

  
}


$sql = "INSERT INTO `user_coupons` (`coupon_id`,`user_id`, `datetime`) VALUES ($coupon_id,$user_id,'$datetime')";
$db->sql($sql);

$sql = "UPDATE users SET balance = balance + $amount, today_income = today_income + $amount, total_income = total_income + $amount WHERE id = $user_id";
$db->sql($sql);

$sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', 'coupon')";
$db->sql($sql_insert_transaction);

$response['success'] = true;
$response['message'] = "Coupon Claimed Successfully";
echo json_encode($response);

?>