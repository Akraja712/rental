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
include('../includes/custom-functions.php');
include('../includes/variables.php');
$db = new Database();
$db->connect();
$fn = new custom_functions;

// $response['success'] = false;
// $response['message'] = "DISABLED";
// echo json_encode($response);
// return;

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['order_id'])) {
    $response['success'] = false;
    $response['message'] = "Order ID is empty";
    echo json_encode($response);
    return;
}
$user_id = $db->escapeString($_POST['user_id']);
$order_id = $db->escapeString($_POST['order_id']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$sql = "SELECT product_id,claim,amount FROM payments WHERE order_id = '$order_id'";
$db->sql($sql);
$payments = $db->getResult();
$amount = $payments[0]['amount'];
$claim = $payments[0]['claim'];
if (empty($payments)) {
    $response['success'] = false;
    $response['message'] = "Invalid Order Id";
    echo json_encode($response);
    return;
}

if ($claim == 1) {
    $response['success'] = false;
    $response['message'] = "Already Claimed";
    echo json_encode($response);
    return;
}
$datetime = date('Y-m-d H:i:s');
$type = 'recharge';
$sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`)VALUES('$user_id','$amount','$datetime','$type')";
$db->sql($sql);
$sql_query = "UPDATE users SET recharge = recharge + $amount ,total_recharge = total_recharge + $amount  WHERE id = $user_id";
$db->sql($sql_query);
$sql = "UPDATE payments SET claim = 1 WHERE order_id = '$order_id'";
$db->sql($sql);

$response['success'] = true;
$response['message'] = "Your Amount Added to your wallet";
print_r(json_encode($response));
?>