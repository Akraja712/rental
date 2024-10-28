<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');
$sql_query = "SELECT name, email,age,mobile,state,city,referred_by,refer_code,account_num,holder_name,bank,branch,ifsc,withdrawal_status,recharge,total_recharge,total_income,today_income,device_id,total_withdrawal,team_income,earning_wallet,bonus_wallet,balance,registered_datetime,blocked  FROM `users`"; // Fetch only name and email
$db->sql($sql_query);
$developer_records = $db->getResult();

$filename = "AllUsers-data" . date('Ymd') . ".csv";
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

$output = fopen('php://output', 'w');

if (!empty($developer_records)) {
    // Get the keys from the first record to create the header row
    fputcsv($output, array_keys($developer_records[0]));
    
    // Output the data
    foreach ($developer_records as $record) {
        fputcsv($output, $record);
    }
}

fclose($output);
exit;
?>
