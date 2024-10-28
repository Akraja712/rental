<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
$currentdate = date('Y-m-d');

$sql = "SELECT 
'customer' AS `Contact type - Select from List (Mandatory)`,
u.name AS `Business Name - Minimum 3 char & Max 100 char., Name should not end with space (Mandatory)`, 
u.name AS `Contact Person Name - Minimum 3 char & Max 50 char., Name should not end with space (M),`,
u.mobile AS `Mobile number - Numeric Value and 10 digits (Mandatory)`, 
u.email AS ` E-Mail id of Contact (O)`, 
u.name AS ` Billing Name - This values will be displayed on Invoices (M)`,
NULL AS `Billing Address - This values will be displayed on Invoices (Optional)`,
NULL AS ` Billing code Pincode - (O)`,
NULL AS `Shipping Name -(O)`,
NULL AS `Shipping Address - (O)`,
NULL AS ` shipping code Pincode -(O)`,
CONCAT(',', u.account_num, ',') AS ` Beneficiary account number(O)`, 
u.ifsc AS `IFSC code (O)`, 
u.bank AS `Bank name(O)`, 
u.branch AS ` Branch name(O)`,
NULL AS ` Pan(O)`,
NULL AS `GSTIN(O)`,
NULL AS `GST Registration Type(O)`,
NULL AS `e-Commerce Operator? (O)`,
NULL AS `Is Transporter? (O)`,
NULL AS `Transporter ID (O)`,
NULL AS `TDS(O)`,
NULL AS `Notes (O)`
FROM users u 
JOIN withdrawals w ON u.id = w.user_id AND w.status = 0;
";

	$db->sql($sql);
	$developer_records = $db->getResult();
	
	$filename = "Customers-data" . date('Ymd') . ".csv";
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
