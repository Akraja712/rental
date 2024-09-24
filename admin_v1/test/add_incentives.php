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


$sql = "SELECT * FROM `users` WHERE joined_date >= '2023-08-27' AND status = 1";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $ID = $row['id'];
        $support_id = $row['support_id'];
        $lead_id = $row['lead_id'];
        $referred_by = $row['referred_by'];

        if(strlen($referred_by) < 4){
            $incentives = 50;
        }else{
            $incentives = 7.5;
            
        }
        $sql_query = "UPDATE staffs SET incentives = incentives + $incentives,earn = earn + $incentives,balance = balance + $incentives,supports = supports + 1 WHERE id =  $support_id";
        $db->sql($sql_query);

        $sql_query = "UPDATE staffs SET incentives = incentives + $incentives,earn = earn + $incentives,balance = balance + $incentives,leads = leads + 1 WHERE id =  $lead_id";
        $db->sql($sql_query);
        
        $sql_query = "INSERT INTO incentives (user_id,staff_id,amount,datetime,type)VALUES($ID,$support_id,$incentives,'$datetime','support')";
        $db->sql($sql_query);

        $sql_query = "INSERT INTO incentives (user_id,staff_id,amount,datetime,type)VALUES($ID,$lead_id,$incentives,'$datetime','lead')";
        $db->sql($sql_query);

        $sql_query = "INSERT INTO staff_transactions (staff_id,amount,datetime,type)VALUES($support_id,$incentives,'$datetime','incentives')";
        $db->sql($sql_query);

        $sql_query = "INSERT INTO staff_transactions (staff_id,amount,datetime,type)VALUES($lead_id,$incentives,'$datetime','incentives')";
        $db->sql($sql_query);
    

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