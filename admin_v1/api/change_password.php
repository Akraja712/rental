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


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = " User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['password'])) {
    $response['success'] = false;
    $response['message'] = "Password is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['confirm_password'])) {
    $response['success'] = false;
    $response['message'] = "Confirm Password is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id=$db->escapeString($_POST['user_id']);
$password=$db->escapeString($_POST['password']);
$confirm_password = $db->escapeString($_POST['confirm_password']);

if ($password !== $confirm_password) {
    $response['success'] = false;
    $response['message'] = "Password and Confirm Password do not match";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);


if ($num == 1) {
    $sql = "UPDATE users SET password='$password' WHERE id=" . $user_id;
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "User Password Updated Successfully";

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));




?>
