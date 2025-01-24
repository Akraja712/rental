<?php
include 'admin_v1/includes/crud.php';
include 'admin_v1/includes/functions.php';

$db = new Database();
$fn = new Functions();
$db->connect();

ini_set('display_errors', 1); // Enable error reporting
error_reporting(E_ALL);

if (isset($_POST['mobile']) && isset($_POST['to_mobile']) && isset($_POST['transfer_amount'])) {
    $mobile = $db->escapeString(htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8')); // Sender's mobile
    $toMobile = $db->escapeString(htmlspecialchars($_POST['to_mobile'], ENT_QUOTES, 'UTF-8')); // Recipient's mobile
    $transferAmount = floatval($_POST['transfer_amount']); // Transfer amount
    $datetime = date('Y-m-d H:i:s'); // Current date and time

    // Validate sender and recipient numbers
    if ($mobile === $toMobile) {
        echo json_encode(['message' => 'Cannot transfer funds to the same mobile number.']);
        exit;
    }

    // Check if sender exists
    $senderQuery = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($senderQuery);
    $sender = $db->getResult();
    if (empty($sender)) {
        echo json_encode(['message' => 'Sender mobile number not found.']);
        exit;
    }

    // Check if recipient exists
    $receiverQuery = "SELECT * FROM users WHERE mobile = '$toMobile'";
    $db->sql($receiverQuery);
    $receiver = $db->getResult();
    if (empty($receiver)) {
        echo json_encode(['message' => 'Recipient mobile number not found.']);
        exit;
    }

    // Validate sender's balance
    $senderBalance = floatval($sender[0]['recharge']);
    if ($senderBalance < $transferAmount) {
        echo json_encode(['message' => 'Insufficient balance in sender\'s wallet.']);
        exit;
    }

    // Perform wallet transfer
    $newSenderBalance = $senderBalance - $transferAmount;
    $newReceiverBalance = floatval($receiver[0]['recharge']) + $transferAmount;

    // Update sender's balance
    $updateSenderQuery = "UPDATE users SET recharge = $newSenderBalance WHERE mobile = '$mobile'";
    $db->sql($updateSenderQuery);

    // Update recipient's balance
    $updateReceiverQuery = "UPDATE users SET recharge = $newReceiverBalance WHERE mobile = '$toMobile'";
    $db->sql($updateReceiverQuery);

    // Log sender's transaction (debit)
    $senderID = $sender[0]['id'];
    $logSenderTransaction = "INSERT INTO transactions (user_id, amount, datetime, type) 
                             VALUES ('$senderID', '$transferAmount', '$datetime', 'debit_transfer')";
    $db->sql($logSenderTransaction);

    // Log recipient's transaction (credit)
    $receiverID = $receiver[0]['id'];
    $logReceiverTransaction = "INSERT INTO transactions (user_id, amount, datetime, type) 
                               VALUES ('$receiverID', '$transferAmount', '$datetime', 'credit_transfer')";
    $db->sql($logReceiverTransaction);

    echo json_encode(['message' => 'Wallet transfer successful.']);
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
