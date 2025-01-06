<?php
include_once('includes/connection.php');
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null; // Ensure user_id is set

if (!$user_id) {
    header("Location: index.php");
    exit();
}

$data = array(
    "user_id" => $user_id,
);

$apiUrl = API_URL."user_details.php";


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
    if ($responseData !== null && $responseData["success"]) {
        // Display transaction details
        $userdetails = $responseData["data"];
        if (!empty($userdetails)) {
            $total_income = $userdetails[0]["total_income"];
            $total_recharge = $userdetails[0]["total_recharge"];
            $balance = $userdetails[0]["balance"];
            $total_withdrawal = $userdetails[0]["total_withdrawal"];
            $today_income = $userdetails[0]["today_income"];
            $team_income = $userdetails[0]["team_income"];
        } else {
            echo "<script>alert('".$responseData["message"]."')</script>";
        }
    } else {

        if ($responseData !== null) {
            echo "<script>alert('".$responseData["message"]."')</script>";
    
        }
    }
}

curl_close($curl);     

?>
<?php
$apiUrl = API_URL . "settings.php";

$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if ($response === false) {
    // Error in cURL request
    echo "Error: " . curl_error($curl);
    $settingsdetails = [];
} else {
    // Successful API response
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        // Store transaction details
        $settingsdetails = $responseData["data"];
        if (!empty($settingsdetails) && isset($settingsdetails[0]["offer_image"])) {
            // Assign offer image if it exists
            $offer_image = $settingsdetails[0]["offer_image"];
        } else {
            // Fallback to default image if no offer image is available
            $offer_image = 'path/to/default-image.jpg';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Dashboard</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo.jpeg">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        /* Enhanced styles for the dashboard */
        .info-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .info-box h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .info-box p {
            font-size: 1.5rem;
            margin: 0;
            font-weight: bold;
        }

        /* Icon styling inside the info boxes */
        .info-box i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Colors and effects */
        .info-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .total-income {
            background: linear-gradient(45deg, #ff7043, #d84315);
            color: white;
        }

        .total-recharge {
            background: linear-gradient(45deg, #fdd835, #f57f17);
            color: white;
        }

        .total-assets {
            background: linear-gradient(45deg, #66bb6a, #2e7d32);
            color: white;
        }

        .total-withdrawal {
            background: linear-gradient(45deg, #4fc3f7, #0277bd);
            color: white;
        }

        .today-income {
            background: linear-gradient(45deg, #26a69a, #004d40);
            color: white;
        }

        .team-income {
            background: linear-gradient(45deg, #d4e157, #afb42b);
            color: white;
        }
        /* Styling for the settings text box */
.settings-text {
    background: linear-gradient(45deg,rgb(255, 255, 255),rgb(255, 255, 255));
    color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.settings-text h4 {
    font-size: 1.3rem;
    margin-bottom: 15px;
    font-weight: bold;
}

.settings-text p {
    font-size: 1.6rem;
    margin: 0;
    font-weight: bold;
    color: rgba(6, 5, 5, 0.8);
}

.settings-text i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: rgba(6, 5, 5, 0.8);
}

/* Hover effect */
.settings-text:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}


        /* Adjusting column behavior for mobile */
           /* Adjusting column behavior and height for mobile */
    @media (max-width: 768px) {
        .col-sm-6 {
            max-width: 50%;
        }

        /* Decrease the height and padding of the info boxes on mobile */
        .info-box {
            padding: 15px; /* Reduce padding */
            min-height: 150px; /* You can adjust this height as needed */
        }

        /* Adjust the font sizes on mobile */
        .info-box h4 {
            font-size: 1rem;
        }

        .info-box p {
            font-size: 1.2rem;
        }

        .info-box i {
            font-size: 2rem; /* Reduce icon size */
        }
    }

    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>

        <div class="col py-3">
            <div class="row">
            <div class="col-6 col-md-4 mb-3">
                    <div class="info-box total-assets">
                        <i class="bi bi-wallet2"></i>
                        <h4>Main Wallet Balance</h4>
                        <p>₹<?php echo $balance; ?></p>
                    </div>
                </div>

                <!-- Total Income -->
                <div class="col-6 col-md-4 mb-3">
                    <div class="info-box total-income">
                        <i class="bi bi-cash-coin"></i>
                        <h4>Total Income</h4>
                        <p>₹<?php echo $total_income; ?></p>
                    </div>
                </div>
                <!-- Total Recharge -->
                <div class="col-6 col-md-4 mb-3">
                    <div class="info-box total-recharge">
                        <i class="bi bi-piggy-bank"></i>
                        <h4>Total Recharge</h4>
                        <p>₹<?php echo $total_recharge; ?></p>
                    </div>
                </div>
                <!-- Total Withdrawals -->
                <div class="col-6 col-md-4 mb-3">
                    <div class="info-box total-withdrawal">
                        <i class="bi bi-wallet-fill"></i>
                        <h4>Total Withdrawals</h4>
                        <p>₹<?php echo $total_withdrawal; ?></p>
                    </div>
                </div>
                <!-- Today's Income -->
                <div class="col-6 col-md-4 mb-3">
                    <div class="info-box today-income">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h4>Today's Income</h4>
                        <p>₹<?php echo $today_income; ?></p>
                    </div>
                </div>
                <!-- Team Income -->
                <div class="col-6 col-md-4 mb-3">
                    <div class="info-box team-income">
                        <i class="bi bi-people-fill"></i>
                        <h4>Team Income</h4>
                        <p>₹<?php echo $team_income; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="offerModalLabel">Special New Year Offer!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                <img src="<?php echo htmlspecialchars($offer_image); ?>" alt="New Year Offer" class="img-fluid" style="width:300px; max-width: 900px; height: 350px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> 



    <!-- Script to Show Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var offerModal = new bootstrap.Modal(document.getElementById('offerModal'));
            offerModal.show();
        });
    </script>


<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
