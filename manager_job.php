<?php
include_once('includes/connection.php'); 
session_start();

// Get the user ID from the session, redirect if not set
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
if (!$user_id) {
    header("Location: index.php");
    exit();
}

// Set the default plan_id to 1 if not set
$plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : 5;



// Fetch the plan list using API
$data = ["user_id" => $user_id];
$apiUrl = API_URL . "user_plan_list.php"; 
$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);
curl_close($curl);

$plans = [];
$plan_name = ''; // Initialize plan_name variable
if ($response !== false) {
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        $plans = $responseData["data"];
        
        // Find the plan name for the current plan_id
        foreach ($plans as $plan) {
            if ($plan['plan_id'] == $plan_id) {
                $plan_name = $plan['name']; // Assign to plan_name for display
                break;
            }
        }
    } else {
        if ($responseData !== null) {
            echo "<script>alert('" . $responseData["message"] . "')</script>";
        }
    }
}

// Set custom maximum submission count for plan_id 1, otherwise default to 50
$max_submission_count_8 = 50;

// Initialize session data if not set
if (!isset($_SESSION['store_data'])) {
    $_SESSION['store_data'] = [];
}

// Resetting session data on refresh and generating new data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $_SESSION['store_data'][$plan_id] = [
            'store_code' => strval(rand(100000, 999999)),
           'payment_date' => date('Y-m-d', strtotime("+" . rand(0, 30) . " days")),
           'invoice_date' => date('Y-m-d', strtotime("+" . rand(0, 365) . " days")), // New random expiry date
           'qty' => rand(10, 100), // New random price
       ];
   }
   
   // Access stored data and provide defaults
   $stored_data = $_SESSION['store_data'][$plan_id];
   $stored_store_code = $stored_data['store_code'] ?? '';
   $stored_payment_date = $stored_data['payment_date'] ?? '';
   $stored_invoice_date = $stored_data['invoice_date'] ?? '';
   $stored_qty = $stored_data['qty'] ?? '';
   
   // Initialize error messages
   $errors = [
       "store_code" => "",
       "payment_date" => "",
       "invoice_date" => "",
       "qty" => ""
   ];

// Check if the submission count is set in the session for this plan_id; if not, initialize to 0
if (!isset($_SESSION['submission_count_8'][$plan_id])) {
    $_SESSION['submission_count_8'][$plan_id] = 0;
}

// Check for form submission
if (isset($_POST['btnNext'])) {
    $store_code = $_POST['store_code'] ?? null;
    $payment_date = $_POST['payment_date'] ?? null;
    $invoice_date = $_POST['invoice_date'] ?? '';
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : null;

    if (empty($store_code)) {
        $errors['store_code'] = "Store Code is required.";
    } elseif ($store_code !== $stored_store_code) {
        $errors['store_code'] = "Incorrect Store Code.";
    }

    if (empty($payment_date)) {
        $errors['payment_date'] = "Payment Date is required.";
    } elseif ($payment_date !== $stored_payment_date) {
        $errors['payment_date'] = "Incorrect Payment Date.";
    }

    if (empty($invoice_date)) {
        $errors['invoice_date'] = "Invoice Date is required.";
    } elseif ($invoice_date !== $stored_invoice_date) {
        $errors['invoice_date'] = "Incorrect Invoice Date.";
    }

    if (empty($qty) || $qty < 1) {
        $errors['qty'] = "qty is required and should be greater than 0.";
    } elseif ($qty !== $stored_qty) {
        $errors['qty'] = "Incorrect qty.";
    }

    // Check if there are no validation errors
    if (!array_filter($errors)) {
        $_SESSION['submission_count_8'][$plan_id] = ($_SESSION['submission_count_8'][$plan_id] ?? 0) + 1;

        // Process the submission if max count reached
        if ($_SESSION['submission_count_8'][$plan_id] >= $max_submission_count_8) {
            $data = [
                "plan_id" => $plan_id,
                "user_id" => $user_id,
                "store_code" => $store_code,
                "qty" => $qty,
                "invoice_number" => $invoice_number,
                "invoice_date" => $invoice_date
            ];
        
            $apiUrl = API_URL . "claim.php";
            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
            $response = curl_exec($curl);
            curl_close($curl);
        
            $responseData = json_decode($response, true);
            if ($responseData !== null && isset($responseData["success"]) && $responseData["success"]) {
                $message = $responseData["message"];
                if (isset($responseData["balance"])) {
                    $_SESSION['balance'] = $responseData['balance'];
                }
                echo "<script>alert('$message');</script>";
                echo "<script>window.location.href = 'my_plans.php';</script>";
                exit();
            } else {
                // Log the error if API response is not successful
                error_log("API response error: " . print_r($responseData, true));
            }
        }

            // Regenerate stored data for the next submission
            $_SESSION['store_data'][$plan_id] = [
                'store_code' => strval(rand(100000, 999999)),
                'payment_date' => date('Y-m-d', strtotime("+" . rand(0, 30) . " days")),
                'invoice_date' => date('Y-m-d', strtotime("+" . rand(0, 365) . " days")),
                'qty' => rand(10, 100),
            ];
    
            // Refresh stored data for display
            $stored_data = $_SESSION['store_data'][$plan_id];
            $stored_store_code = $stored_data['store_code'];
            $stored_payment_date = $stored_data['payment_date'];
            $stored_invoice_date = $stored_data['invoice_date'];
            $stored_qty = $stored_data['qty'];
    }
}

// Determine if the claim button should be enabled
$claim_button_enabled = $_SESSION['submission_count_8'][$plan_id] >= $max_submission_count_8;

// Display the submission count
$display_count = $_SESSION['submission_count_8'][$plan_id];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .plan-box {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 20px; 
            margin: 0 auto 20px auto; 
            width: 80%; 
            max-width: 600px; 
        }

        .product-name-box {
            background-color: #4A148C;
            color: white;
            padding: 15px; 
            text-align: center;
            font-size: 1.2rem; 
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 15px; 
        }

        .highlighted-value {
            background-color: #fff8c6;
            font-weight: bold;
            padding: 2px 5px; 
            border-radius: 5px;
        }

        .custom-btn {
            background-color: #4A148C; 
            border-color: #4A148C; 
            color: white; 
        }

        .custom-btn:hover {
            background-color: #6A1B9A; 
            border-color: #6A1B9A; 
        }
    </style>
</head>
<body>
<?php include_once('sidebar.php'); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="plan-box">
                <div class="product-name-box">Manager Job</div>
                    
                    <div class="mb-3">
                        <h5>Submission Count: <span class="highlighted-value"><?php echo $display_count; ?>/<?php echo htmlspecialchars($max_submission_count_8); ?></span></h5>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-3">
                        <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($plan_id); ?>">

                        <div class="mb-3">
                                <label for="store_code" class="form-label">Store Code:</label><span class="highlighted-value"><?php echo $stored_store_code; ?></span>
                                <input type="text" name="store_code" class="form-control" id="store_code" placeholder="Enter Store Code">
                                <span class="text-danger"><?php echo $errors['store_code']; ?></span>
                            </div>

                            <div class="mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date:</label>
                            <span class="highlighted-value"><?php echo htmlspecialchars($stored_invoice_date); ?></span>
                            <input type="date" name="invoice_date" class="form-control" id="invoice_date" required>
                            <span class="text-danger"><?php echo htmlspecialchars($errors['invoice_date']); ?></span>
                        </div>


                        <div class="mb-3">
                                <label for="qty" class="form-label">Qty Sold:</label>
                                <span class="highlighted-value"><?php echo $stored_qty; ?></span>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">-</button>
                                    <input type="number" name="qty" class="form-control" id="qty" value="1" min="1" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">+</button>
                                    <span class="text-danger"><?php echo $errors['qty'] ?? ''; ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date:</label>
                            <span class="highlighted-value"><?php echo htmlspecialchars($stored_payment_date); ?></span>
                            <input type="date" name="payment_date" class="form-control" id="payment_date" required>
                            <span class="text-danger"><?php echo htmlspecialchars($errors['payment_date']); ?></span>
                        </div>

                        <button type="submit" name="btnNext" class="btn custom-btn">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function incrementQty() {
            var qtyInput = document.getElementById("qty");
            var currentValue = parseInt(qtyInput.value) || 0;
            qtyInput.value = currentValue + 1;
        }

        function decrementQty() {
            var qtyInput = document.getElementById("qty");
            var currentValue = parseInt(qtyInput.value) || 0;
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }
    </script>
</body>
</html>
