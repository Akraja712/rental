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
include_once('../includes/functions.php');
$fn = new functions;

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['city'])) {
    $response['success'] = false;
    $response['message'] = "City is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['age'])) {
    $response['success'] = false;
    $response['message'] = "Age is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['state'])) {
    $response['success'] = false;
    $response['message'] = "State is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['email'])) {
    $response['success'] = false;
    $response['message'] = "Email is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);
$name = $db->escapeString($_POST['name']);
$age = $db->escapeString($_POST['age']);
$city = $db->escapeString($_POST['city']);
$mobile = $db->escapeString($_POST['mobile']);
$state = $db->escapeString($_POST['state']);           
$email = $db->escapeString($_POST['email']);


$sql = "SELECT * FROM users WHERE id=" . $user_id;
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "UPDATE users SET name='$name',age='$age',city='$city',state='$state',mobile='$mobile',email='$email' WHERE id=" . $user_id;
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id=" . $user_id;
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "User Updated Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
    return false;
}
else{
    
    $response['success'] = false;
    $response['message'] ="User Not Found";
    print_r(json_encode($response));
    return false;

}

?>