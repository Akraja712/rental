<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Transfer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .btn-success {
            color: #fff;
            background-color: #5cb85c;
            border-color: #4cae4c;
            margin-top: 25px;
            margin-left: 10px;
        }
        .wallet {
            margin-top: 20px;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <section class="content-header">
        <h1 class="wallet">Transfer Wallet / 
            <small><a href="menu.php"><i class="fa fa-home"></i> Back</a></small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="col-md-12">
                            <form id="walletTransferForm" method="POST">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label for="to_mobile">To Mobile Number:</label>
                                        <input type="text" id="to_mobile" name="to_mobile" class="form-control" maxlength="10" placeholder="Enter recipient's mobile number" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label for="transfer_amount">Transfer Amount:</label>
                                        <input type="number" id="transfer_amount" name="transfer_amount" class="form-control" min="1" placeholder="Enter amount to transfer" required>
                                    </div>
                                </div>
                                <button type="button" id="transferBtn" class="btn btn-success">Wallet Transfer</button>
                            </form>
                            <div id="mobileResult" style="margin-top: 20px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Handle Wallet Transfer
        $('#transferBtn').click(function () {
            const toMobile = $('#to_mobile').val().trim();
            const transferAmount = parseFloat($('#transfer_amount').val());
            const mobile = '1234567890'; // Replace with actual logged-in user's mobile number from session or cookie

            // Validate input fields
            if (!/^\d{10}$/.test(toMobile)) {
                alert('Please enter a valid 10-digit mobile number.');
                return;
            }

            if (isNaN(transferAmount) || transferAmount <= 0) {
                alert('Please enter a valid transfer amount.');
                return;
            }

            // Send AJAX request
            $.ajax({
                url: 'process-transfer.php', // Backend endpoint
                type: 'POST',
                data: { 
                    mobile: mobile,  // Include mobile number of logged-in user
                    to_mobile: toMobile, 
                    transfer_amount: transferAmount 
                },
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    $('#mobileResult').html(`<p>${response.message}</p>`);
                },
                error: function (xhr, status, error) {
                    console.error('Error Details:', xhr, status, error);
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });
    </script>
</body>
</html>
