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
$datetime = date('Y-m-d H:i:s');
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    echo json_encode($response);
    return;
}

$mobile = $db->escapeString($_POST['mobile']);

$randomNumber = mt_rand(100000, 999999);
$sql = "SELECT * FROM otp WHERE mobile = '$mobile'";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $sql = "UPDATE otp SET mobile = '$mobile',otp = '$randomNumber',datetime = '$datetime' WHERE mobile = '$mobile'";
    $db->sql($sql);
}
else{
    $sql_insert_transaction = "INSERT INTO otp (`mobile`, `otp`, `datetime`) VALUES ('$mobile', '$randomNumber', '$datetime')";
    $db->sql($sql_insert_transaction);
}

$sql = "SELECT * FROM otp WHERE mobile = '$mobile'";
$db->sql($sql);
$res= $db->getResult();

$response['success'] = true;
$response['message'] = "Successfully Otp Received";
$response['data'] = $res;
echo json_encode($response);
?>
