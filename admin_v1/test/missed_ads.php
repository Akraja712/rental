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
$datetime = date('Y-m-d H:i:s');


$sql = "SELECT id,joined_date FROM `users` WHERE plan = 'A1' AND old_plan = 0 AND status = 1 ";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $ID = $row['id'];
        $joined_date = $row['joined_date'];
        $sql = "SELECT COUNT(id) AS total,user_id FROM `daily_ads` WHERE user_id = $ID AND ads < 1200 AND date >= '$joined_date'";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        if ($num >= 1){
            $total = $res[0]['total'];
            $user_id = $res[0]['user_id'];
            $sql = "UPDATE users SET missed_days = $total WHERE id = $user_id";
            $db->sql($sql);
        }


    

    }
    $response['success'] = true;
    $response['message'] = "updated Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Users Not found";
    print_r(json_encode($response));

}
?>