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

$response = array(); // Initialize response array

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['txn_id'])) {
    $response['success'] = false;
    $response['message'] = "Transaction ID is empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is empty";
    echo json_encode($response);
    return;
}

if ($_POST['amount'] < 499) {
    $response['success'] = false;
    $response['message'] = "Amount should be minimum 499";
    echo json_encode($response);
    return;
}

if (empty($_POST['key'])) {
    $response['success'] = false;
    $response['message'] = "Key is empty";
    echo json_encode($response);
    return;
}
$datetime = date('Y-m-d H:i:s');
$user_id = $db->escapeString($_POST['user_id']);
$txn_id = $db->escapeString($_POST['txn_id']);
$amount = $db->escapeString($_POST['amount']);
$key = $db->escapeString($_POST['key']);
$p_info = 'Recharge';

$sql = "SELECT name,mobile,email FROM users WHERE id = $user_id";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num == 0){
    $response['success'] = false;
    $response['message'] = "Data Not found";
    print_r(json_encode($response));
    return false;
}
$name = $res[0]['name'];
$email = $res[0]['email'];
$mobile = $res[0]['mobile'];
$redirect_url = 'https://www.google.com/';

// API endpoint
$url = 'https://api.ekqr.in/api/create_order';

// Data to be sent
$data = array(
    'client_txn_id' => $txn_id,
    'amount' => $amount,
    'p_info' => $p_info,
    'txn_date' => $date,
    'customer_name' => $name,
    'customer_email' => $email,
    'customer_mobile' => $mobile,
    'redirect_url' => $redirect_url,
    'key' => $key
);

// Initialize curl session
$ch = curl_init();

// Set curl options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute curl request
$resp = curl_exec($ch);

// Check for errors
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}

// Close curl session
curl_close($ch);

$responseArray = json_decode($resp, true);
$status = $responseArray['status'];
if($status == true){
    $order_id = $responseArray['data']['order_id'];
    $sql = "INSERT INTO recharge_trans (`user_id`,`txn_id`,`order_id`,`amount`,`status`,`datetime`) VALUES ($user_id,'$txn_id','$order_id',$amount,0,'$datetime')";
    $db->sql($sql);
}

echo json_encode($responseArray);
?>