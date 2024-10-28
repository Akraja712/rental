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


if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['age'])) {
    $response['success'] = false;
    $response['message'] = "Age is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['city'])) {
    $response['success'] = false;
    $response['message'] = "City is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['gender'])) {
    $response['success'] = false;
    $response['message'] = "Gender is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['support_lan'])) {
    $response['success'] = false;
    $response['message'] = "Support Languages is Empty";
    print_r(json_encode($response));
    return false;
}
$mobile = $db->escapeString($_POST['mobile']);
$name = $db->escapeString($_POST['name']);
$age = $db->escapeString($_POST['age']);
$city = $db->escapeString($_POST['city']);
$gender = $db->escapeString($_POST['gender']);
$support_lan = $db->escapeString($_POST['support_lan']);
$datetime = date('Y-m-d H:i:s');
$referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
$device_id = $db->escapeString($_POST['device_id']);


$sql = "SELECT id FROM users WHERE device_id='$device_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] ="User Already Registered with this device kindly register with new device";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE mobile = '$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $response['success'] = false;
    $response['message'] = "You are Already Registered";
    print_r(json_encode($response));
}
else{
    do {
        $random_number = mt_rand(10000,99999);
        $sql = "SELECT * FROM users WHERE refer_code = $random_number";
        $db->sql($sql);
        $res = $db->getResult();
        if(!$res) {
            break;
        }
    } while(1);

    $refer_code = $random_number;

    $currentdate = date('Y-m-d');
    $min_withdrawal = MIN_WITHDRAWAL;

    $sql = "INSERT INTO users (`mobile`,`name`,`referred_by`,`account_num`,`holder_name`,`bank`,`branch`,`ifsc`,`refer_code`,`joined_date`,`registered_date`,`min_withdrawal`,`device_id`,`age`,`city`,`gender`,`support_lan`) VALUES ('$mobile','$name','$referred_by','','','','','','$refer_code','$currentdate','$datetime',$min_withdrawal,'$device_id','$age','$city','$gender','$support_lan')";
    $db->sql($sql);
   
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "User Registered Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));


}



?>