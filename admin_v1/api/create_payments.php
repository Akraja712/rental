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


if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['order_id'])) {
    $response['success'] = false;
    $response['message'] = "Order Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['product_id'])) {
    $response['success'] = false;
    $response['message'] = "Product ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['datetime'])) {
    $response['success'] = false;
    $response['message'] = "Datetime is Empty";
    print_r(json_encode($response));
    return false;
}

$datetime = $db->escapeString($_POST['datetime']);
$order_id = $db->escapeString($_POST['order_id']);
$amount = $db->escapeString($_POST['amount']);
$mobile = $db->escapeString($_POST['mobile']);
$product_id = $db->escapeString($_POST['product_id']);


$product_ids = json_decode($_POST['product_id'], true);

    $sql = "SELECT * FROM `payments` WHERE order_id = '$order_id'";
    $db->sql($sql);
    $res = $db->getResult();

    if ($res) {
        $response['success'] = false;
        $response['message'] = "Order Id already exists";
        print_r(json_encode($response));
        return false;
    }

    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $user = $db->getResult();

    if (empty($user)) {
        $sql = "INSERT INTO `payments` (order_id, product_id, mobile, amount, datetime, claim) VALUES ('$order_id','$product_id','$mobile', '$amount', '$datetime', 0)";
        $db->sql($sql);
        $res = $db->getResult();
    }else{
        $sql = "INSERT INTO `payments` (order_id, product_id, mobile, amount, datetime, claim) VALUES ('$order_id','$product_id','$mobile', '$amount', '$datetime', 1)";
        $db->sql($sql);
        $res = $db->getResult();

        $ID = $user[0]['id'];
        $type = 'recharge';
        $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
        $db->sql($sql);
        $sql_query = "UPDATE users SET recharge = recharge + $amount, total_recharge = total_recharge + $amount WHERE id = $ID";
        $db->sql($sql_query);
    }


$response['success'] = true;
$response['message'] = "Order added Successfully";
print_r(json_encode($response));
