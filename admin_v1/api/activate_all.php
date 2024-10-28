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

$user_id = $db->escapeString($_POST['user_id']);

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

$sql = "SELECT * FROM plan WHERE id IN (2, 3, 4, 5)";
$db->sql($sql);
$plans = $db->getResult();

if (empty($plans)) {
    $response['success'] = false;
    $response['message'] = "No relevant plans found";
    print_r(json_encode($response));
    return false;
}

$plans_to_activate = [];
foreach ($plans as $plan) {
    $plan_id = $plan['id'];

    $sql_check_activation = "SELECT * FROM user_plan WHERE user_id = '$user_id' AND plan_id = '$plan_id'";
    $db->sql($sql_check_activation);
    $existing_activation = $db->getResult();

    if (empty($existing_activation)) {
        $plans_to_activate[] = $plan;
    }
}

$sql_hr = "SELECT * FROM hr";
$db->sql($sql_hr);
$hr_entries = $db->getResult();

$hr_to_activate = [];
foreach ($hr_entries as $hr) {
    $hr_id = $hr['id'];

    $sql_check_hr_job = "SELECT * FROM hr_jobs WHERE user_id = '$user_id' AND hr_id = '$hr_id'";
    $db->sql($sql_check_hr_job);
    $existing_hr_job = $db->getResult();

    if (empty($existing_hr_job)) {
        $hr_to_activate[] = $hr;
    }
}

if (empty($plans_to_activate) && empty($hr_to_activate)) {
    $response['success'] = false;
    $response['message'] = "You already activated with all available plans and HR jobs";
    print_r(json_encode($response));
    return false;
}

$datetime = date('Y-m-d H:i:s');
foreach ($plans_to_activate as $plan) {
    $plan_id = $plan['id'];
    $price = -$plan['price'];


    $sql = "UPDATE users SET recharge = recharge + $price, total_recharge = total_recharge + $price WHERE id = $user_id";
    $db->sql($sql);

    $sql_insert_user_plan = "INSERT INTO user_plan (user_id, plan_id, joined_date, claim) VALUES ('$user_id', '$plan_id', '$date', 1)";
    $db->sql($sql_insert_user_plan);

    $sql = "INSERT INTO transactions (user_id, amount, datetime, type) VALUES ('$user_id', '$price', '$datetime', 'plan_activate')";
    $db->sql($sql);
}
foreach ($hr_to_activate as $hr) {
    $hr_id = $hr['id'];
    $course_charges = -$hr['course_charges'];

    $sql = "UPDATE users SET recharge = recharge + $course_charges, total_recharge = total_recharge + $course_charges WHERE id = $user_id";
    $db->sql($sql);

    $sql_insert_user_hr = "INSERT INTO hr_jobs (user_id, hr_id, status) VALUES ('$user_id', '$hr_id', 0)";
    $db->sql($sql_insert_user_hr);

    $sql = "INSERT INTO transactions (user_id, amount, datetime, type) VALUES ('$user_id', '$course_charges', '$datetime', 'hr_activated')";
    $db->sql($sql);
}
$sql_user = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql_user);
$res_user = $db->getResult();

$response['success'] = true;
$response['message'] = "Congratulations Your Free Plans have beed activated successfully";
$response['data'] = $res_user;

print_r(json_encode($response));

?>
