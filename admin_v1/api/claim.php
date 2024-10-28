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

if (empty($_POST['plan_id'])) {
    $response['success'] = false;
    $response['message'] = "Plan Id is Empty";
    echo json_encode($response);
    return;
}


$user_id = $db->escapeString($_POST['user_id']);
$plan_id = $db->escapeString($_POST['plan_id']);

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
$sql = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = $plan_id";
$db->sql($sql);
$user_plan = $db->getResult();
if (empty($user_plan)) {
    $response['success'] = false;
    $response['message'] = "User Plan not found";
    echo json_encode($response);
    return;
}

$claim = $user_plan[0]['claim'];

if ($claim == 0) {
    $response['success'] = false;
    $response['message'] = "You already claimed this plan";
    print_r(json_encode($response));
    return false;
}
/*if ($plan_id == 2) {
    $sql_check = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = 6";
    $db->sql($sql_check);
    $check_user = $db->getResult();

    if (empty($check_user)) {
        $response['success'] = false;
        $response['message'] = "This job is disabled";
        print_r(json_encode($response));
        return false;
    }
}   
if ($plan_id == 3) {
    $sql_check = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = 7";
    $db->sql($sql_check);
    $check_user = $db->getResult();

    if (empty($check_user)) {
        $response['success'] = false;
        $response['message'] = "This job is disabled";
        print_r(json_encode($response));
        return false;
    }
}    
if ($plan_id == 4) {
    $sql_check = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = 8";
    $db->sql($sql_check);
    $check_user = $db->getResult();

    if (empty($check_user)) {
        $response['success'] = false;
        $response['message'] = "This job is disabled";
        print_r(json_encode($response));
        return false;
    }
}   
if ($plan_id == 5) {
    $sql_check = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = 9";
    $db->sql($sql_check);
    $check_user = $db->getResult();

    if (empty($check_user)) {
        $response['success'] = false;
        $response['message'] = "This job is disabled";
        print_r(json_encode($response));
        return false;
    }
} 
*/
if ($plan_id == 1) {
    $joined_date = $user_plan[0]['joined_date'];
    $current_date = new DateTime($datetime);
    $plan_joined_date = new DateTime($joined_date);
    $interval = $current_date->diff($plan_joined_date);

    if ($interval->days > 30) {
        $response['success'] = false;
        $response['message'] = "Your plan has ended";
        echo json_encode($response);
        return;
    }
}  

$sql = "SELECT daily_earnings FROM plan WHERE id = $plan_id";
$db->sql($sql);
$plan = $db->getResult();

if (empty($plan)) {
    $response['success'] = false;
    $response['message'] = "Plan not found";
    echo json_encode($response);
    return;
}
$daily_income = $plan[0]['daily_earnings'];


$sql = "UPDATE user_plan SET claim = 0,income = income + $daily_income WHERE plan_id = $plan_id AND user_id = $user_id";
$db->sql($sql);

$sql = "UPDATE users SET earning_wallet = earning_wallet + $daily_income, today_income = today_income + $daily_income, total_income = total_income + $daily_income WHERE id = $user_id";
$db->sql($sql);

$sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$daily_income', '$datetime', 'daily_income')";
$db->sql($sql_insert_transaction);






$response['success'] = true;
$response['message'] = "Work Completed Successfully";
echo json_encode($response);
?>
