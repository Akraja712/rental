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
            $earning_wallet = $userdetails[0]["earning_wallet"];
            $bonus_wallet = $userdetails[0]["bonus_wallet"];
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }

        .profile-header {
            background-color: #4A148C;
            color: #fff;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 20px;
            text-align: center;
            display: flex; /* Add flex display */
            justify-content: space-around; /* Distribute items evenly */
            align-items: center; /* Align items vertically */
        }

        .wallet-item {
            text-align: center; /* Center text within each wallet item */
            flex: 1; /* Allow equal distribution */
        }
        .wallet-item h5 {
                color: #FFD700; /* Golden color */
        }


        .nav-links {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            text-decoration: none;
            color: #4A148C;
            font-weight: bold;
        }

        .nav-links:hover {
            background-color: #4A148C;
            color: white;
        }

        .nav-links i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #4A148C;
        }

        .nav-links:hover i {
            color: white;
        }

        .card-body {
            padding: 20px;
        }

        .container-fluid {
            margin-top: 50px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .nav-link {
                font-size: 14px;
                padding: 10px;
            }

            .nav-link i {
                font-size: 1.2rem;
                margin-right: 10px;
            }
            .profile-header h5{
                font-size: 1rem;
               
        }
        }

    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mt-3">
                       <div class="profile-header">
                            <div class="wallet-item">
                                <h5>Earning Wallet</h5>
                                <p class="fw-bold">₹<?php echo htmlspecialchars($earning_wallet); ?></p> <!-- Placeholder value -->
                            </div>
                            <div class="vr mx-3"></div> <!-- Vertical Line -->
                            <div class="wallet-item">
                                <h5>Bonus Wallet</h5>
                                <p class="fw-bold">₹<?php echo htmlspecialchars($bonus_wallet); ?></p> <!-- Placeholder value -->
                            </div>
                            <div class="vr mx-3"></div> <!-- Vertical Line -->
                            <div class="wallet-item">
                                <h5>Main Wallet</h5>
                                <p class="fw-bold">₹<?php echo htmlspecialchars($balance); ?></p> <!-- Placeholder value -->
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Menu Items -->
                            <!--<a href="ins_recharge.php" class="nav-links">
                                <i class="bi bi-cash"></i>
                                <span>Instant Recharge</span>
                            </a>-->
                            <a href="withdrawals.php" class="nav-links">
                                <i class="bi bi-cash-stack"></i>
                                <span>Withdrawals</span>
                            </a>
                            <a href="transactions.php" class="nav-links">
                                <i class="bi bi-credit-card"></i>
                                <span>Transactions</span>
                            </a>
                            <a href="transfer_wallet.php" class="nav-links">
                                <i class="bi bi-credit-card"></i>
                                <span>Transfer Wallet</span>
                            </a>
                            <a href="bank_details.php" class="nav-links">
                                <i class="bi bi-bank"></i>
                                <span>Bank Account</span>
                            </a>
                            <a href="set_password.php" class="nav-links">
                                <i class="bi bi-lock"></i>
                                <span>Set Password</span>
                            </a>
                            <a href="invite_friends.php" class="nav-links">
                                <i class="bi bi-people-fill"></i>
                                <span>Invite Friends</span>
                            </a>
                            <a href="profile.php" class="nav-links">
                                <i class="bi bi-people-fill"></i>
                                <span>Profile</span>
                            </a>
                            <a href="logout.php" class="nav-links">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
