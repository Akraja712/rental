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
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['wallet_type'])) {
    $response['success'] = false;
    $response['message'] = "Wallet Type is Empty";
    print_r(json_encode($response));
    return false;
}

$datetime = date('Y-m-d H:i:s');
$user_id=$db->escapeString($_POST['user_id']);
$wallet_type = $db->escapeString($_POST['wallet_type']);


$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num == 1) {
    $earning_wallet = $res[0]['earning_wallet']; 
    $bonus_wallet = $res[0]['bonus_wallet'];

    if($wallet_type == 'earning_wallet'){ 
        if ($earning_wallet < 10) {
            $response['success'] = false;
            $response['message'] = "Minimum 10 rs to add";
            print_r(json_encode($response));
            return false;
        }

        $sql = "INSERT INTO transactions (`user_id`,`type`,`datetime`,`amount`) VALUES ($user_id,'earning_wallet','$datetime',$earning_wallet)";
        $db->sql($sql);
        $sql = "UPDATE users SET earning_wallet= earning_wallet - $earning_wallet,balance = balance + $earning_wallet  WHERE id=" . $user_id;
        $db->sql($sql);
    }
    if($wallet_type == 'bonus_wallet'){
        if ($bonus_wallet < 50) {
            $response['success'] = false;
            $response['message'] = "Minimum 50 rs to add";
            print_r(json_encode($response));
            return false;
        }
        $sql = "INSERT INTO transactions (`user_id`,`type`,`datetime`,`amount`) VALUES ($user_id,'bonus_wallet','$datetime',$bonus_wallet)";
        $db->sql($sql);
        $sql = "UPDATE users SET bonus_wallet= bonus_wallet - $bonus_wallet,balance = balance + $bonus_wallet  WHERE id=" . $user_id;
        $db->sql($sql);
    }


    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();

    $sql_settings = "SELECT min_withdrawal FROM settings WHERE id = 1";
    $db->sql($sql_settings);
    $res_settings = $db->getResult();
    $min_withdrawal = $res_settings[0]['min_withdrawal'];

    $res[0]['min_withdrawal'] = $min_withdrawal;

    $response['success'] = true;
    $response['message'] = "Added to Main Balance Successfully";
    $response['data'] = $res;

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));
?>
