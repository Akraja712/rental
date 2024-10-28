<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
include_once('../includes/functions.php');
$fn = new functions;
date_default_timezone_set('Asia/Kolkata');

if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile number is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['password'])) {
    $response['success'] = false;
    $response['message'] = "Password is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['confirm_password'])) {
    $response['success'] = false;
    $response['message'] = "Confirm Password is Empty";
    print_r(json_encode($response));
    return false;
}

$mobile = $db->escapeString($_POST['mobile']);
$password = $db->escapeString($_POST['password']);
$confirm_password = $db->escapeString($_POST['confirm_password']);

if ($password !== $confirm_password) {
    $response['success'] = false;
    $response['message'] = "Password and Confirm Password do not match";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $sql_query = "UPDATE users SET password='$password' WHERE mobile = '$mobile'";
    $db->sql($sql_query);
    $response['success'] = true;
    $response['message'] = "Password Changed Successfully";
    print_r(json_encode($response));
    return false;
} else {
    $response['success'] = false;
    $response['message'] = "Mobile Number Not Registered";
    print_r(json_encode($response));
}
?>