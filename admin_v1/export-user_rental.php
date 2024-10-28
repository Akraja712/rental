<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');
$sql_query = "SELECT ur.user_id, u.name AS user_name, u.mobile,u.referred_by, ur.rental_id, r.name AS rental_name, r.price, r.course_charges, r.monthly_rental_earnings, r.daily_earnings, r.min_refers, r.invite_bonus,ur.income, ur.joined_date, ur.claim
              FROM `user_rental` ur
              JOIN `users` u ON ur.user_id = u.id
              JOIN `rental` r ON ur.rental_id = r.id
              ORDER BY ur.id"; // Join user_plan with users and plans on user_id and plan_id respectively, and order by user_plan id
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
