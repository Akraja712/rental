<?php
include_once('includes/connection.php');
session_start();

// Check if the user is logged in
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!$user_id) {
    header("Location: index.php");
    exit();
}

$data = array(
    "user_id" => $user_id,
);

$apiUrl = API_URL . "user_plan_list.php";

$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if ($response === false) {
    // Error in cURL request
    echo "Error: " . curl_error($curl);
    $plans = [];
} else {
    // Successful API response
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        // Store all plan details
        $plans = $responseData["data"];
    } else {
        if ($responseData !== null) {
            echo "<script>alert('" . $responseData["message"] . "')</script>";
        }
        $plans = [];
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
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        /* Additional styles for the boxes */
        .plan-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .plan-box img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 5px;
        }

        .plan-details {
            flex-grow: 1;
        }

        .plan-details p {
            margin: 5px 0;
            font-size: 1.1rem;
        }

        .highlight {
            background-color: yellow;
            font-weight: bold;
            padding: 0 5px;
        }

        .purchase-btn {
            background-color: #4A148C;
            color: white;
        }

        /* Style for the product name box */
        .product-name-box {
            background-color: #4A148C;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 0.90rem;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-success {
            background-color: #4A148C;
            border-color: #4A148C;
        }

        .btn-success[disabled] {
            background-color: #ccc;
            border-color: #ccc;
        }

        .activated-jobs-link {
            margin-bottom: 20px;
            background-color: #4A148C;
            /* Background color for the link */
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            .plan-details p {
                margin: 5px 0;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once('sidebar.php'); ?>
            <div class="col py-3">
                <!-- Activated Jobs Link -->
                <div class="activated-jobs-link">
                    <a href="plan.php" class="btn w-100 d-flex justify-content-center align-items-center" style="background-color: #4A148C;">
                        <i style="color: #f8f9fa; font-size: 1.5rem;font-weight: bold;" class="bi bi-arrow-left"></i>
                        <span style="color: #f8f9fa; font-size: 1.2rem; font-weight: bold; flex-grow: 1; text-align: center;">Jobs</span>
                    </a>
                </div>

                <div id="plansSection" class="plansSection-container">
                    <div class="row">
                        <!-- Loop through all plans and display each one -->
                        <?php foreach ($plans as $plan): ?>
                        <div class="col-md-6 mb-4">
                            <!-- Separate Product Name Box -->
                            <div class="product-name-box">
                                <?php echo htmlspecialchars($plan['name']); ?>
                            </div>

                            <div class="plan-box">
                                <!-- Left side: Image with Lightbox -->
                                <?php if (!empty($plan['image'])): ?>
                                <a data-lightbox="plan" href="<?php echo htmlspecialchars($plan['image']); ?>" data-title="<?php echo htmlspecialchars($plan['name']); ?>">
                                    <img src="<?php echo htmlspecialchars($plan['image']); ?>" alt="Plan image" title="<?php echo htmlspecialchars($plan['name']); ?>">
                                </a>
                                <?php else: ?>
                                <p>No Image Available</p>
                                <?php endif; ?>

                                <!-- Right side: Details -->
                                <div class="plan-details">
                                    <p>Price: <strong><?php echo '₹' . htmlspecialchars($plan['price']); ?></strong></p>
                                    <p>Daily Earnings: <strong><?php echo '₹' . htmlspecialchars($plan['daily_earnings']); ?></strong></p>
                                    <p>Monthly Earnings: <strong><?php echo '₹' . htmlspecialchars($plan['monthly_earnings']); ?></strong></p>
                                    <p>Daily Codes: <strong><?php echo htmlspecialchars($plan['daily_codes']); ?></strong></p>
                                    <p>Validity: <span class="highlight">Life Time</span></p>

                                    <button class="btn btn-success" <?php echo ($plan['claim'] == '0') ? 'disabled' : ''; ?> onclick="startWork(<?php echo htmlspecialchars($plan['plan_id']); ?>)">
                                        Start Work
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle redirection based on plan_id -->
    <script>
        function startWork(planId) {
            let redirectUrl = "";
            switch (planId) {
                case 1:
                    redirectUrl = "30_days_free_earnings.php";
                    break;
                case 2:
                    redirectUrl = "associate_job.php";
                    break;
                case 3:
                    redirectUrl = "supervisor_job.php";
                    break;
                case 4:
                    redirectUrl = "asst_manager_job.php";
                    break;
                case 5:
                    redirectUrl = "manager_job.php";
                    break;
                default:
                    alert("Invalid Plan ID");
                    return;
            }
            window.location.href = redirectUrl;
        }
    </script>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

</body>

</html>