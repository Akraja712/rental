<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();

$currentdate = date('Y-m-d');

// SQL query to join withdrawals and users table using user_id and filter by status = 0
$sql_query = "
	SELECT w.id, w.status, u.name, u.mobile, w.amount, w.datetime, u.bank, u.account_num, u.holder_name, u.branch, u.ifsc
	FROM `withdrawals` w
  JOIN `users` u ON w.user_id = u.id
  WHERE w.status = 0";  // Filtering only records where status = 0

$db->sql($sql_query);
$developer_records = $db->getResult();

// Generate the filename for the CSV
$filename = "withdrawals_status_unpaid" . date('Ymd') . ".csv";			

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
