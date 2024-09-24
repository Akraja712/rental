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


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = " User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$datetime = date('Y-m-d H:i:s');
$user_id=$db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num == 1) {
    $veg_wallet = $res[0]['veg_wallet']; 
    $valid = $res[0]['valid']; 
    
    if ($veg_wallet < 1) {
        $response['success'] = false;
        $response['message'] = "Insufficient amount";
        print_r(json_encode($response));
        return false;
    }

    if ($valid == 0) {
        $response['success'] = false;
        $response['message'] = "You are not Valid User";
        print_r(json_encode($response));
        return false;
    }
    

    $tranfer_amount = $veg_wallet;

    $sql = "INSERT INTO transactions (`user_id`,`type`,`datetime`,`amount`) VALUES ($user_id,'transfer','$datetime','$tranfer_amount')";
    $db->sql($sql);
    $sql = "UPDATE users SET veg_wallet = veg_wallet - $tranfer_amount, recharge = recharge + $tranfer_amount , total_recharge = total_recharge + $tranfer_amount,recharge_dialogue = 0  WHERE id=" . $user_id;
    $db->sql($sql);
    
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Transfer Successfully";
    $response['data'] = $res;

} else {
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));
?>
