<?php
session_start();
$mobile = isset($_SESSION['mobile']) ? $_SESSION['mobile'] : '';
?>

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
                            <form id="walletTransferForm">
                                <div class="form-group">
                                     <!-- Hidden input for user's mobile number -->
                                     <input type="hidden" id="mobile" value="<?php echo $mobile; ?>">
                                    <div class="col-md-3">
                                        <label for="to_mobile">Recipient's Mobile Number:</label>
                                        <input type="text" id="to_mobile" name="to_mobile" class="form-control" maxlength="10" placeholder="Enter recipient's mobile number" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label for="transfer_amount">Transfer Amount:</label>
                                        <input type="number" id="transfer_amount" name="transfer_amount" class="form-control" min="1" placeholder="Enter amount to transfer" required>
                                    </div>
                                </div>
                                <button type="button" id="transferBtn" class="btn btn-success">Transfer</button>
                            </form>
                            <div id="responseMessage" style="margin-top: 20px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#transferBtn').click(function () {
            const mobile = $('#mobile').val().trim();
            const toMobile = $('#to_mobile').val().trim();
            const transferAmount = parseFloat($('#transfer_amount').val());


            // Validate inputs
            if (!/^\d{10}$/.test(toMobile)) {
                alert('Please enter a valid 10-digit mobile number.');
                return;
            }

            if (mobile === toMobile) {
                alert('Cannot transfer funds to the same mobile number.');
                return;
            }

            if (isNaN(transferAmount) || transferAmount <= 0) {
                alert('Please enter a valid transfer amount.');
                return;
            }

            // Send AJAX request
            $.ajax({
                url: 'process-transfer.php',
                type: 'POST',
                data: {
                    mobile: mobile,
                    to_mobile: toMobile,
                    transfer_amount: transferAmount
                },
                dataType: 'json',
                success: function (response) {
                    $('#responseMessage').html(`<p>${response.message}</p>`);
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    </script>
</body>
</html>
