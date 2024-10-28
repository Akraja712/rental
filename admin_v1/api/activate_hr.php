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
include_once('verify-token.php');
$fn = new functions;

$date = date('Y-m-d');

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['hr_id'])) {
    $response['success'] = false;
    $response['message'] = "HR Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$hr_id = $db->escapeString($_POST['hr_id']);

$sql = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$recharge = $user[0]['recharge'];

$sql = "SELECT * FROM hr WHERE id = $hr_id";
$db->sql($sql);
$hr = $db->getResult();

if (empty($hr)) {
    $response['success'] = false;
    $response['message'] = "HR not found";
    print_r(json_encode($response));
    return false;
}

$course_charges = -$hr[0]['course_charges'];
$datetime = date('Y-m-d H:i:s');

$sql_check_activation = "SELECT * FROM hr_jobs WHERE user_id = '$user_id' AND hr_id = '$hr_id'";
$db->sql($sql_check_activation);
$existing_activation = $db->getResult();

if (!empty($existing_activation)) {
    $response['success'] = false;
    $response['message'] = "you already activated with this HR Job";
    print_r(json_encode($response));
    return false;
}

    $sql = "UPDATE users SET recharge = recharge + $course_charges , total_recharge = total_recharge + $course_charges WHERE id = $user_id";
    $db->sql($sql);

    $sql_insert_user_hr = "INSERT INTO hr_jobs (user_id, hr_id, status) VALUES ('$user_id', '$hr_id', 0)";
    $db->sql($sql_insert_user_hr);


    $sql = "INSERT INTO transactions (user_id, amount, datetime, type) VALUES ('$user_id', '$course_charges', '$datetime', 'hr_activated')";
    $db->sql($sql);

    $sql_user = "SELECT * FROM users WHERE id = $user_id";
    $db->sql($sql_user);
    $res_user = $db->getResult();

    $response['success'] = true;
    $response['message'] = "HR Activated successfully";
    $response['data'] = $res_user;


print_r(json_encode($response));

?>
