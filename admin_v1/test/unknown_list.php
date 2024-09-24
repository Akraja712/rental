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


$sql = "SELECT id,referred_by FROM `users` WHERE DATE(registered_date) >= '2023-12-01' AND status = 0 AND referred_by != ''";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $referred_by = $row['referred_by'];
        $user_id = $row['id'];
        $sql = "SELECT id FROM `users` WHERE status = 1 AND refer_code = '$referred_by'";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        $unknown = 1;
        if ($num >= 1 && $referred_by != ''){
            $unknown = 0;

        }

        $sql = "UPDATE users SET unknown = $unknown WHERE id = $user_id";
        $db->sql($sql);


    

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