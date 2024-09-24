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



$sql = "SELECT * FROM settings WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$income_status = $result[0]['income_status'];


if ($income_status == 0) {
    $response['success'] = false;
    $response['message'] = "Income Disabled Today";
    print_r(json_encode($response));
    return false;
}



$sql = "SELECT * FROM plan WHERE id = $plan_id ";
$db->sql($sql);
$plan = $db->getResult();
$daily_income = $plan[0]['daily_income'];
if (empty($plan)) {
    $response['success'] = false;
    $response['message'] = "Plans not found";
    print_r(json_encode($response));
    return false;
}


$sql = "SELECT id,referred_by,c_referred_by,d_referred_by,valid_team,valid FROM users WHERE id = $user_id";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$dayOfWeek = date('w');

if ($dayOfWeek == 0 || $dayOfWeek == 7) {
    $response['success'] = false;
    $response['message'] = "Get Income  From Monday to Saturday";
    print_r(json_encode($response));
    return false;
} 

$referred_by = $user[0]['referred_by'];
$c_referred_by = $user[0]['c_referred_by'];
$d_referred_by = $user[0]['d_referred_by'];
$valid_team = $user[0]['valid_team'];
$valid = $user[0]['valid'];


$sql = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = $plan_id ORDER BY claim DESC LIMIT 1";
$db->sql($sql);
$user_plan = $db->getResult();
if (empty($user_plan)) {
    $response['success'] = false;
    $response['message'] = "User Plan not found";
    echo json_encode($response);
    return;
}
$claim = $user_plan[0]['claim'];
$user_plan_id = $user_plan[0]['id'];


if ($claim == 0) {
    $response['success'] = false;
    $response['message'] = "You already claimed this plan";
    print_r(json_encode($response));
    return false;
}



$sql = "UPDATE user_plan SET claim = 0,income = income + $daily_income WHERE id = $user_plan_id";
$db->sql($sql);

$sql = "UPDATE users SET balance = balance + $daily_income, today_income = today_income + $daily_income, total_income = total_income + $daily_income WHERE id = $user_id";
$db->sql($sql);

$sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$daily_income', '$datetime', 'daily_income')";
$db->sql($sql_insert_transaction);

// $sql = "SELECT id FROM users WHERE refer_code = '$referred_by'";
// $db->sql($sql);
// $res= $db->getResult();
// $num = $db->numRows($res);

// if ($num == 1){
//     $refer_id = $res[0]['id'];
//     $level_income = $daily_income * 0.1;
//     $sql = "UPDATE users SET balance = balance + $level_income, today_income = today_income + $level_income, total_income = total_income + $level_income,`team_income` = `team_income` + $level_income WHERE id  = $refer_id";
//     $db->sql($sql);
//     $sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$refer_id', '$level_income', '$datetime', 'level_income')";
//     $db->sql($sql_insert_transaction);
    

// }

// $sql = "SELECT id FROM users WHERE refer_code = '$c_referred_by'";
// $db->sql($sql);
// $res= $db->getResult();
// $num = $db->numRows($res);

// if ($num == 1){
//     $refer_id = $res[0]['id'];
//     $level_income = $daily_income * 0.05;
//     $sql = "UPDATE users SET balance = balance + $level_income, today_income = today_income + $level_income, total_income = total_income + $level_income,`team_income` = `team_income` + $level_income WHERE id  = $refer_id";
//     $db->sql($sql);
//     $sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$refer_id', '$level_income', '$datetime', 'level_income')";
//     $db->sql($sql_insert_transaction);
    

// }

// $sql = "SELECT id FROM users WHERE refer_code = '$d_referred_by'";
// $db->sql($sql);
// $res= $db->getResult();
// $num = $db->numRows($res);

// if ($num == 1){
//     $refer_id = $res[0]['id'];
//     $level_income = $daily_income * 0.02;
//     $sql = "UPDATE users SET balance = balance + $level_income, today_income = today_income + $level_income, total_income = total_income + $level_income,`team_income` = `team_income` + $level_income WHERE id  = $refer_id";
//     $db->sql($sql);
//     $sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$refer_id', '$level_income', '$datetime', 'level_income')";
//     $db->sql($sql_insert_transaction);
    

// }




$response['success'] = true;
$response['message'] = "Income Claimed Successfully";
echo json_encode($response);
?>
