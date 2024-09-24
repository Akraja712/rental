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
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/functions.php');
$fn = new functions;



if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['account_num'])) {
    $response['success'] = false;
    $response['message'] = "Account Number is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['holder_name'])) {
    $response['success'] = false;
    $response['message'] = "Holder Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['bank'])) {
    $response['success'] = false;
    $response['message'] = "Bank is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['branch'])) {
    $response['success'] = false;
    $response['message'] = "Branch is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['ifsc'])) {
    $response['success'] = false;
    $response['message'] = "IFSC is Empty";
    print_r(json_encode($response));
    return false;
}
// if (!preg_match("/^[A-Z]{4}0[A-Z0-9]{6}$/", $_POST['ifsc'])) {
//     $response['success'] = false;
//     $response['message'] = "Invalid IFSC Code";
//     print_r(json_encode($response));
//     return false;
// }

$account_num = $db->escapeString($_POST['account_num']);
$holder_name = $db->escapeString($_POST['holder_name']);
$bank = $db->escapeString($_POST['bank']);
$branch = $db->escapeString($_POST['branch']);
$ifsc = $db->escapeString($_POST['ifsc']);
$user_id = $db->escapeString($_POST['user_id']);

if (!preg_match("/^[A-Z]{4}0[A-Z0-9]{6}$/", $ifsc)) {
    $response['success'] = false;
    $response['message'] = "Invalid IFSC Code";
    print_r(json_encode($response));
    return false;
}


$sql = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num == 1) {
    $sql = "UPDATE `users` SET `account_num` = '$account_num',`holder_name` = '$holder_name',`bank` = '$bank',`branch` = '$branch',`ifsc` = '$ifsc' WHERE `id` = $user_id";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = $user_id";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Bank Details Updated Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "User Not found";
    print_r(json_encode($response));
}


?>
