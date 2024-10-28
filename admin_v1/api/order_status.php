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

if (empty($_POST['transaction_id'])) {
    $response['success'] = false;
    $response['message'] = "Transaction Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['status'])) {
    $response['success'] = false;
    $response['message'] = "Status is Empty";
    print_r(json_encode($response));
    return false;
}

$transaction_id = $db->escapeString($_POST['transaction_id']);
$status = $db->escapeString($_POST['status']);

$sql = "SELECT * FROM `order` WHERE transaction_id = '" . $transaction_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num == 1) {
    $sql = "UPDATE `order` SET status='$status' WHERE transaction_id=" . $transaction_id;
    $db->sql($sql);
    $sql = "SELECT * FROM `order` WHERE transaction_id = '" . $transaction_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Order Updated Successfully";
    $response['data'] = $res;
} else {
    $response['success'] = false;
    $response['message'] = "Order Not Found";
}

print_r(json_encode($response));
?>
