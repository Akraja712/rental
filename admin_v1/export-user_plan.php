<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');
$sql_query = "SELECT up.user_id, u.name AS user_name, u.mobile,u.referred_by, up.plan_id, p.name AS plan_name, p.price, p.daily_codes, p.daily_earnings, p.per_code_cost, up.income, up.joined_date, up.claim
              FROM `user_plan` up
              JOIN `users` u ON up.user_id = u.id
              JOIN `plan` p ON up.plan_id = p.id
              ORDER BY up.id"; // Join user_plan with users and plans on user_id and plan_id respectively, and order by user_plan id
$db->sql($sql_query);
$developer_records = $db->getResult();

$filename = "AllUserPlan-data" . date('Ymd') . ".csv";
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
