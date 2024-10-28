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
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
$db = new Database();
$db->connect();


$datetime = date('Y-m-d H:i:s');


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    echo json_encode($response);
    return;
}
if (empty($_POST['wallet_type'])) {
    $response['success'] = false;
    $response['message'] = "wallet_type is Empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);
$amount = $db->escapeString($_POST['amount']);
$mobile = $db->escapeString($_POST['mobile']);
$wallet_type = $db->escapeString($_POST['wallet_type']);

if (!is_numeric($amount) || $amount <= 0) {
    $response['success'] = false;
    $response['message'] = "Invalid amount.";
    echo json_encode($response);
    return;
}


$sql = "SELECT * FROM users WHERE id='$user_id'";
$db->sql($sql);
$res = $db->getResult();

if (empty($res)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$recharge = $res[0]['recharge'];
$balance = $res[0]['balance'];
$registered_mobile = $res[0]['mobile']; 

$sql = "SELECT id FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();

if (empty($res)) {
    $response['success'] = false;
    $response['message'] = "mobile not found";
    echo json_encode($response);
    return;
}
$transfer_user_id = $res[0]['id']; 

if ($mobile == $registered_mobile) {
    $response['success'] = false;
    $response['message'] = "Please provide a different mobile number";
    echo json_encode($response);
    return;
}
if($wallet_type == 'recharge'){
    if ($amount <= $recharge) {
        $type = 'friend_recharge';
    
        $sql_query = "UPDATE users SET recharge = recharge - $amount, total_recharge = total_recharge - $amount WHERE id = $user_id";
        $db->sql($sql_query);
        $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`) VALUES ('$user_id', '$amount', '$datetime', 'transfer_recharge')";
        $db->sql($sql);
    
    
        $sql_query = "UPDATE users SET recharge = recharge + $amount, total_recharge = total_recharge + $amount WHERE id  = $transfer_user_id";
        $db->sql($sql_query);
    
        $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`) VALUES ('$transfer_user_id', '$amount', '$datetime', '$type')";
        $db->sql($sql);

        $response['success'] = true;
        $response['message'] = "Amount Transferred Successfully.";
    } else {
        $response['success'] = false;
        $response['message'] = "Your Recharge Balance is Low";
    }
}

if($wallet_type == 'balance'){
    if ($amount <= $balance) {
        $type = 'friend_recharge';
    
        $sql_query = "UPDATE users SET balance = balance - $amount WHERE id = $user_id";
        $db->sql($sql_query);
        $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`) VALUES ('$user_id', '$amount', '$datetime', 'transfer_recharge')";
        $db->sql($sql);
    
    
        $sql_query = "UPDATE users SET recharge = recharge + $amount, total_recharge = total_recharge + $amount WHERE id  = $transfer_user_id";
        $db->sql($sql_query);
    
        $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`) VALUES ('$transfer_user_id', '$amount', '$datetime', '$type')";
        $db->sql($sql);

        $response['success'] = true;
        $response['message'] = "Amount Transferred Successfully.";
    } else {
        $response['success'] = false;
        $response['message'] = "Your Recharge Balance is Low";
    }
}
 
echo json_encode($response);
?>
