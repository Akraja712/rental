<?php
include_once('includes/connection.php');
session_start();

// Check if the user is logged in
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!$user_id) {
    header("Location: index.php");
    exit();
}

// Initialize recharge variable
$recharge = 0; // Default value in case no recharge is found

$data = array(
    "user_id" => $user_id,
    "type" => "jobs",
);

$apiUrl = API_URL . "plan_list.php";

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
            echo "<script>alert('".$responseData["message"]."')</script>";
        }
        $plans = [];
    }
}

curl_close($curl);


if (isset($_POST['btnactivate'])) {
    $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : null;

    if (!$plan_id) {
        die("Plan ID not provided.");
    }

    $data = array(
        "plan_id" => $plan_id,
        "user_id" => $user_id,
    );
    $apiUrl = API_URL . "activate_plan.php";

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
            echo "<script>alert('$message');</script>";
        } else {
            // Failed to fetch transaction details
            if ($responseData !== null) {
                echo "<script>alert('".$responseData["message"]."')</script>";
            }
        }
    }
    curl_close($curl);
}

// Fetch user recharge details
$data = array(
    "user_id" => $user_id,
);

$apiUrl = API_URL . "user_details.php";

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
            $recharge = $userdetails[0]["recharge"];
        } else {
            echo "No recharge details found.";
        }
    } else {
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
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
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
        .trail-btn {
    background-color: white;     /* Background color set to white */
    color: #4A148C;              /* Text color set to #4A148C */
    border: 2px solid #4A148C;   /* Border color set to #4A148C */
}
        

        .product-name-box {
            background-color: #4A148C;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: flex; /* Use flexbox for alignment */
            justify-content: space-between; /* Space out children */
            align-items: center; /* Center items vertically */
        }

        .product-name {
            font-size: 0.90rem; /* Size for the product name */
            font-weight: bold; /* Bold for product name */
        }

        .watch-demo-link {
            color: white; /* Change color as needed */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Make it bold */
            padding-left: 10px; /* Add padding for a gap */
            font-size: 0.70rem; /* Size for the product name */
        }

        .watch-demo-link:hover {
            text-decoration: underline; /* Underline on hover for better UX */
        }

        .activated-jobs-link {
    margin-bottom: 20px;
    background-color: #4A148C; /* Background color for the link */
    border-radius: 10px;
}


.alert-info{
    top: 0px; /* Distance from the top */
    left: 935px; /* Distance from the right */ 
    width: 100%; /* Full width */
    max-width: 300px; /* Set a max width */
}
.small-font {
            font-size: 0.8rem; /* Adjust the size as needed */
        }
@media (max-width: 576px) {
    .plan-details p {
        margin: 5px 0;
        font-size: 0.8rem;
    }

    .alert-info {
        width: 60%; /* Adjust width for smaller screens */
        font-size: 0.7rem; /* Slightly smaller font size for better fit */
        top: 0px; /* Distance from the top */
        left: 130px; /* Distance from the right */
    }
}

    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <!-- Recharge Alert positioned above the Activated Jobs Link -->
            <div class="alert alert-info" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#rechargeGuideModal">
                Recharge Value: <strong>₹<?php echo htmlspecialchars($recharge); ?></strong>
            </div>

            <!-- Activated Jobs Link -->
            <div class="activated-jobs-link">
                <a href="my_plans.php" class="btn w-100 d-flex justify-content-between align-items-center">
                    <i style="color: #f8f9fa; font-size: 1.5rem; padding: 10px; font-weight: bold;" class="bi bi-briefcase-fill"></i>  <!-- Left icon (briefcase) -->
                    <span style="color: #f8f9fa; font-size: 0.90rem; padding: 10px; font-weight: bold;">My Activated Jobs</span> <!-- Button Text -->
                    <i style="color: #f8f9fa; font-size: 1.5rem; padding: 10px; font-weight: bold;" class="bi bi-arrow-right"></i> <!-- Right icon (arrow) -->
                </a>
            </div>

            <div id="plansSection" class="plansSection-container">
                <div class="row">
                    <!-- Loop through all plans and display each one -->
                    <?php foreach ($plans as $plan): ?>
                        <div class="col-md-6 mb-4">
                            <span class="product-name-box">
                                <?php echo htmlspecialchars($plan['name']); ?>
                                <a href="<?php echo htmlspecialchars($plan['demo_video']); ?>" target="_blank" class="watch-demo-link">
                                    Watch Demo Video
                                </a>
                            </span>

                            <div class="plan-box">
                                <?php if (!empty($plan['image'])): ?>
                                    <a data-lightbox="plan" href="<?php echo htmlspecialchars($plan['image']); ?>" data-title="<?php echo htmlspecialchars($plan['name']); ?>">
                                        <img src="<?php echo htmlspecialchars($plan['image']); ?>" alt="Plan image" title="<?php echo htmlspecialchars($plan['name']); ?>">
                                    </a>
                                <?php else: ?>
                                    <p>No Image Available</p>
                                <?php endif; ?>

                                <div class="plan-details">
                                    <p>Course Fees: <strong><?php echo '₹' . htmlspecialchars($plan['price']); ?></strong></p>
                                    <p>Daily Earnings: <strong><?php echo '₹' . htmlspecialchars($plan['daily_earnings']); ?></strong></p>
                                    <p>Monthly Earnings: <strong><?php echo '₹' . htmlspecialchars($plan['monthly_earnings']); ?></strong></p>
                                    <p>Daily Codes: <strong><?php echo '' . htmlspecialchars($plan['daily_codes']); ?></strong></p>
                                    <?php if ($plan['id'] != 1): ?>
                                        <p>Validity: <span class="highlight">Life Time</span></p>
                                    <?php else: ?>
                                        <p>Validity: <span class="highlight">30 Days</span></p>
                                    <?php endif; ?>
                                    
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="margin-top: 10px;">
                                        <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($plan['id']); ?>">
                                        <button type="submit" name="btnactivate" class="btn purchase-btn">Purchase</button>
                                        <button type="button" onclick="startWork(<?php echo htmlspecialchars($plan['id']); ?>)" class="btn trail-btn">Take Trial</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
       function startWork(planId) {
    let redirectUrl = "";
    switch (planId) {
        case 1:
            redirectUrl = "30_days_trail.php";
            break;
        case 2:
            redirectUrl = "associate_job_trail.php";
            break;
        case 3:
            redirectUrl = "supervisor_job_trail.php";
            break;
        case 4:
            redirectUrl = "asst_manager_job_trail.php";
            break;
        case 5:
            redirectUrl = "manager_job_trail.php";
            break;
        default:
            alert("Invalid Plan ID");
            return;
    }
    window.location.href = redirectUrl;
}

    </script>
<!-- Recharge Guide Modal -->
<div class="modal fade" id="rechargeGuideModal" tabindex="-1" aria-labelledby="rechargeGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rechargeGuideModalLabel">Recharge Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <center><p>1.Click on the below link & complete the payment.</p></center>
                <center><a href="https://www.jiyologistics.org/product/31855011/Jiyo-Retail-Career-Building-Course" class="btn" style = "background-color: #4A148C; color:#f8f9fa;" target="_blank">Click here for making payment</a></center>
                 <center><a href="https://youtu.be/1elTq_diwjA?si=KW5etuxCu3ChvV41" target="_blank" class="small-font">Watch demo</a></center>
                <a href="demo_video_url" class="watch-demo-link">Watch Demo Video</a>
            </div>
        </div>
    </div>
</div>


    <!-- JavaScript to handle redirection based on plan_id -->
 
<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

</body>
</html>