<?php
include_once('includes/connection.php'); 
session_start();

// Check if the user is logged in
$user_id = $_SESSION['id'] ?? null;
if (!$user_id) {
    header("Location: index.php");
    exit();
}

$max_submission_count = 2; // Set your desired max count

// Initialize submission count on first load
if (!isset($_SESSION['trial_submission_count'])) {
    $_SESSION['trial_submission_count'] = 0;
}

// Initialize error messages
$errors = [];

// Check if random values are already set for the plan, if not, generate new ones
if (!isset($_SESSION['trial_plan_data'])) {
    $_SESSION['trial_plan_data'] = [];
}

// Check for form submission
if (isset($_POST['btnNext'])) {
    // Capture form values from the user input
    $submitted_store_code = $_POST['store_code'] ?? '';
    $submitted_invoice_number = $_POST['invoice_number'] ?? '';
    $submitted_invoice_date = $_POST['invoice_date'] ?? '';
    $submitted_qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1; // default to 1 if not set

    // Validate inputs against session values
    if ($submitted_store_code !== $_SESSION['store_code']) {
        $errors['store_code'] = "Store Code is incorrect.";
    } 

    if ($submitted_invoice_number !== $_SESSION['invoice_number']) {
        $errors['invoice_number'] = "Invoice Number is incorrect.";
    } 

    if ($submitted_invoice_date !== $_SESSION['invoice_date']) {
        $errors['invoice_date'] = "Invoice Date is incorrect.";
    }

    if ($submitted_qty !== $_SESSION['qty']) {
        $errors['qty'] = "Quantity is incorrect.";
    } 

    // If no validation errors, process the form submission
    if (empty($errors)) {
        $_SESSION['trial_submission_count']++; // Increment the trial count

        // Check if the maximum trial count is reached
        if ($_SESSION['trial_submission_count'] >= $max_submission_count) {
            // Reset submission count before redirecting to plan.php
            $_SESSION['trial_submission_count'] = 0;
            header("Location: plan.php");
            exit();
        }

        // Generate new random values for next submission
        generateNewValues();
    }
} else {
    // Ensure new values are generated upon each new form load
    generateNewValues();
}

// Function to generate new random values
function generateNewValues() {
    $_SESSION['store_code'] = strval(rand(100000, 999999));
    $_SESSION['invoice_number'] = strval(rand(1000000000, 9999999999));
    $_SESSION['invoice_date'] = date('Y-m-d', strtotime("+" . rand(0, 30) . " days"));
    $_SESSION['qty'] = rand(1, 100);
}
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
            border-color: #6A148C;
        }
    </style>
</head>
<body>
<?php include_once('sidebar.php'); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="plan-box">
                    <!-- Submission Count Display -->
                    <div class="mb-3">
                        <h5>Submission Count: <span class="highlighted-value"><?php echo $_SESSION['trial_submission_count']; ?>/<?php echo $max_submission_count; ?></span></h5>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mt-3">
                        <div class="mb-3">
                            <label for="store_code" class="form-label">Store Code:</label>
                            <span class="highlighted-value"><?php echo htmlspecialchars($_SESSION['store_code']); ?></span>
                            <input type="text" name="store_code" class="form-control" id="store_code" placeholder="Enter Store Code">
                            <span class="text-danger"><?php echo $errors['store_code'] ?? ''; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">Invoice Number:</label>
                            <span class="highlighted-value"><?php echo htmlspecialchars($_SESSION['invoice_number']); ?></span>
                            <input type="text" name="invoice_number" class="form-control" id="invoice_number" placeholder="Enter Invoice Number">
                            <span class="text-danger"><?php echo $errors['invoice_number'] ?? ''; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date:</label>
                            <span class="highlighted-value"><?php echo htmlspecialchars($_SESSION['invoice_date']); ?></span>
                            <input type="date" name="invoice_date" class="form-control" id="invoice_date">
                            <span class="text-danger"><?php echo $errors['invoice_date'] ?? ''; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="qty" class="form-label">Quantity:</label>
                            <span class="highlighted-value"><?php echo htmlspecialchars($_SESSION['qty']); ?></span>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">-</button>
                                <input type="number" name="qty" class="form-control" id="qty" value="<?php echo htmlspecialchars($_SESSION['qty']); ?>" min="1" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">+</button>
                                <span class="text-danger"><?php echo $errors['qty'] ?? ''; ?></span>
                            </div>
                        </div>
                        <button type="submit" name="btnNext" class="btn custom-btn" <?php echo ($_SESSION['trial_submission_count'] >= $max_submission_count) ? 'disabled' : ''; ?>>
                            Next
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function incrementQty() {
            var qtyInput = document.getElementById("qty");
            var currentValue = parseInt(qtyInput.value);
            qtyInput.value = currentValue + 1;
        }
        function decrementQty() {
            var qtyInput = document.getElementById("qty");
            var currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }
    </script>
</body>
</html>
