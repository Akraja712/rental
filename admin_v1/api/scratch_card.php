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
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');
$fn = new functions;

$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s'); 

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);

if (empty($_POST['scratch_id'])) {

    $sql = "SELECT * FROM scratch_cards WHERE user_id = '$user_id'";
    $db->sql($sql);
    $res= $db->getResult();
    $id = $res[0]['id'];

    $response['success'] = true;
    $response['amount'] = rand(20, 50);
    $response['scratch_id'] = 123;
    $response['message'] = "Scratch Card Available";
    print_r(json_encode($response));
} else {

    $scratch_id = $db->escapeString($_POST['scratch_id']);
    
    $sql = "SELECT * FROM scratch_cards WHERE id = '$scratch_id'";
    $db->sql($sql);
    $res = $db->getResult();

    $amount = $res[0]['amount'];

    $sql = "UPDATE users SET balance = balance + $amount, today_income = today_income + $amount, total_income = total_income + $amount WHERE id = '$user_id'";
    $db->sql($sql);
    
    $sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', 'scratch_card')";
    $db->sql($sql_insert_transaction);
    
    $sql = "UPDATE scratch_cards SET status = 1 WHERE id = '$scratch_id'";
    $db->sql($sql);

    $response['success'] = true;
    $response['message'] = "Scratch Card Claimed Successfully";
    print_r(json_encode($response));
}
?>
