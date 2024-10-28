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
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['rental_id'])) {
    $response['success'] = false;
    $response['message'] = "Rental Id is Empty";
    echo json_encode($response);
    return;
}


$user_id = $db->escapeString($_POST['user_id']);
$rental_id = $db->escapeString($_POST['rental_id']);

$sql = "SELECT id,referred_by,c_referred_by,d_referred_by FROM users WHERE id = $user_id";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$sql = "SELECT * FROM settings WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$income_status = $result[0]['income_status'];


if ($income_status == 0) {
    $response['success'] = false;
    $response['message'] = "Today Holiday";
    print_r(json_encode($response));
    return false;
}


$dayOfWeek = date('w');

if ($dayOfWeek == 0 || $dayOfWeek == 7) {
    $response['success'] = false;
    $response['message'] = "Market Open time From Monday to Saturday";
    print_r(json_encode($response));
    return false;
} 

$referred_by = $user[0]['referred_by'];
$c_referred_by = $user[0]['c_referred_by'];
$d_referred_by = $user[0]['d_referred_by'];
$sql = "SELECT * FROM user_rental WHERE user_id = $user_id AND rental_id = $rental_id";
$db->sql($sql);
$user_rental = $db->getResult();
if (empty($user_rental)) {
    $response['success'] = false;
    $response['message'] = "User Rental not found";
    echo json_encode($response);
    return;
}

$claim = $user_rental[0]['claim'];

if ($claim == 0) {
    $response['success'] = false;
    $response['message'] = "You already claimed this rental";
    print_r(json_encode($response));
    return false;
}

if ($rental_id == 1) {
    $joined_date = $user_rental[0]['joined_date'];
    $current_date = new DateTime($datetime);
    $rental_joined_date = new DateTime($joined_date);
    $interval = $current_date->diff($rental_joined_date);

    $days_passed = $interval->days;

    if ($interval->days > 30) {
        $response['success'] = false;
        $response['message'] = "Your rental has ended";
        echo json_encode($response);
        return;
    }
} 
 
if ($days_passed >= 30) {

$sql = "SELECT daily_earnings FROM rental WHERE id = $rental_id";
$db->sql($sql);
$rental = $db->getResult();

if (empty($rental)) {
    $response['success'] = false;
    $response['message'] = "rental not found";
    echo json_encode($response);
    return;
}
$daily_income = $rental[0]['daily_earnings'];

$sql = "UPDATE user_rental SET claim = 0,income = income + $daily_income WHERE rental_id = $rental_id AND user_id = $user_id";
$db->sql($sql);

$sql = "UPDATE users SET earning_wallet = earning_wallet + $daily_income, today_income = today_income + $daily_income, total_income = total_income + $daily_income WHERE id = $user_id";
$db->sql($sql);

$sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$daily_income', '$datetime', 'daily_income')";
$db->sql($sql_insert_transaction);


$response['success'] = true;
$response['message'] = "Work Completed Successfully";
echo json_encode($response);
}
else {
    $remaining_days = 30 - $days_passed;
    $response['success'] = true;
    $response['message'] = "$remaining_days days remaining to claim your rental earning";
    echo json_encode($response);
    return;
}
?>
