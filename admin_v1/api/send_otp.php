<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

date_default_timezone_set('Asia/Kolkata');

// Input validation and checks
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = 'Mobile is empty.';
    echo json_encode($response);
    return;
}

if (strlen($_POST['mobile']) !== 10) {
    $response['success'] = false;
    $response['message'] = 'Mobile should be 10 digits.';
    echo json_encode($response);
    return;
}

if (empty($_POST['country_code'])) {
    $response['success'] = false;
    $response['message'] = 'Country code is empty.';
    echo json_encode($response);
    return;
}

if (empty($_POST['otp'])) {
    $response['success'] = false;
    $response['message'] = 'OTP is empty.';
    echo json_encode($response);
    return;
}

$mobile = $_POST['mobile'];
$country_code = $_POST['country_code'];
$otp = $_POST['otp'];

// Define the API URL and parameters for OTP sending
$apiUrl = 'https://api.authkey.io/request'; 
$authKey = '673e807e1f672335'; // Your authkey here
$sid = '14324'; // SID, if applicable

// Make the HTTP request to the OTP API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'authkey' => $authKey,
    'mobile' => $mobile,
    'country_code' => $country_code,
    'sid' => $sid,
    'otp' => $otp,
]);

// Execute the request
$responseData = curl_exec($ch);

// Check if the request was successful
if ($responseData === false) {
    $response['success'] = false;
    $response['message'] = 'Error communicating with OTP service.';
    echo json_encode($response);
    curl_close($ch);
    return;
}

// Parse the API response
$apiResponse = json_decode($responseData, true);
curl_close($ch);

// Check if OTP was successfully submitted
if (isset($apiResponse['Message']) && $apiResponse['Message'] == 'Submitted Successfully') {
    $response['success'] = true;
    $response['message'] = 'OTP sent successfully.';
} else {
    $response['success'] = false;
    $response['message'] = isset($apiResponse['Message']) ? $apiResponse['Message'] : 'Failed to send OTP.';
}

echo json_encode($response);
?>
