<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');

$db = new Database();
$db->connect();
$currentdate = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
$sql = "SELECT * FROM `withdrawals` WHERE status = 2";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    foreach ($res as $row) {
        $w_id = $row['id'];
        $amount = $row['amount'];
        $user_id = $row['user_id'];
        // $sql = "UPDATE withdrawals SET status=2 WHERE id = $w_id";
        // $db->sql($sql);
        $amount = $amount * 0.25;
        $sql = "UPDATE users SET balance= balance + $amount,total_withdrawal = total_withdrawal - $amount WHERE id = $user_id";
        $db->sql($sql);

        $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', 'deduction_credit')";
        $db->sql($sql);
    }
    $response['success'] = true;
    $response['message'] = "balance added";
    
    print_r(json_encode($response));

}else{
    $response['success'] = false;
    $response['message'] = "No Results Found";
    print_r(json_encode($response));

}




?>