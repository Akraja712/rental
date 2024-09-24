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

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['ads'])) {
    $response['success'] = false;
    $response['message'] = "Ads is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['sync_type'])) {
    $response['success'] = false;
    $response['message'] = "Sync Type is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
    print_r(json_encode($response));
    return false;
}


$currentdate = date('Y-m-d');
$user_id = $db->escapeString($_POST['user_id']);
$ads = $db->escapeString($_POST['ads']);
$device_id = $db->escapeString($_POST['device_id']);

$sync_type = $db->escapeString($_POST['sync_type']);
$datetime = date('Y-m-d H:i:s');
$type = 'watch_ads';

$t_sync_unique_id = '';
$sql = "SELECT * FROM settings";
$db->sql($sql);
$settings = $db->getResult();
$watch_ad_status = $settings[0]['watch_ad_status'];


function isBetween12AMand6AM() {
    $currentHour = date('H');
    $startTimestamp = strtotime('00:00:00');
    $endTimestamp = strtotime('06:00:00');
    return ($currentHour >= date('H', $startTimestamp)) && ($currentHour < date('H', $endTimestamp));
}
// if (isBetween12AMand6AM()) {
//     $response['success'] = false;
//     $response['message'] = "App Maintence Timing Midnight 12:00 AM to Morning 6:00 AM";
//     print_r(json_encode($response));
//     return false;
// }

$sql = "SELECT * FROM leaves WHERE date = '$currentdate'";
$db->sql($sql);
$resl = $db->getResult();
$lnum = $db->numRows($resl);
$enable = 1;
if ($lnum >= 1) {
    $enable = 0;

}
if ($enable == 0) {
    $response['success'] = false;
    $response['message'] = "Holiday, Come Back Tomorrow";
    print_r(json_encode($response));
    return false;
}

// if ( $watch_ad_status == 0) {
//     $response['success'] = false;
//     $response['message'] = "Watch Ad is disable right now";
//     print_r(json_encode($response));
//     return false;
// } 
$sql = "SELECT without_work,plan,id,reward_ads,device_id,referred_by,status,blocked,total_ads,ads_time,project_type,total_referrals,worked_days,joined_date,missed_days,store_balance,today_ads FROM users WHERE id = $user_id ";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $reward_ads = $res[0]['reward_ads'];
    $user_device_id = $res[0]['device_id'];
    $referred_by = $res[0]['referred_by'];
    $status = $res[0]['status'];
    $blocked = $res[0]['blocked'];
    $total_ads = $res[0]['total_ads'];
    $store_balance = $res[0]['store_balance'];
    $ads_time = $res[0]['ads_time'];
    $project_type = $res[0]['project_type'];
    $total_referrals = $res[0]['total_referrals'];
    $worked_days = $res[0]['worked_days'];
    $joined_date = $res[0]['joined_date'];
    $missed_days = $res[0]['missed_days'];
    $today_ads = $res[0]['today_ads'];
    $without_work = $res[0]['without_work'];
    $plan = $res[0]['plan'];

    // if($total_referrals = 0 && $worked_days > 12){
    //     $ads_time = 60;

    // }

    if($project_type == 'free'){
        $ad_cost = $ads * 0.100;

    }else{
        $ad_cost = $ads * 0.125;
    }
    

    if ($status == 2) {
        $response['success'] = false;
        $response['message'] = "You are blocked";
        print_r(json_encode($response));
        return false;
    }
    if ($status == 0) {
        $response['success'] = false;
        $response['message'] = "You are Account is not Approved";
        print_r(json_encode($response));
        return false;
    }
    if ($blocked == 1) {
        $response['success'] = false;
        $response['message'] = "Your Account is Blocked";
        print_r(json_encode($response));
        return false;
    }
    if ($without_work == 1) {
        $response['success'] = false;
        $response['message'] = "We work for you,you can relax";
        print_r(json_encode($response));
        return false;
    }
    if($total_ads >= 36000){
        $response['success'] = false;
        $response['message'] = "Your Completed Total Ads";
        print_r(json_encode($response));
        return false;

    }
    if($joined_date > $currentdate){
        $response['success'] = false;
        $response['message'] = "Your Plan not Started";
        print_r(json_encode($response));
        return false;

    }
    if($worked_days >= 30 && $plan == 'A1'){
        $response['success'] = false;
        $response['message'] = "Your Plan Expired";
        print_r(json_encode($response));
        return false;

    }



    if($user_device_id == ''){
        $sql = "UPDATE users SET device_id = '$device_id'  WHERE id=" . $user_id;
        $db->sql($sql);

    }else{
        if ($user_device_id != $device_id) {
            $sql = "UPDATE users SET device_id = '$device_id'  WHERE id=" . $user_id;
            $db->sql($sql);

            $sql = "INSERT INTO dup_devices (`user_id`,`device_id`,`datetime`)VALUES($user_id,'$device_id','$datetime')";
            $db->sql($sql);

    
        }
    
    }



    if($sync_type == 'reward_sync'){

            
            if (empty($reward_ads)) {
                $response['success'] = false;
                $response['message'] = "Reward Ads is Empty";
                print_r(json_encode($response));
                return false;
            }
            $type = 'reward_ads';
            $ad_cost = $reward_ads * 0.125;
            $ads  = $reward_ads;
            $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`)VALUES('$user_id','$ads','$ad_cost','$datetime','$type')";
            $db->sql($sql);
        
            $sql = "UPDATE users SET reward_ads = 0,today_ads = today_ads + $ads,total_ads = total_ads + $ads,balance = balance + $ad_cost,earn = earn + $ad_cost  WHERE id=" . $user_id;
            $db->sql($sql);
        



        
    }else {
        if (empty($_POST['sync_unique_id'])) {
            $response['success'] = false;
            $response['message'] = "Sync Unique Id is Empty";
            print_r(json_encode($response));
            return false;
        }
        $sync_unique_id = $db->escapeString($_POST['sync_unique_id']);
        $sql = "SELECT COUNT(id) AS count  FROM transactions WHERE user_id = $user_id AND DATE(datetime) = '$currentdate' AND type = '$type'";
        $db->sql($sql);
        $tres = $db->getResult();
        $t_count = $tres[0]['count'];
        $sync_limit = 10;

        if($plan == 'A1S' || $plan == 'A1U'){
            $sync_limit = 5;

        }
        if ($t_count >= $sync_limit) {
            $response['success'] = false;
            $response['message'] = "You Reached Daily Sync Limit";
            print_r(json_encode($response));
            return false;
        }

        $sql = "SELECT id FROM transactions WHERE user_id = $user_id AND type = 'target_bonus' AND DATE(datetime) = '$currentdate' ORDER BY datetime DESC LIMIT 1 ";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        if ($num == 1) {
            $response['success'] = false;
            $response['message'] = "You Achieved Target Bonus";
            print_r(json_encode($response));
            return false;

        }
        
        $sql = "SELECT sync_unique_id,datetime FROM transactions WHERE user_id = $user_id AND type = '$type' ORDER BY datetime DESC LIMIT 1 ";
        $db->sql($sql);
        $tres = $db->getResult();
        $num = $db->numRows($tres);
        if($plan == 'A1'){
            $code_min_sync_time = 45;

        }
        else{
            $code_min_sync_time = 15;
        }
        
        $totalMinutes = 0;
        $sync = 0;
        $ex_ads_time = 0;
        if ($num >= 1) {
            $t_sync_unique_id = $tres[0]['sync_unique_id'];
            $dt1 = $tres[0]['datetime'];
            $date1 = new DateTime($dt1);
            $date2 = new DateTime($datetime);
            $sync = 1;
        
            $diff = $date1->diff($date2);
            $totalMinutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            $dfi = $code_min_sync_time - $totalMinutes;
        
        }

        
        
        if($ads == '120'){
            $total_time = (($ads_time * 120)/60);
            if($totalMinutes < $total_time && $sync == 1){
                $message = "don't use any tricks to watching ads";

            }else{
                if(($sync_unique_id != $t_sync_unique_id) || $t_sync_unique_id == ''){

                    $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`,`sync_unique_id`)VALUES('$user_id','$ads','$ad_cost','$datetime','$type','$sync_unique_id')";
                    $db->sql($sql);
                    $join = "balance = balance + $ad_cost";

                    if($plan == 'A1' && $total_referrals < 4 && $total_referrals < $missed_days){
                        $join = "store_balance = store_balance + $ads";
                    
                    }
                    if($plan == 'A1U'){
                        $ad_cost = 72 * 0.125;
                        $store_ads = 48;
                        $join = "balance = balance + $ad_cost,store_balance = store_balance + $store_ads";
                    
                    }
                    if($plan == 'A1S'){
                        $join = "store_balance = store_balance + $ads";
                    
                    }
                    $sql = "UPDATE users SET today_ads = today_ads + $ads,total_ads = total_ads + $ads," . $join . ",earn = earn + $ad_cost WHERE id=" . $user_id;
                    $db->sql($sql);
                    $message = "Sync updated successfully";
                    
                
                
                }else{
                    $message = "don't use any tricks to watching ads";
        
            
                }

            }

            
        
        }else{
            $message = "poor internet connection, chaeck your internet";
        }
    }
}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
    print_r(json_encode($response));
    return false;
}



$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$res = $db->getResult();

$response['success'] = true;
$response['message'] = $message;
$response['data'] = $res;
echo json_encode($response);


?>