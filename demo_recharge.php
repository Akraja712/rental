<?php
include_once('includes/connection.php');
session_start();

$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null; // Ensure user_id is set

if (!$user_id) {
    header("Location: index.php");
    exit();
}

$data = []; // Define $data at the top

if (isset($_POST['btnclaim'])) {
    $order_id = $_POST['order_id'];
    $type = isset($_POST['type']) ? $_POST['type'] : 'ebook'; 

    $data = array(
        "user_id" => $user_id,
        "order_id" => $order_id,
        "type" => $type, 
    );
   
  // Updated API URL
  $apiUrl = "https://admin.5penta.store/api/order_claim.php";

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

    if ($response === false) {
        echo "Error: " . curl_error($curl);
    } else {

        $responseData = json_decode($response, true);
        if ($responseData !== null && isset($responseData["success"])) {
            $message = $responseData["message"];
            echo "<script>
                    alert('$message');
                    window.location.href = 'demo_recharge.php';
                  </script>";
        } else {
            if ($responseData !== null) {
                echo "<script>alert('".$responseData["message"]."')</script>";
            } else {
                echo "<script>alert('Unexpected response format.')</script>";
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
            <br>
            <div class="col-md-6">
                <div class="row">
                <form action="demo_recharge.php" method="post">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Enter Order Id</label>
                            <input type="text" class="form-control" id="order_id" name="order_id" required>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="btnclaim" style="background-color:#3eb3a8; color:white;" class="btn">Claim</button>
                    </div>
                </form>
                </div>
            </div>

            <!-- Optional script for dynamic link redirection -->
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
