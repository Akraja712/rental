<?php
include 'includes/crud.php';
include 'includes/functions.php';

$db = new Database();
$fn = new Functions();
$db->connect();

ini_set('display_errors', 1); // Enable error reporting for debugging
error_reporting(E_ALL); // Report all errors

if (isset($_POST['mobile']) && isset($_POST['to_mobile']) && isset($_POST['transfer_amount'])) {
    $mobile = $db->escapeString(htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8'));
    $toMobile = $db->escapeString(htmlspecialchars($_POST['to_mobile'], ENT_QUOTES, 'UTF-8'));
    $transferAmount = floatval($_POST['transfer_amount']);
    $datetime = date('Y-m-d H:i:s'); // Current date and time

    // Check if both mobile numbers exist
    $senderQuery = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($senderQuery);
    $sender = $db->getResult();

    $receiverQuery = "SELECT * FROM users WHERE mobile = '$toMobile'";
    $db->sql($receiverQuery);
    $receiver = $db->getResult();

    if (empty($sender)) {
        echo json_encode(['message' => 'Sender mobile number not found.']);
        exit;
    }

    if (empty($receiver)) {
        echo json_encode(['message' => 'Recipient mobile number not found.']);
        exit;
    }

    // Check if the sender and receiver are the same
    if ($mobile === $toMobile) {
        // If same, we should still allow transfer but just ensure it's a valid transfer
        if ($transferAmount <= 0 || !is_numeric($transferAmount)) {
            echo json_encode(['message' => 'Please enter a valid transfer amount.']);
            exit;
        }

        // Insert transaction record for the sender (no balance change, just log)
        $senderID = $sender[0]['id']; // Assuming 'id' is the primary key in the `users` table
        $senderType = 'transfer_to_self';
        $transactionSender = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) 
                              VALUES ('$senderID', '$transferAmount', '$datetime', '$senderType')";
        $db->sql($transactionSender);

        // Return success message for transferring to self
        echo json_encode(['message' => 'Transferred amount is same mobile number .']);
        exit;
    }

    // Validate sender's recharge balance
    $senderBalance = floatval($sender[0]['recharge']);
    if ($senderBalance < $transferAmount) {
        echo json_encode(['message' => 'Insufficient balance in sender\'s wallet.']);
        exit;
    }

    // Process the transfer (for different mobile numbers)
    $newSenderBalance = $senderBalance - $transferAmount;
    $newReceiverBalance = floatval($receiver[0]['recharge']) + $transferAmount;

    // Update balances in the database
    $updateSender = "UPDATE users SET recharge = $newSenderBalance WHERE mobile = '$mobile'";
    $db->sql($updateSender);

    $updateReceiver = "UPDATE users SET recharge = $newReceiverBalance WHERE mobile = '$toMobile'";
    $db->sql($updateReceiver);

    // Insert transaction record for the sender (debit)
    $senderID = $sender[0]['id']; // Assuming 'id' is the primary key in the `users` table
    $senderType = 'debit_transfer';
    $transactionSender = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) 
                          VALUES ('$senderID', '$transferAmount', '$datetime', '$senderType')";
    $db->sql($transactionSender);

    // Insert transaction record for the receiver (credit)
    $receiverID = $receiver[0]['id']; // Assuming 'id' is the primary key in the `users` table
    $receiverType = 'credit_transfer';
    $transactionReceiver = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) 
                            VALUES ('$receiverID', '$transferAmount', '$datetime', '$receiverType')";
    $db->sql($transactionReceiver);

    echo json_encode(['message' => 'Wallet transfer successful.']);
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
