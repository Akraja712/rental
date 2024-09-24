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

if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['key'])) {
    $response['success'] = false;
    $response['message'] = "Key is empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);
$txn_id = $db->escapeString($_POST['txn_id']);
$date = $db->escapeString($_POST['date']);
$key = $db->escapeString($_POST['key']);

// API endpoint
$url = 'https://api.ekqr.in/api/check_order_status';

// Data to be sent
$data = array(
    'client_txn_id' => $txn_id,
    'txn_date' => $date,
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
if ($responseArray['status'] === true) {
    $datetime = date('Y-m-d H:i:s');
    $type = 'recharge';
    $data = $responseArray['data'];
    $amount = $data['amount'];
    $status = $data['status'];
    if($status == 'success'){
        $txn_id = $data['client_txn_id'];
        $sql = "SELECT id FROM recharge_trans WHERE txn_id = $txn_id AND status = 0 ";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);

        if ($num == 1){
            $rech_trans_id = $res[0]['id'];
            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', '$type')";
            $db->sql($sql);
        
            $sql_query = "UPDATE users SET recharge = recharge + $amount, total_recharge = total_recharge + $amount WHERE id = '$user_id'";
            $db->sql($sql_query);
            $sql_query = "UPDATE recharge_trans SET status = 1 WHERE id = '$rech_trans_id'";
            $db->sql($sql_query);

        }

    
        $response['success'] = true;
        $response['message'] = "Transaction completed successfully";

    }else{
        $response['success'] = false;
        $response['message'] = "Transaction failed";
    }


} else {
    $response['success'] = false;
    $response['message'] = "Transaction failed";
}

echo json_encode($response);
?>
