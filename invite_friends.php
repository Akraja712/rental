
<?php
include_once('includes/connection.php');
session_start();
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

$apiUrl = API_URL."user_details.php";


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
            $refer_code = $userdetails[0]["refer_code"];
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
// Fetch the user's current balance
$apiUrl = API_URL . "settings.php"; // Ensure this endpoint provides the user's balance

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if ($response === false) {
    echo "Error: " . curl_error($curl);
    $telegram_channel = "N/A";
} else {
    $responseData = json_decode($response, true);
    if ($responseData !== null && $responseData["success"]) {
        $details = $responseData["data"];
        if (!empty($details)) {
            $telegram_channel = $details[0]["telegram_channel"];
        } else {
            $telegram_channel = "No telegram_channel information available.";
        }
    } else {
        $telegram_channel = "Failed to fetch telegram_channel.";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        .friends-container {
            position: relative; 
            padding: 20px; 
        }
        .friends-container h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }
        .friends-button {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1rem;
             background-color:#4A148C;
        }
        .button{
            padding:10px;
            background-color:#4A148C;
        }
        .form-container {
            max-width: 400px; 
        }
        @media (max-width: 576px) {
            .friends-container h2 {
                font-size: 0.9rem;
            }
            .friends-button {
                font-size: 0.600rem;
                top: 19px;
                right: 8px;
            }
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
        <div class="friends-container" id="invitefriends">
            <h2>Invite Friends</h2>
            <!-- Withdrawal Request Form -->
            <div class="form-container mt-4">
                <form action="submit_withdrawal_request.php" method="post">
                <div class="mb-3">
                <label for="link" class="form-label">Invite Link</label>
                <input type="text" class="form-control" id="inviteLink" name="link" value="https://jiyoapp.in/register.php?refer_code=<?php echo $refer_code; ?>" disabled>
            </div>
            <button type="button" id="copyButton" style="background-color:#4A148C; color:white;" class="btn">
                <i class="fs-5 bi-copy"></i> Copy Link
            </button>
            <br>
            <!-- <button type="button" id="whatsappButton" style="background-color:#25D366; color:white;" class="btn">
                <i class="fs-5 bi-whatsapp"></i> Join WhatsApp
            </button> -->
             <br>
            <button type="button" id="telegramButton" style="background-color:#3290ec; color:white;" class="btn">
                <i class="fs-5 bi-telegram"></i> Join Telegram
            </button>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var copyButton = document.getElementById('copyButton');
        var inviteLink = document.getElementById('inviteLink');

        copyButton.addEventListener('click', function() {
            // Select the text field
            inviteLink.select();
            inviteLink.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(inviteLink.value)
                .then(function() {
                    // Success message
                    alert('Link copied to clipboard!');
                })
                .catch(function(err) {
                    // Error message
                    console.error('Failed to copy: ', err);
                });
        });

        // var whatsappButton = document.getElementById('whatsappButton');
        // whatsappButton.addEventListener('click', function() {
        //     // Redirect to the WhatsApp channel
        //     var whatsappChannelUrl = "https://whatsapp.com/channel/0029Vb2UNaWHFxP0YpoVU91B";
        //     window.open(whatsappChannelUrl, '_blank');
        // });

        var telegramButton = document.getElementById('telegramButton');
        telegramButton.addEventListener('click', function() {
            // Redirect to the Telegram channel
            var telegramChannelUrl = <?php echo json_encode($telegram_channel); ?>;
            if (telegramChannelUrl && telegramChannelUrl !== "N/A" && telegramChannelUrl !== "Failed to fetch telegram_channel.") {
                window.open(telegramChannelUrl, '_blank');
            } else {
                alert('No valid Telegram channel URL available.');
            }
        });
    });
</script>

</body>
</html>