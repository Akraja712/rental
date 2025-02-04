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

$response = array(); // Initialize response array

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


$datetime = date('Y-m-d H:i:s');
$date = date('Y-m-d');
$user_id = $db->escapeString($_POST['user_id']);
$order_id = $db->escapeString($_POST['order_id']);

$sql = "SELECT * FROM  recharge_orders WHERE order_id = $order_id";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num > 0){
    $response['success'] = false;
    $response['message'] = "Order Id Already Exist";
    print_r(json_encode($response));
    return false;
}


$sql = "INSERT INTO recharge_orders (`user_id`,`order_id`,`status`,`datetime`) VALUES ($user_id,'$order_id',0,'$datetime')";
$db->sql($sql);



$response['success'] = true;
$response['message'] = "Recharge Requested Successfully, Please wait...";
print_r(json_encode($response));
?>