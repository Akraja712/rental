<?php
include_once('includes/connection.php'); 
session_start();

// Check if the user is logged in
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!$user_id) {
    header("Location: index.php");
    exit();
}
$plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : null;

// Fetch the plan list
$data = ["user_id" => $user_id];
$apiUrl = API_URL . "user_plan_list.php"; 
$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

// Error handling for curl
if ($response === false) {
    echo "Error: " . curl_error($curl);
    $plans = [];
} else {
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        $plans = $responseData["data"];
    } else {
        if ($responseData !== null) {
            echo "<script>alert('" . $responseData["message"] . "')</script>";
        }
        $plans = [];
    }
}

// Initialize selected_plan
$selected_plan = null;

// Check if plan_id is valid and select the plan
if ($plan_id && !empty($plans)) {
    foreach ($plans as $plan) {
        if ($plan['plan_id'] === $plan_id) {
            $selected_plan = $plan;
            break;
        }
    }
}

// Initialize session progress counter for the selected plan
if (!isset($_SESSION['submission_count'])) {
    $_SESSION['submission_count'] = [];
}
if ($selected_plan && !isset($_SESSION['submission_count'][$plan_id])) {
    $_SESSION['submission_count'][$plan_id] = 0;  
}

// Set maximum submission count
$max_submission_count = 50;

// Define claim_button_enabled based on submission count
$claim_button_enabled = ($selected_plan && $_SESSION['submission_count'][$plan_id] >= $max_submission_count);

// Initialize `store_data` session variable for this plan
if (!isset($_SESSION['store_data'])) {
    $_SESSION['store_data'] = [];
}
if ($selected_plan && !isset($_SESSION['store_data'][$plan_id])) {
    $_SESSION['store_data'][$plan_id] = [
        'store_code' => strval(rand(100000, 999999)), // Generate Store Code
        'invoice_number' => strval(rand(1000000000, 9999999999)), // Generate Invoice Number
        'invoice_date' => date('Y-m-d', strtotime("+" . rand(0, 30) . " days")), // Generate Invoice Date
        'qty' => rand(1, 100), // Generate Qty
    ];
}

// Retrieve stored session values for this plan
$stored_data = $selected_plan ? $_SESSION['store_data'][$plan_id] : null;
if ($stored_data) {
    $stored_store_code = $stored_data['store_code'];
    $stored_invoice_number = $stored_data['invoice_number'];
    $stored_invoice_date = $stored_data['invoice_date'];
    $stored_qty = $stored_data['qty'];
} else {
    // Set default values to avoid "undefined variable" warnings
    $stored_store_code = '';
    $stored_invoice_number = '';
    $stored_invoice_date = '';
    $stored_qty = '';
}

// Initialize form validation errors
$errors = [
    "plan_id" => "",
    "store_code" => "",
    "invoice_number" => "",
    "invoice_date" => "",
    "qty" => ""
];

// Check for form submission
if (isset($_POST['btnNext'])) {
    // Capture form values from the user input
    $store_code = isset($_POST['store_code']) ? $_POST['store_code'] : null;
    $invoice_number = isset($_POST['invoice_number']) ? $_POST['invoice_number'] : null;
    $invoice_date = isset($_POST['invoice_date']) ? $_POST['invoice_date'] : null;
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : null;

    // Validate inputs
    if (empty($store_code)) {
        $errors['store_code'] = "Store Code is required.";
    } elseif ($store_code !== $stored_store_code) {
        $errors['store_code'] = "Incorrect Store Code.";
    }

    if (empty($invoice_number)) {
        $errors['invoice_number'] = "Invoice Number is required.";
    } elseif ($invoice_number !== $stored_invoice_number) {
        $errors['invoice_number'] = "Incorrect Invoice Number.";
    }

    if (empty($invoice_date)) {
        $errors['invoice_date'] = "Invoice Date is required.";
    } elseif ($invoice_date !== $stored_invoice_date) {
        $errors['invoice_date'] = "Incorrect Invoice Date.";
    }

    if (empty($qty) || $qty < 1) {
        $errors['qty'] = "Qty Dispatching is required and should be greater than 0.";
    } elseif ($qty !== $stored_qty) {
        $errors['qty'] = "Incorrect Qty.";
    }

    // If no validation errors, process the form submission
    if (!array_filter($errors)) {
        $_SESSION['submission_count'][$plan_id]++; // Increment the count for this plan

        // Check if the maximum submission count is reached
        if ($_SESSION['submission_count'][$plan_id] >= $max_submission_count) {
            // Prepare data for API call to claim.php
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

            if ($response === false) {
                echo "Error: " . curl_error($curl);
            } else {
                $responseData = json_decode($response, true);
                if ($responseData !== null && isset($responseData["success"]) && $responseData["success"]) {
                    $message = $responseData["message"];
                    if (isset($responseData["balance"])) {
                        $_SESSION['balance'] = $responseData['balance'];  
                    }
                    // Show an alert with the success message, staying on the same page
                    echo "<script>alert('$message');</script>";
                } else {
                    if ($responseData !== null) {
                        echo "<script>alert('" . $responseData["message"] . "')</script>";
                    }
                }
            }
        }

        // Generate new session values after successful form submission
        if ($selected_plan) {
            $_SESSION['store_data'][$plan_id] = [
                'store_code' => strval(rand(100000, 999999)),
                'invoice_number' => strval(rand(1000000000, 9999999999)),
                'invoice_date' => date('Y-m-d', strtotime("+" . rand(0, 30) . " days")),
                'qty' => rand(1, 100),
            ];
        }

        // Do not redirect here; stay on the same page
        // Instead, update the stored values to be displayed again
        $stored_data = $_SESSION['store_data'][$plan_id]; // Fetch updated data from session
        $stored_store_code = $stored_data['store_code'];
        $stored_invoice_number = $stored_data['invoice_number'];
        $stored_invoice_date = $stored_data['invoice_date'];
        $stored_qty = $stored_data['qty'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/money.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
.plan-box {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 20px; /* Padding to keep height */
    margin: 0 auto 20px auto; /* Center the box with auto left and right margins */
    width: 80%; /* Set the desired width */
    max-width: 600px; /* Optional: set a max width */
}

.product-name-box {
    background-color: #4A148C;
    color: white;
    padding: 15px; /* Padding for product name box */
    text-align: center;
    font-size: 1.2rem; /* Keep font size */
    font-weight: bold;
    border-radius: 5px;
    margin-bottom: 15px; /* Bottom margin */
}

.highlighted-value {
    background-color: #fff8c6;
    font-weight: bold;
    padding: 2px 5px; /* Padding for highlighted value */
    border-radius: 5px;
}
.custom-btn {
    background-color: #4A148C; /* Custom background color */
    border-color: #4A148C; /* Match the border color with background */
    color: white; /* Set text color to white for contrast */
}

.custom-btn:hover {
    background-color: #6A1B9A; /* Optional: Darken on hover for better UX */
    border-color: #6A1B9A; /* Match the border on hover */
}

    </style>
</head>
<body>
<?php include_once('sidebar.php'); ?>
    <div class="container mt-5">

        <?php if ($selected_plan): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="plan-box">
                        <div class="product-name-box"><?php echo htmlspecialchars($selected_plan['name']); ?></div>
                     
                        <!-- Submission Count Display -->
                        <div class="mb-3">
                            <h5>Submission Count: <span class="highlighted-value"><?php echo $_SESSION['submission_count'][$plan_id]; ?>/<?php echo $max_submission_count; ?></span></h5>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mt-3">
                            <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($selected_plan['plan_id']); ?>">

                            <div class="mb-3">
                                <label for="store_code" class="form-label">Store Code:</label><span class="highlighted-value"><?php echo $stored_store_code; ?></span>
                                <input type="text" name="store_code" class="form-control" id="store_code" placeholder="Enter Store Code">
                                <span class="text-danger"><?php echo $errors['store_code']; ?></span>
                            </div>

                            <div class="mb-3">
                                <label for="invoice_number" class="form-label">Invoice Number</label><span class="highlighted-value"><?php echo $stored_invoice_number; ?></span>
                                <input type="text" name="invoice_number" class="form-control" id="invoice_number" placeholder="Enter Invoice Number">
                                <span class="text-danger"><?php echo $errors['invoice_number']; ?></span>
                            </div>

                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date</label><span class="highlighted-value"><?php echo $stored_invoice_date; ?></span>
                                <input type="date" name="invoice_date" class="form-control" id="invoice_date">
                                <span class="text-danger"><?php echo $errors['invoice_date']; ?></span>
                            </div>

                            <div class="mb-3">
                                <label for="qty" class="form-label">Quantity:</label>
                                <span class="highlighted-value"><?php echo $stored_qty; ?></span>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">-</button>
                                    <input type="number" name="qty" class="form-control" id="qty" value="1" min="1" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">+</button>
                                    <span class="text-danger"><?php echo $errors['qty'] ?? ''; ?></span>
                                </div>
                            </div>

                            <button type="submit" name="btnNext" class="btn custom-btn" <?php echo $claim_button_enabled ? 'disabled' : ''; ?>>
                                Next
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center">No plan found.</p>
        <?php endif; ?>
    </div>

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