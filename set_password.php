<?php
include_once('includes/connection.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

if (isset($_POST['btnUpdate'])) {
    // Gather and sanitize user input data
    $password = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : "";
    $confirm_password = isset($_POST["confirm_password"]) ? htmlspecialchars($_POST["confirm_password"]) : "";

    // Basic validation for passwords
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit();
    }

    // Prepare data to be sent to the API
    $data = array(
        "user_id" => $user_id,
        "password" => $password,
    );

    $apiUrl = API_URL . "change_password.php"; 

    // Initialize cURL session
    $curl = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // Execute the cURL request
    $response = curl_exec($curl);

    if ($response === false) {
        // Error in cURL request
        echo "Error: " . curl_error($curl);
    } else {
        // Decode the JSON response from the API
        $responseData = json_decode($response, true);
        if ($responseData !== null && isset($responseData["success"])) {
            $message = $responseData["message"];
            echo "<script>alert('$message');</script>";
        } else {
            // Handle API response error
            if ($responseData !== null) {
                echo "<script>alert('".$responseData["message"]."')</script>";
            } else {
                echo "Unexpected error.";
            }
        }
    }

    // Close cURL session
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
        .withdrawal-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .form-container {
            max-width: 500px;
            position: relative;
        }
        .form-container .eye-icon {
            position: absolute;
            top: 70%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .form-container .form-control {
            padding-right: 40px;
        }
        .setpassword-container {
            position: relative; 
            padding: 20px; 
        }
        .btn{
             background-color:#4A148C; 
            border-color: #4A148C; 
            color: white; 
            font-weight: 600;
            border-radius: 99999px;
            margin-left: 20px;
           
        }
        .btn:hover{
            color:white;
            background-color: #4A148C;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
    <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div class="d-flex justify-content-between align-items-center mb-3">  
                    <a href="menu.php" style="color:white;" class="btn"><i style="color:rgb(248, 248, 248); font-size: 1rem;" class="bi bi-arrow-left"></i>Back</a>   
                </div>
        <div id="setpassword" class="setpassword-container">
            <h2>Set Password</h2>
            
            <!-- Withdrawal Request Form -->
            <div class="form-container mt-4">
            <form action="set_password.php" method="post">
                    <div class="mb-3 position-relative">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="password" required>
                        <i class="bi bi-eye eye-icon" id="togglePassword"></i>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                        <i class="bi bi-eye eye-icon" id="toggleConfirmPassword"></i>
                    </div>
                    <button type="submit" name="btnUpdate"  style="background-color:#4A148C; color:white;" class="btn">Set</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const newPassword = document.getElementById('newPassword');

        togglePassword.addEventListener('click', function () {
            const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            newPassword.setAttribute('type', type);
            togglePassword.classList.toggle('bi-eye-slash');
        });

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('bi-eye-slash');
        });
    });
</script>
</body>
</html>