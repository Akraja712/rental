<?php
include_once('includes/connection.php');
session_start();

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null; // Ensure user_id is set

if (!$user_id) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['btnPay'])) {
    $order_id = $_POST['order_id'];
    $data = array(
        "user_id" => $user_id,
        "order_id" => $order_id,
    );
    $apiUrl = API_URL . "order_pay.php";

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
            // Alert and redirect
            echo "<script>
                    alert('$message');
                    window.location.href = 'recharge.php';
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
$data = array(
    "user_id" => $user_id,
);

$apiUrl = API_URL . "pay_links_list.php";

$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if ($response === false) {
    // Error in cURL request
    echo "Error: " . curl_error($curl);
    $userdetails = [];
} else {
    // Successful API response
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        // Store transaction details
        $payOptions = $responseData["data"];
    } else {
     
        $userdetails = [];
    }
}

curl_close($curl);

$apiUrl = API_URL . "order_pay_list.php";

$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if ($response === false) {
    // Error in cURL request
    echo "Error: " . curl_error($curl);
    $userdetails = [];
} else {
    // Successful API response
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        // Store transaction details
        $userdetails = $responseData["data"];
    } else {
     
        $userdetails = [];
    }
}

curl_close($curl);

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
    $recharge = "N/A";
} else {
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        $details = $responseData["data"];
        if (!empty($details)) {
            $recharge = $details[0]["recharge"];
        } else {
            $recharge = "No recharge information available.";
        }
    } else {
        $recharge = "Failed to fetch recharge.";
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
    <title>Web</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/money.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Additional styles for the boxes */
        .info-box {
            background-color: #f8f9fa;
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
        .blue-underline {
            text-decoration: underline;
            text-decoration-color: blue;
        }
        @media (max-width: 576px) {
            .withdrawal-container h2 {
                font-size: 1.5rem;
            }
            .withdrawal-button {
                font-size: 0.650rem;
                top: 21px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
    <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div class="col-md-6">
                <div class="row">
                    <h3>Recharge Balance - <?php echo htmlspecialchars($recharge); ?></h3>
                </div>
                <div class="row">
                <a href="recharge_video.mp4" style="color: #3eb3a8; text-decoration: underline; text-decoration-color: #3eb3a8;">How to Pay?</a>

                </div>
            </div>

            <br>
         
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" aria-label="Select Recharge">
                            <?php
                            foreach ($payOptions as $option) {
                                echo "<option value='" . $option["links"] . "'>" . $option["amount"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button class="btn"  style="background-color:#3eb3a8; color:white;" onclick="redirectToOptionLink(document.querySelector('.form-select'))">Pay</button>
                    </div>
                </div>
                <br>
                <div class="row">
                <form action="recharge.php" method="post">
                    <div class="col-md-9">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Enter Order Id</label>
                                <input  class="form-control" id="order_id" name="order_id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="btnPay"  style="background-color:#3eb3a8; color:white;" class="btn">Submit Request</button>
                        </div>
                </form>

                </div>
            </div>

            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Order Id</th>
                            <th scope="col">Status</th>
                            <th scope="col">Amount</th>
                            <th scope="col">DateTime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through all withdrawals and display each one -->
                        <?php foreach ($userdetails as $index => $withdrawal): ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo htmlspecialchars($withdrawal['order_id']); ?></td>
                                <td>
                                    <?php 
                                    if ($withdrawal['status'] === '1') {
                                        echo '<span class="text-success">Success</span>';
                                    } elseif ($withdrawal['status'] === '0') {
                                        echo '<span class="text-primary">Pending</span>';
                                    } elseif ($withdrawal['status'] === '2') {
                                         echo '<span class="text-danger">Rejeted</span>';
                                    } 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($withdrawal['amount']); ?></td>
                                <td><?php echo htmlspecialchars($withdrawal['datetime']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($userdetails)): ?>
                            <tr>
                                <td colspan="4">No transactions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            <script>
                function redirectToOptionLink(selectElement) {
                    var selectedOption = selectElement.value;
                    if (selectedOption) {
                        window.open(selectedOption, '_blank');
                    }
                }
            </script>

            
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
