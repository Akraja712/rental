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
if (empty($_POST['plan_id'])) {
    $response['success'] = false;
    $response['message'] = "Plan ID is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$plan_id = $db->escapeString($_POST['plan_id']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$earned_user = 0;

$sql = "SELECT * FROM `user_plan` WHERE income > 300 AND plan_id = 2 AND user_id = $user_id";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $earned_user = 1;

}

$sql = "SELECT * FROM markets WHERE plan_id = '$plan_id' ORDER BY price";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['plan_id'] = $row['plan_id'];
        $temp['name'] = $row['name'];
        $temp['price'] = $row['price'];
        $temp['min_valid_team'] = $row['min_valid_team'];
        // if($earned_user == 1 && $row['id'] == 2){
        //     $temp['price'] = '8';

        // }
        

        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Markets Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Markets Not found";
    print_r(json_encode($response));

}


