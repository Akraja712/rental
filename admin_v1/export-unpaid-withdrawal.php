<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
$currentdate = date('Y-m-d');

$sql = "
	SELECT w.id, 'unpaid' as status, u.name, u.mobile, w.amount, w.datetime, u.bank, CONCAT(',', u.account_num, ',') as account_num, u.holder_name, u.branch, u.ifsc
	FROM withdrawals w
  JOIN users u ON w.user_id = u.id
  WHERE w.status = 0"; 

       $db->sql($sql);
    $developer_records = $db->getResult();

	
	$filename = "UnPaidWithdrawals-data" . date('Ymd') . ".csv";
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