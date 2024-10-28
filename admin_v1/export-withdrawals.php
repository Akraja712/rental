<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
$currentdate = date('Y-m-d');

$sql = "SELECT 
u.name AS `Beneficiary Name (Mandatory) Full name of the customer - eg: Bruce Wayne`, 
CONCAT('', u.account_num, '') AS `Beneficiary Account number (Mandatory) Beneficiary Account number to which the money should be transferred`, 
u.ifsc AS `IFSC code (Mandatory) IFSC code of beneficary's bank. eg:KKBK0000958`, 
w.amount AS `Amount (Mandatory) Amount that needs to be transfered. Eg: 100.00`, 
NULL AS `Description / Purpose (Optional) For Internal Reference eg: For salary`
FROM users u JOIN  withdrawals w ON u.id = w.user_id";
	$db->sql($sql);
	$developer_records = $db->getResult();
	
	$filename = "AllWithdrawals-data" . date('Ymd') . ".csv";
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