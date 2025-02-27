<?php
include_once('includes/connection.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null; // Ensure user_id is set

$data = array(
    "user_id" => $user_id,
);


// Fetch the user's current balance
$apiUrl = API_URL . "user_details.php"; // Ensure this endpoint provides the user's balance

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if ($response === false) {
    echo "Error: " . curl_error($curl);
    $balance = "N/A";
} else {
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        $userdetails = $responseData["data"];
        if (!empty($userdetails)) {
            $balance = $userdetails[0]["balance"];
            $bank = $userdetails[0]["bank"];
            $branch = $userdetails[0]["branch"];
            $ifsc = $userdetails[0]["ifsc"];
            $account_num = $userdetails[0]["account_num"];
            $holder_name = $userdetails[0]["holder_name"];
        } else {
            $balance = "No balance information available.";
        }
    } else {
        $balance = "Failed to fetch balance.";
        if ($responseData !== null) {
            echo "<script>alert('".$responseData["message"]."')</script>";
        }
    }
}
curl_close($curl);

// Update session balance
$_SESSION['balance'] = $balance;

if (isset($_POST['btnWithdrawal'])) {
    $amount = $_POST['amount'];
    $data = array(
        "user_id" => $user_id,
        "amount" => $amount,
    );
    $apiUrl = API_URL . "withdrawals.php";

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);

    if ($response === false) {
        // Error in cURL request
        echo "Error: " . curl_error($curl);
    } else {
        // Successful API response
        $responseData = json_decode($response, true);
        if ($responseData !== null && isset($responseData["success"])) {
            $message = $responseData["message"];
            if (isset($responseData["balance"])) {
                $_SESSION['balance'] = $responseData['balance'];
                $balance = $_SESSION['balance'];
            }
            // Alert and redirect
            echo "<script>
                    alert('$message');
                    window.location.href = 'withdrawals.php';
                  </script>";
        } else {
            // Failed to fetch transaction details
            if ($responseData !== null) {
                echo "<script>alert('".$responseData["message"]."')</script>";
            }
        }
    }
    
    curl_close($curl);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Additional styles for the boxes */
        .info-box {
            background-color: #4A148C;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .info-box h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .info-box p {
            font-size: 1.25rem;
            margin: 0;
        }
        .withdrawal-container {
            position: relative; 
            padding: 20px; 
        }
        .withdrawal-container h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }
        .withdrawal-button {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1rem;
        }
        .form-container {
            max-width: 400px; 
        }
        .info-box {
            background-color: white; /* Dark background */
            padding: 20px;
            border-radius: 10px;
            text-align: center; /* Center text */
            margin: 10px 0; /* Space between boxes */
            border: 2px solid #4A148C; /* Border color */
        }
        @media (max-width: 576px) {
            .withdrawal-container h2 {
                font-size: 0.9rem;
            }
            .withdrawal-button {
                font-size: 0.600rem;
                top: 0px;
                right: 8px;
            }

        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div class="withdrawal-container">
                <!-- New Boxes for Wallets -->
                <div class="row d-flex justify-content-start">
       
         
        </div>

                <!-- Existing Withdrawal Request Title and Form -->
                <h2>Withdrawal Request</h2>
                <a href="withdrawals.php" style="background-color:#4A148C; color:white;" class="btn withdrawal-button">Back To Withdrawals</a>
                
                <!-- Withdrawal Request Form -->
                <div class="form-container mt-4">
                    <form action="withdrawal_request.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <div class="mb-3">
                            <label for="balance" class="form-label">Remaining Balance</label>
                            <input type="number" class="form-control" id="balance" name="balance" value="<?php echo htmlspecialchars($balance); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Enter Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <button type="submit" name="btnWithdrawal" style="background-color:#4A148C; color:white;" class="btn">Submit Request</button>
                    </form>
                </div>
                    
                    <!-- User Bank Details Table -->
                    <div class="mt-4">
                        <h4>User Bank Details</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Account Number</th>
                                    <th>Holder Name</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>IFSC Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlspecialchars($account_num); ?></td>
                                    <td><?php echo htmlspecialchars($holder_name); ?></td>
                                    <td><?php echo htmlspecialchars($bank); ?></td>
                                    <td><?php echo htmlspecialchars($branch); ?></td>
                                    <td><?php echo htmlspecialchars($ifsc); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>