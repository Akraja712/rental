<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');
// Modified condition to include both 'recharge' and 'recharge_orders'
$condition = "type IN ('recharge', 'recharge_orders')"; 
$sql_query = "SELECT  t.id, u.name, u.mobile,t.ads, t.amount, t.type, t.datetime FROM `transactions` t INNER JOIN `users` u ON t.user_id = u.id WHERE $condition";
$db->sql($sql_query);
$developer_records = $db->getResult();

// Generate the filename
$filename = "transactions_" . date('Ymd') . ".csv";			

// Send headers to trigger the file download as CSV
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Open the output stream as the file pointer
$output = fopen('php://output', 'w');

// Check if we have records and process them
if (!empty($developer_records)) {
  $show_column = false;
  
  foreach ($developer_records as $record) {
    if (!$show_column) {
      // Output column headings once
      fputcsv($output, array_keys($record));
      $show_column = true;
    }
    // Output the data rows
    fputcsv($output, $record);
  }
}

fclose($output);
exit;
?>
