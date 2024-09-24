<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');

$db = new Database();
$db->connect();
$currentdate = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
//$sql = "SELECT u.id AS user_id,u.worked_days AS worked_days,u.project_type AS project_type,u.ads_time AS ads_time FROM `withdrawals`w,`users`u WHERE w.user_id = u.id AND DATE(w.datetime) = '$currentdate' AND w.status = 2";
$sql = "SELECT w.id AS w_id,w.amount AS amount,u.id AS user_id FROM `withdrawals`w,`users`u WHERE w.user_id = u.id AND DATE(w.datetime) = '$currentdate' AND u.total_referrals  < 1 AND u.today_ads < 1200 AND u.worked_days > 7";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {

    foreach ($res as $row) {
        $user_id = $row['user_id'];
        $sql = "UPDATE users SET ads_time = 60 WHERE id = $user_id";
        $db->sql($sql);



        
    }
    $response['success'] = true;
    $response['message'] = "time added";
    
    print_r(json_encode($response));

}else{
    $response['success'] = false;
    $response['message'] = "No Results Found";
    print_r(json_encode($response));

}




?>