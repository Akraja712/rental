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
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['secrete_code'])) {
    $response['success'] = false;
    $response['message'] = "Secrete Code is Empty";
    print_r(json_encode($response));
    return false;
}

$secrete_code = $db->escapeString($_POST['secrete_code']);
$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$user_res = $db->getResult();
$user_num = $db->numRows($user_res);

if ($user_num == 0) {
    $response['success'] = false;
    $response['message'] = "User ID Not Found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM settings WHERE secrete_code = '$secrete_code'";
$db->sql($sql);
$settings_res = $db->getResult();
$settings_num = $db->numRows($settings_res);

if ($settings_num == 1) {
    $sql = "UPDATE users SET secrete_code='$secrete_code' WHERE id=" . $user_id;
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "Secrete Code Found";
} else {
    $response['success'] = false;
    $response['message'] = "Secrete Code is Invalid";
}

print_r(json_encode($response));
?>