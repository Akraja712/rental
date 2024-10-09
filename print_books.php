<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id']; // Get user_id from session

$servername = "localhost";
$username = "u743445510_jiyo";
$password = "Jiyo@2024";  
$dbname = "u743445510_jiyo"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all records from the books table
$sql = "SELECT customer_name, book_name, author_name, book_id FROM books";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
} else {
    echo "No records found.";
    exit();
}

// Select a random record for display
$randomIndex = array_rand($books);
$randomBook = $books[$randomIndex];

$randomCustomerName = trim($randomBook['customer_name']);
$randomBookName = trim($randomBook['book_name']);
$randomAuthorName = trim($randomBook['author_name']);
$randomBookId = trim($randomBook['book_id']);

// Check if it's an AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
    $customerName = trim($conn->real_escape_string($_POST['customer_name']));
    $bookName = trim($conn->real_escape_string($_POST['book_name']));
    $authorName = trim($conn->real_escape_string($_POST['author_name']));
    $bookId = trim($conn->real_escape_string($_POST['book_id']));

   // Validate each field against all records
// Validate each field against all records
$errors = [];
$matchFound = false;

foreach ($books as $book) {
    // Check if all fields match
    if (strcasecmp($customerName, trim($book['customer_name'])) === 0 && 
        strcasecmp($bookName, trim($book['book_name'])) === 0 && 
        strcasecmp($authorName, trim($book['author_name'])) === 0 && 
        strcasecmp($bookId, trim($book['book_id'])) === 0) {
        $matchFound = true; // A full match is found, no need for errors
        break;
    }
}

// If no match is found, compare each field individually to give specific error messages
if (!$matchFound) {
    // Loop through the records again to determine which fields are incorrect
    foreach ($books as $book) {
        if (strcasecmp($customerName, trim($book['customer_name']))) {
            $errors['customer_name'] = "Customer Name is incorrect.";
        }
        if (strcasecmp($bookName, trim($book['book_name']))) {
            $errors['book_name'] = "Book Name is incorrect.";
        }
        if (strcasecmp($authorName, trim($book['author_name']))) {
            $errors['author_name'] = "Author Name is incorrect.";
        }
        if (strcasecmp($bookId, trim($book['book_id']))) {
            $errors['book_id'] = "Book ID is incorrect.";
        }
    }
}

// If errors exist, return the specific error messages for incorrect fields
if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'errors' => $errors]);
} else {
    // Proceed with the transaction if all fields are correct
    $conn->begin_transaction();

    try {
        // Update user fields
        $sql = "UPDATE users SET print_wallet = print_wallet - 1, balance = balance + 1 WHERE id = $user_id";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to update user fields: ' . $conn->error);
        }

        // Insert transaction
        $sql = "INSERT INTO transactions (user_id, type, amount, datetime) VALUES ($user_id, 'print_books', 1, NOW())";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to insert transaction: ' . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        // Success response with page reload trigger
        echo json_encode(['status' => 'success', 'message' => 'Your book printed successfully!', 'reload' => true]);
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}

$conn->close();
exit();

}

// Initialize user details
include_once('includes/connection.php');
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null; // Ensure user_id is set

if (!$user_id) {
    header("Location: index.php");
    exit();
}

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
            $print_wallet = $userdetails[0]["print_wallet"];
            $balance = $userdetails[0]["balance"];
        } else {
            echo "<script>alert('" . $responseData["message"] . "')</script>";
        }
    } else {
        if ($responseData !== null) {
            echo "<script>alert('" . $responseData["message"] . "')</script>";
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
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/money.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .info-box {
            background-color: #4A148C;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .info-box p {
            font-size: 1.25rem;
            margin: 0;
        }
        .form-container {
            max-width: 500px;
            margin-top: 20px;
        }
        .bankdetails-container {
            position: relative;
            padding: 20px;
        }
        .no-copy {
            user-select: none; /* Disable text selection */
            -webkit-user-select: none; /* Disable for Safari */
            -ms-user-select: none; /* Disable for IE/Edge */
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $("form").on("submit", function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            $.ajax({
                    type: "POST",
                    url: "", // Current page
                    data: $(this).serialize() + "&ajax=1",
                    dataType: "json",
                    success: function (response) {
                        var modalHeader = $("#modalHeader");
                        var modalTitle = $("#modalTitle");
                        var modalMessage = $("#modalMessage");

                        if (response.status === 'success') {
                            modalTitle.text("Success");
                            modalHeader.removeClass('bg-danger').addClass('bg-success');
                            modalMessage.html(response.message);
                            $("form")[0].reset();

                            // Reload page on success after 2 seconds
                            if (response.reload) {
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            }
                        } else {
                            modalTitle.text("Error");
                            modalHeader.removeClass('bg-success').addClass('bg-danger');
                            var errorMessage = '';
                            if (response.errors) {
                                $.each(response.errors, function (key, value) {
                                    errorMessage += value + '<br>';
                                });
                            } else {
                                errorMessage = "An error occurred. Please try again.";
                            }
                            modalMessage.html(errorMessage);
                        }

                        $("#responseModal").modal('show');
                    },
                    error: function () {
                        $("#modalTitle").text("Error");
                        $("#modalMessage").text("Something went wrong. Please try again.");
                        $("#modalHeader").removeClass('bg-success').addClass('bg-danger');
                        $("#responseModal").modal('show');
                    }
                });

        });
    });
    </script>
</head>
<body>

<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div id="bankdetails" class="bankdetails-container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box" style="background-color: #BF360C; color: white;">
                            <h4>Print Wallet</h4>  <p>₹<?php echo $print_wallet; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box" style="background-color: #F9A825; color: white;">
                            <h4>Balance</h4>  <p>₹<?php echo $balance; ?></p>
                        </div>
                    </div>
                </div>

                <h2 style="text-decoration: underline;">Print Books </h2>

                <!-- Modern Card Layout for Balance and Print Wallet -->
                <!-- Book Print Form -->
                <div class="form-container mt-4">
                    <form method="post">
                        <div class="mb-3">
                            <p class="no-copy"><?php echo htmlspecialchars($randomCustomerName); ?></p>
                            <input type="text" id="customer_name" name="customer_name" placeholder="Customer Name"
                                   class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <p class="no-copy"><?php echo htmlspecialchars($randomBookName); ?></p>
                            <input type="text" id="book_name" name="book_name" placeholder="Book Name"
                                   class="form-control" required/>
                        </div>
                        <div class="mb-3">
                            <p class="no-copy"><?php echo htmlspecialchars($randomAuthorName); ?></p>
                            <input type="text" id="author_name" name="author_name" placeholder="Author Name"
                                   class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <p class="no-copy"><?php echo htmlspecialchars($randomBookId); ?></p>
                            <input type="text" id="book_id" name="book_id" placeholder="Book ID"
                                   class="form-control" required />
                        </div>

                        <button type="submit" name="btnUpdate" style="background-color:#4A148C; color:white;" class="btn">Print Book</button>
                    </form>
                </div>

                <!-- Bootstrap Modal -->
                <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div id="modalHeader" class="modal-header bg-success">
                                <h5 id="modalTitle" class="modal-title">Success</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p id="modalMessage">Your book printed successfully!</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End of Modal -->
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
