<?php
include_once('includes/connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

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
    echo "Error: " . curl_error($curl);
} else {
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        $users = $responseData["data"];
        if (!empty($users)) {
            $name = $users[0]["name"];
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bottom Navigation Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
      /* General reset for cross-browser consistency */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body padding to avoid content being hidden under the bottom navigation */
body {
    padding-bottom: 60px; /* Space for bottom navigation */
    font-family: Arial, sans-serif;
}

/* Bottom navigation bar styles */
.bottom-nav {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #4A148C;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px 0;
    z-index: 1000;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

/* Navigation links */
.bottom-nav .nav-link {
    text-align: center;
    color: white;
    font-size: 14px;
    transition: color 0.3s ease, transform 0.3s ease; /* Smooth hover transition */
}

/* Icon styles */
.bottom-nav .nav-link i {
    display: block;
    font-size: 1.5rem;
    margin-bottom: 2px; /* Space between icon and text */
    transition: color 0.3s ease, transform 0.3s ease; /* Smooth hover transition */
}

/* Label under the icons */
.bottom-nav .nav-link span {
    font-size: 0.8rem;
    transition: color 0.3s ease; /* Smooth hover transition */
}

/* Hover effect for navigation links */
.bottom-nav .nav-link:hover {
    color: #FFD700; /* Change color on hover */
    transform: scale(1.1); /* Slight zoom on hover */
}

/* Hover effect for icons */
.bottom-nav .nav-link:hover i {
    color: #FFD700; /* Change icon color on hover */
    transform: scale(1.2); /* Slight zoom for icon */
}

/* Ensure the navigation bar is shown across all screen sizes */
@media (min-width: 768px) {
    .bottom-nav {
        display: flex;
    }
}

    </style>
</head>
<body>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="dashboard.php" class="nav-link">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="plan.php" class="nav-link">
            <i class="bi bi-file-earmark-text"></i>
            <span>Plans</span>
        </a>
        <a href="level_1.php" class="nav-link">
            <i class="bi bi-grid"></i>
            <span>My Referrals</span>
        </a>
        <a href="menu.php" class="nav-link">
           <i class="bi bi-gear-fill"></i>
            <span>More</span>
        </a>
        
    </div>

    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
