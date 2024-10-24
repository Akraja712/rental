
<?php
include_once('includes/connection.php');

session_start(); // Start session if not already started

if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit(); // It's a good practice to exit after redirecting
}

$user_id = $_SESSION['id']; // Use the actual user_id
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
        // Display user details
        $bankdetails = $responseData["data"];
   
        if (!empty($bankdetails)) {
            $name = $bankdetails[0]["name"];
            $mobile = $bankdetails[0]["mobile"];
            $email = $bankdetails[0]["email"];
            $password = $bankdetails[0]["password"];
            $city = $bankdetails[0]["city"];
            $state = $bankdetails[0]["state"];
            $age = $bankdetails[0]["age"];
        } else {
            echo "No users found.";
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
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        .profile-header h2 {
            font-size: 2rem;
            margin: 0;
        }
        .form-label {
            font-weight: bold;
            color: #495057;
        }
        .form-control:read-only {
            background-color: #e9ecef;
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
                    <h2>User Profile</h2>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label"><i class="bi bi-person-fill"></i> Name</label>
                            <input type="text" id="name" name="name" class="form-control" readonly value="<?php echo htmlspecialchars($name); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label"><i class="bi bi-phone-fill"></i> Mobile</label>
                            <input type="text" id="mobile" name="mobile" class="form-control" readonly value="<?php echo htmlspecialchars($mobile); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="bi bi-envelope-fill"></i> Email</label>
                            <input type="email" id="email" name="email" class="form-control" readonly value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label"><i class="bi bi-person-fill"></i> Age</label>
                            <input type="text" id="age" name="age" class="form-control" readonly value="<?php echo htmlspecialchars($age); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="bi bi-lock-fill"></i> Password</label>
                            <input type="text" id="password" name="password" class="form-control" readonly value="<?php echo htmlspecialchars($password); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label"><i class="bi bi-building-fill"></i>  City</label>
                            <input type="text" id="city" name="city" class="form-control" readonly value="<?php echo htmlspecialchars($city); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label"><i class="bi bi-geo-alt-fill"></i>  State</label>
                            <input type="text" id="state" name="state" class="form-control" readonly value="<?php echo htmlspecialchars($state); ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
