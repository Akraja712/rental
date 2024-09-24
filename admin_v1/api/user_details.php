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
    echo json_encode($response);
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql_user = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql_user);
$res_user = $db->getResult();
$num = $db->numRows($res_user);

if ($num >= 1) {

    // $sql_user = "SELECT * FROM `recharge_trans` WHERE user_id = $user_id AND status = 0 ORDER BY `id` DESC";
    // $db->sql($sql_user);
    // $rec = $db->getResult();
    // $num = $db->numRows($rec);
    // if ($num >= 1) {
    //     $txn_id = $rec[0]['txn_id'];
    //     $date = $rec[0]['txn_date'];
    //     $old_f_date = $rec[0]['txn_date'];
    //     $date = date('d-m-Y', strtotime($old_f_date));

    //     $key = '707029bb-78d4-44b6-9f72-0d7fe80e338b';
    //         // API endpoint
    //     $url = 'https://api.ekqr.in/api/check_order_status';

    //     // Data to be sent
    //     $data = array(
    //         'client_txn_id' => $txn_id,
    //         'txn_date' => $date,
    //         'key' => $key
    //     );

    //     // Initialize curl session
    //     $ch = curl_init();

    //     // Set curl options
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     // Execute curl request
    //     $resp = curl_exec($ch);

    //     // Check for errors
    //     if(curl_errno($ch)){
    //         echo 'Curl error: ' . curl_error($ch);
    //     }

    //     // Close curl session
    //     curl_close($ch);

    //     $responseArray = json_decode($resp, true);
    //     if ($responseArray['status'] === true) {
    //         $datetime = date('Y-m-d H:i:s');
    //         $type = 'recharge';
    //         $data = $responseArray['data'];
    //         $amount = $data['amount'];
    //         $status = $data['status'];
    //         if($status == 'success'){
    //             $txn_id = $data['client_txn_id'];
    //             $sql = "SELECT id FROM recharge_trans WHERE txn_id = $txn_id AND status = 0 ";
    //             $db->sql($sql);
    //             $res= $db->getResult();
    //             $num = $db->numRows($res);

    //             if ($num == 1){
    //                 $rech_trans_id = $res[0]['id'];
    //                 $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$amount', '$datetime', '$type')";
    //                 $db->sql($sql);
                
    //                 $sql_query = "UPDATE users SET recharge = recharge + $amount, total_recharge = total_recharge + $amount WHERE id = '$user_id'";
    //                 $db->sql($sql_query);
    //                 $sql_query = "UPDATE recharge_trans SET status = 1 WHERE id = '$rech_trans_id'";
    //                 $db->sql($sql_query);

    //             }

            
    //             $response['success'] = true;
    //             $response['message'] = "Transaction completed successfully";

    //         }else{
    //             $response['success'] = false;
    //             $response['message'] = "Transaction failed";
    //         }


    //     } else {
    //         $response['success'] = false;
    //         $response['message'] = "Transaction failed";
    //     }
    

    // }




    $user_details = $res_user[0];
    $user_details['profile'] = DOMAIN_URL . $user_details['profile'];
    $response['success'] = true;
    $response['message'] = "User Details Retrieved Successfully";
    $response['data'] = array($user_details);
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "User Not found";
    echo json_encode($response);
}
?>
