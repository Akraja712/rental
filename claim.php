<?php
include_once('includes/connection.php'); 
session_start();

// Check if the user is logged in
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if (!$user_id) {
    header("Location: index.php");
    exit();
}

// Fetch the plan list
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

// Initialize session progress counter if it doesn't exist
if (!isset($_SESSION['submission_count'])) {
    $_SESSION['submission_count'] = 0;  
}

// Set maximum submission count
$max_submission_count = 50;

// Define claim_button_enabled based on submission count
$claim_button_enabled = ($_SESSION['submission_count'] >= $max_submission_count);

// Generate default values dynamically
$default_store_code = strval(rand(100000, 999999)); 
$default_invoice_number = strval(rand(1000000000, 9999999999));
$default_invoice_date = date('Y-m-d', strtotime("+".rand(0, 30)." days")); 
$default_qty = rand(1, 100); 

// Initialize form values
$store_code = $default_store_code;
$invoice_number = $default_invoice_number;
$invoice_date = $default_invoice_date;
$qty = $default_qty;

$errors = [
    "plan_id" => "",
    "store_code" => "",
    "invoice_number" => "",
    "invoice_date" => "",
    "qty" => ""
];

// Check for form submission
if (isset($_POST['btnNext'])) {
    $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : null;

    if (!$plan_id) {
        $errors['plan_id'] = "Plan ID is missing.";
    }

    // Validate inputs
    if (empty($store_code)) {
        $errors['store_code'] = "Store Code is required.";
    }

    if (empty($invoice_number)) {
        $errors['invoice_number'] = "Invoice Number is required.";
    }

    if (empty($invoice_date)) {
        $errors['invoice_date'] = "Invoice Date is required.";
    }

    if (empty($qty) || $qty < 1) {
        $errors['qty'] = "Qty Dispatching is required and should be greater than 0.";
    }

    // If no errors, process the submission
    if (!array_filter($errors)) {
        $_SESSION['submission_count']++; 

        if ($_SESSION['submission_count'] >= $max_submission_count) {
            // Prepare data for API call to claim.php
            $data = array(
                "plan_id" => $plan_id,
                "user_id" => $user_id,
                "store_code" => $store_code,
                "qty" => $qty,
                "invoice_number" => $invoice_number,
                "invoice_date" => $invoice_date
            );

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
                    echo "<script>alert('$message'); window.location.href = 'my_plans.php';</script>";
                    exit(); 
                } else {
                    if ($responseData !== null) {
                        echo "<script>alert('" . $responseData["message"] . "')</script>";
                    }
                }
            }
        }
    
  // **REDIRECT HERE** to prevent resubmission on refresh
  header("Location: " . $_SERVER['PHP_SELF']);
  exit(); // Stop further script execution
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
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .plan-details {
            flex-grow: 1;
        }

        .quantity-btn {
            cursor: pointer;
            padding: 5px 10px;
            font-size: 18px;
            font-weight: bold;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
        }

        .product-name-box {
            background-color: #6f42c1;
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

        .no-copy {
            user-select: none;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .product-name-box {
                font-size: 1rem;
            }

            .plan-box {
                padding: 15px;
            }

            .quantity-btn {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div id="plansSection" class="plansSection-container">
                <div class="row">
                    <?php foreach ($plans as $plan): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="product-name-box">
                                <?php echo htmlspecialchars($plan['products']); ?>
                            </div>

                            <div class="plan-box">
                                <div class="plan-details">
                                    <form method="post" action="">

                                        <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($plan['plan_id']); ?>">

                                        <p>Progress: <?php echo $_SESSION['submission_count'] . '/' . $max_submission_count; ?></p>

                                        <div class="mb-3">
                                            <label class="no-copy">Store Code: <span class="highlighted-value"><?php echo htmlspecialchars ($default_store_code); ?></span></label>
                                            <input type="text" class="form-control" name="store_code" required>
                                            <span class="text-danger"><?php echo $errors['store_code']; ?></span>
                                        </div>

                                        <div class="mb-3">
                                            <label class="no-copy">Invoice Number: <span class="highlighted-value"><?php echo htmlspecialchars ($default_invoice_number); ?></span></label>
                                            <input type="text" class="form-control" name="invoice_number" required >
                                            <span class="text-danger"><?php echo $errors['invoice_number']; ?></span>
                                        </div>

                                        <div class="mb-3">
                                            <label class="no-copy">Invoice Date: <span class="highlighted-value"><?php echo htmlspecialchars ($default_invoice_date); ?></span></label>
                                            <input type="date" class="form-control" name="invoice_date" required>
                                            <span class="text-danger"><?php echo $errors['invoice_date']; ?></span>
                                        </div>

                                        <div class="mb-3">
                                            <label class="no-copy">Qty Dispatching: <span class="highlighted-value" ><?php echo htmlspecialchars($qty); ?></span></label>
                                            <div class="d-flex align-items-center">
                                                <button type="button" class="quantity-btn" onclick="decrementQty(this)">-</button>
                                                <input type="number" class="form-control quantity-input" name="qty" id="qtyInput" required min="1">
                                                <button type="button" class="quantity-btn" onclick="incrementQty(this)">+</button>
                                            </div>
                                            <span class="text-danger"><?php echo $errors['qty']; ?></span>
                                        </div>

                                        <?php if ($_SESSION['submission_count'] < $max_submission_count): ?>
                                            <button type="submit" name="btnNext" class="btn btn-primary mt-2">
                                                Next
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($claim_button_enabled): ?>
                                            <button type="submit" name="btnIncome" class="btn btn-success mt-2">
                                                Claim
                                            </button>
                                        <?php endif; ?>
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
// Increment quantity
function incrementQty(element) {
    let input = element.previousElementSibling; // Target the input field
    let currentValue = parseInt(input.value); 
    currentValue = isNaN(currentValue) ? 0 : currentValue; 
    input.value = currentValue + 1; 
    document.getElementById('qtyDisplay').innerText = input.value; 
}

// Decrement quantity
function decrementQty(element) {
    let input = element.nextElementSibling; 
    let currentValue = parseInt(input.value); 
    if (!isNaN(currentValue) && currentValue > 1) {
        input.value = currentValue - 1;
        document.getElementById('qtyDisplay').innerText = input.value;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
