<?php
session_start();
// include_once('../api-firebase/send-email.php');
include('../includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");
date_default_timezone_set('Asia/Kolkata');


include_once('../includes/custom-functions.php');
$fn = new custom_functions;
require_once('../includes/firebase.php');
require_once ('../includes/push.php');
include_once('../includes/functions.php');
$function = new functions;

$datetime = date('Y-m-d H:i:s');

if (isset($_POST['cancel_withdrawal']) && $_POST['cancel_withdrawal'] == 1) {
    $count = 0;
    $count1 = 0;
    $error = false;
    $filename = $_FILES["upload_file"]["tmp_name"];
    $result = $fn->validate_image($_FILES["upload_file"], false);
    if (!$result) {
        $error = true;
    }
    if ($_FILES["upload_file"]["size"] > 0  && $error == false) {
        $file = fopen($filename, "r");
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // print_r($emapData);
            if ($count1 != 0) {
                $w_id = trim($db->escapeString($emapData[0]));
             

                $sql = "UPDATE withdrawals SET status=2 WHERE id = $w_id";
                $db->sql($sql);
                $sql = "SELECT * FROM `withdrawals` WHERE id = $w_id ";
                $db->sql($sql);
                $res = $db->getResult();
                $user_id= $res[0]['user_id'];
                $amount= $res[0]['amount'];
                $sql = "UPDATE users SET balance= balance + $amount WHERE id = $user_id";
                $db->sql($sql);
                
            }

            $count1++;
        }
        fclose($file);
        // $file = fopen($filename, "r");
        // while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
        //     // print_r($emapData);
        //     if ($count1 != 0) {
        //         $emapData[0] = trim($db->escapeString($emapData[0]));
        //         $emapData[1] = trim($db->escapeString($emapData[1]));  
                
        //         $sql = "UPDATE users SET `support_id`= $emapData[1],op_leads = 1 WHERE id= $emapData[0]";
        //         $db->sql($sql);

        //     }

        //     $count1++;
        // }
        // fclose($file);

        echo "<p class='alert alert-success'>CSV file is successfully imported!</p><br>";
    } else {
        echo "<p class='alert alert-danger'>Invalid file format! Please upload data in CSV file!</p><br>";
    }
}
if (isset($_POST['delete_variant'])) {
    $v_id = $db->escapeString(($_POST['id']));
    $sql = "DELETE FROM product_variant WHERE id = $v_id";
    $db->sql($sql);
    $result = $db->getResult();
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}


