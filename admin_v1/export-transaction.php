<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');

// Modify the SQL query to join transactions with users to fetch name and mobile
$sql_query = "SELECT transactions.user_id, users.name, users.mobile, transactions.type, transactions.datetime, transactions.amount 
              FROM `transactions`
              JOIN `users` ON transactions.user_id = users.id"; // Assuming user_id in transactions matches id in users

$db->sql($sql_query);
$developer_records = $db->getResult();

$filename = "AllTransactions-data" . date('Ymd') . ".csv";
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