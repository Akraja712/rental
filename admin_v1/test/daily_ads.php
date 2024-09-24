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
$currentdate = date('Y-m-d');
//$currentdate = '2023-12-02';

$sql = "SELECT id FROM `users` WHERE plan = 'A1' AND old_plan = 0 AND status = 1 AND today_ads != 0";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $ID = $row['id'];
        $sql = "SELECT id FROM `transactions` WHERE user_id = $ID AND type = 'refer_bonus' AND DATE(datetime) = '$currentdate'";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        if ($num == 0){
            $sql = "SELECT ads,DATE(datetime) AS date,user_id FROM `transactions` WHERE user_id = $ID AND type = 'watch_ads' AND DATE(datetime) = '$currentdate' GROUP BY datetime";
            $db->sql($sql);
            $res= $db->getResult();
            $num = $db->numRows($res);
            if ($num >= 1){
                foreach ($res as $row) {
                    $user_id = $row['user_id'];
                    $ads = $row['ads'];
                    $date = $row['date'];
                    $sql = "UPDATE users SET real_ads = real_ads +  $ads WHERE id = $user_id";
                    $db->sql($sql);
    
    
                }
    
            }

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