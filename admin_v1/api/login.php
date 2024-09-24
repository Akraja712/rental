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

$response = array();

if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['password'])) {
    $response['success'] = false;
    $response['message'] = "Password is empty";
    print_r(json_encode($response));
    return false;
}
$mobile = $db->escapeString($_POST['mobile']);
$password = $db->escapeString($_POST['password']);

$sql = "SELECT * FROM users WHERE mobile = '$mobile'";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "Your Mobile Number is not Registered";
    print_r(json_encode($response));
    return false;
}
$sql = "SELECT * FROM users WHERE mobile = '$mobile' AND password = '$password'";
$db->sql($sql);
$user = $db->getResult();
if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "Your Password is incorrect";
    print_r(json_encode($response));
    return false;
}
$blocked = $user[0]['blocked'];

if ($blocked == 1) {
    $response['success'] = false;
    $response['message'] = "Your Account is Blocked";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE mobile = '$mobile'";
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['registered'] = true;
$response['message'] = "Logged In Successfully";
$response['data'] = $res;
echo json_encode($response);

?>