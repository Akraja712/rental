<?php
include_once('includes/connection.php');
session_start();
ob_start();

if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    // Handle AJAX request
    $response = array('success' => false, 'message' => 'Unknown error');

    $mobile = $_POST["mobilenum"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $otpstatus = $_POST["otpstatus"];

    if ($password !== $confirmPassword) {
        $response['message'] = 'Password and Confirm Password do not match';
    } else {
        if ($otpstatus == '1') {
            $data = array(
                "mobile" => $mobile,
                "password" => $password,
            );

            $apiUrl = API_URL . "forgot_password.php";
            $curl = curl_init($apiUrl);

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $responseData = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if ($responseData === null) {
                $response['message'] = 'Error decoding API response.';
            } else {
                if (isset($responseData["success"]) && $responseData["success"]) {
                    $response['success'] = true;
                    $response['message'] = $responseData["message"];
                    $response['redirect'] = 'login.php';
                } else {
                    $response['message'] = isset($responseData["message"]) ? $responseData["message"] : "Registration failed. Please try again.";
                }
            }
        } else {
            $response['message'] = 'Mobile number not verified';
        }
    }

    echo json_encode($response);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #efefef;
        }
        .custom-container {
            width: 450px; 
            margin: 10px auto; /* Adjusted margin */
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: rgb(255, 255, 255);
        }
        .btn-custom {
            width: 100%;
            margin-top:25px;
            border-radius: 10px;
            border: 2px solid #4A148C;
        }
        .btn-customs {
            width: 100%;
            border-radius: 15px;
        }
        .heading {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .heading h2, .heading h3 {
            margin:10px;
            font-weight: 600;
        }
        .heading h2 {
            font-size: 1.5rem;
            color: #3eb3a8;
        }
        .heading h3 {
            font-size: 2rem;
            color: #333;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 576px) {
            .nowrap-mobile {
                white-space: nowrap;
                font-size: 10px;
            }
            .btn-customs {
                width: 100%;
                border-radius: 15px;
                margin-top:6px;
            }
            .heading h2 {
                font-size: 1.2rem;
            }
            .heading h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="heading">
<h3>Forgot Password</h3>
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="custom-container">
            <form method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" id="otpstatus" name="otpstatus" value="0">
            <input type="hidden" class="form-control" id="mobilenum" name="mobilenum" value="">
                        
                <div class="form-group">
                    <label for="mobile" style="font-weight:bold;">Mobile Number:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-mobile-alt"></i></span>
                        </div>
                        <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Mobile" style="border-left: none" required>
                        <div class="input-group-append">
                            <div class="btn btn-primary"  id="sendOtpButton">Send</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="otp" style="font-weight:bold;">OTP:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-message"></i></span>
                        </div>
                        <input type="number" class="form-control" id="otp" name="otp" placeholder="OTP" style="border-left: none">
                        <div class="input-group-append">
                            <div class="btn btn-success"  id="verifyOtpButton">Verify</div>
                            
                        </div>
                    </div>
                </div>
            
                <div class="form-group">
                    <label for="password" style= "font-weight:bold;">New Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required style="border-left: none;">
                    </div>
                    <span id="passwordError" class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword" style= "font-weight:bold;">Confirm Password:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-right: none; background: transparent;"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required style="border-left: none;">
                    </div>
                    <span id="confirmPasswordError" class="text-danger"></span>
                </div>
             
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-custom" name="btnChange" style="background-color:#4A148C; color:white; font-weight:bold;">Change Password</button>
                </div>
                <div class="text-center mt-3">
                    <p><a href="index.php">Back to Login</a></p>
                </div>
            </form>
        </div>
    </div>


    <script>
$(document).ready(function() {
    var crctotp = '';

    $("#sendOtpButton").click(function() {
        var mobile = $("#mobile").val();
        var mobilenum = document.getElementById("mobilenum");
        if (mobile.length === 10) {
            var otp = Math.floor(100000 + Math.random() * 900000);
            $.ajax({
                url: "https://api.authkey.io/request",
                type: "GET",
                data: {
                    authkey: "64045a300411033f",
                    mobile: mobile,
                    country_code: "91",
                    sid: "14031",
                    otp: otp,
                    company: "E-Books"
                },
                success: function(response) {
                    crctotp = otp;
                    mobilenum.value = mobile;
                    $("#mobile").prop("disabled", true);
                    alert("OTP Sent Successfully");
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                    alert("OTP Failed");
                }
            });

        } else {
            alert("Please enter a valid 10-digit mobile number.");
        }
    });

    $("#verifyOtpButton").click(function() {
        var otp = $("#otp").val();
        var otpStatusElement = document.getElementById("otpstatus");
        if (otp.length === 6) {
            if (otp == crctotp) {
                otpStatusElement.value = "1";
                alert("OTP Verified Successfully");
            } else {
                otpStatusElement.value = "0";
                alert("OTP Wrong");
            }
        } else {
            alert("Please enter a valid 6-digit OTP.");
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        var formData = $(this).serializeArray();
        formData.push({name: 'ajax', value: 'true'});

        $.ajax({
            url: "",  // The PHP file URL (current file if it's the same)
            type: "POST",
            data: $.param(formData),
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    alert(jsonResponse.message);  // Show the success message
                    window.location.href = jsonResponse.redirect;  
                } else {
                    alert(jsonResponse.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
                alert("An error occurred. Please try again.");
            }
        });
    });
});

</script>
<script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script><script id="zsiqscript" src="https://salesiq.zohopublic.in/widget?wc=siq7f332814434ba123f5efbf2d82a7e47947952e33ecc6bf4b78f9f89edf3ad350" defer></script>
</body>
</html>
