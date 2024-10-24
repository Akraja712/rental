<?php
include_once('includes/connection.php');
session_start();

if (!isset($_SESSION['id'])) {
  header("location:index.php");
}

$user_id = $_SESSION['id']; // Replace with the actual user_id
$data = array(
    "user_id" => $user_id,
);


if (isset($_POST['btnUpdate'])) {
    // Gather user input data and sanitize it
    $account_num = isset($_POST["account_num"]) ? htmlspecialchars($_POST["account_num"]) : "";
    $holder_name = isset($_POST["holder_name"]) ? htmlspecialchars($_POST["holder_name"]) : "";
    $bank = isset($_POST["bank"]) ? htmlspecialchars($_POST["bank"]) : "";
    $branch = isset($_POST["branch"]) ? htmlspecialchars($_POST["branch"]) : "";
    $ifsc = isset($_POST["ifsc"]) ? htmlspecialchars($_POST["ifsc"]) : "";

    // Prepare data to be sent to the API
    $data = array(
        "user_id" => $user_id,
        "account_num" => $account_num,
        "holder_name" => $holder_name,
        "bank" => $bank,
        "branch" => $branch,
        "ifsc" => $ifsc,
    );

   
    $apiUrl = API_URL."update_bank_details.php"; 
    // Initialize cURL session
    $curl = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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
            if(isset($responseData["balance"])){
              $_SESSION['balance'] = $responseData['balance'];
            $balance = $_SESSION['balance'] ;
    
            }
            
            echo "<script>alert('$message');</script>";
    
        } else {
            echo "<script>alert('".$responseData["message"]."')</script>";
        }
    }

    // Close cURL session
    curl_close($curl);
}
?>

<?php


if (!isset($_SESSION['id'])) {
  header("location:index.php");
}

$user_id = $_SESSION['id']; // Replace with the actual user_id
$data = array(
    "user_id" => $user_id,
);

$apiUrl = API_URL."bank_details.php";


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
        $bankdetails = $responseData["data"];
   
        if (!empty($bankdetails)) {
            $account_num = $bankdetails[0]["account_num"];
            $holder_name = $bankdetails[0]["holder_name"];
            $bank = $bankdetails[0]["bank"];
            $branch = $bankdetails[0]["branch"];
            $ifsc = $bankdetails[0]["ifsc"];
        } else {
            echo "No transactions found.";
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
        }
        .bankdetails-container {
            position: relative; 
            padding: 20px; 
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
    <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
        <div id="bankdetails" class="bankdetails-container">
            <h2>Bank Details</h2>
            <!-- Withdrawal Request Form -->
            <div class="form-container mt-4">
                <form action="bank_details.php" method="post">
                    <div class="mb-3">
                        <label for="withdrawalAmount" class="form-label"></label>
                        <input type="text" id="holder_name" name="holder_name" placeholder="account_holder_name" class="form-control" required value="<?php echo isset($holder_name) ? htmlspecialchars($holder_name) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="withdrawalNote" class="form-label"></label>
                        <input type="text" id="account_num" name="account_num" placeholder="account_num" class="form-control" required value="<?php echo isset($account_num) ? htmlspecialchars($account_num) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="withdrawalAmount" class="form-label"></label>
                        <input type="text" id="bank" name="bank" placeholder="bank" class="form-control" required value="<?php echo isset($bank) ? htmlspecialchars($bank) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="withdrawalNote" class="form-label"></label>
                        <input type="text" id="branch" name="branch" placeholder="branch" class="form-control" required value="<?php echo isset($branch) ? htmlspecialchars($branch) : ''; ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="withdrawalAmount" class="form-label"></label>
                        <input type="text" id="ifsc" name="ifsc" placeholder="ifsc" class="form-control" required value="<?php echo isset($ifsc) ? htmlspecialchars($ifsc) : ''; ?>" />
                    </div>
                    <button type="submit" name="btnUpdate"   style="background-color:#4A148C; color:white;" class="btn">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
