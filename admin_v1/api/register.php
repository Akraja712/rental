<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile number is Empty";
    print_r(json_encode($response));
    return false;
}

// Remove any non-numeric characters from the mobile number
$mobileNumber = preg_replace('/[^0-9]/', '', $_POST['mobile']);

// Check if the mobile number starts with '0'
if (substr($mobileNumber, 0, 1) === '0') {
    $response['success'] = false;
    $response['message'] = "Mobile number cannot start with '0'";
    print_r(json_encode($response));
    return false;
}

// Check if the length of the mobile number is exactly 10 digits
if (strlen($mobileNumber) !== 10) {
    $response['success'] = false;
    $response['message'] = "Mobile number should be exactly 10 digits, please remove if +91 is there";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['age'])) {
    $response['success'] = false;
    $response['message'] = "Age is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['city'])) {
    $response['success'] = false;
    $response['message'] = "City is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['email'])) {
    $response['success'] = false;
    $response['message'] = "Email is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['state'])) {
    $response['success'] = false;
    $response['message'] = "State is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['password'])) {
    $response['success'] = false;
    $response['message'] = "password is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['referred_by'])) {
    $response['success'] = false;
    $response['message'] = "Referred By is Empty";
    print_r(json_encode($response));
    return false;
}

$name = $db->escapeString($_POST['name']);
$mobile = $db->escapeString($_POST['mobile']);
$age = $db->escapeString($_POST['age']);
$city = $db->escapeString($_POST['city']);
$email = $db->escapeString($_POST['email']);
$state = $db->escapeString($_POST['state']);
$password = $db->escapeString($_POST['password']);
$referred_by = $db->escapeString($_POST['referred_by']);
$c_referred_by = '';
$d_referred_by = '';

if ($referred_by !== '5PL') {
    $sql = "SELECT id FROM users WHERE refer_code='$referred_by'";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num == 0) {
        $response['success'] = false;
        $response['message'] = "Invalid Referred By";
        print_r(json_encode($response));
        return false;
    }
}

$datetime = date('Y-m-d H:i:s');
$sql = "SELECT * FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] = "Mobile Number Already Registered";
    print_r(json_encode($response));
    return false;
} else {
    if ($referred_by !== '5PL') {
        $sql = "SELECT id,referred_by FROM users WHERE refer_code = '$referred_by' AND refer_code != ''";
        $db->sql($sql);
        $refres = $db->getResult();
        $num = $db->numRows($refres);
        if ($num == 0) {
            $response['success'] = false;
            $response['message'] = "Invalid Refer Code";
            print_r(json_encode($response));
            return false;
        } else {
            $ref2 = $refres[0]['referred_by'];
            $sql = "SELECT id,referred_by FROM users WHERE refer_code = '$ref2' AND refer_code != ''";
            $db->sql($sql);
            $refres2 = $db->getResult();
            $num = $db->numRows($refres2);
            if ($num == 1) {
                $c_referred_by = $ref2;
                $ref3 = $refres2[0]['referred_by'];
                $sql = "SELECT id,referred_by FROM users WHERE refer_code = '$ref3' AND refer_code != ''";
                $db->sql($sql);
                $refres3 = $db->getResult();
                $num = $db->numRows($refres3);
                if ($num == 1) {
                    $d_referred_by = $ref3;
                }
            }
        }
    }

    // Insert user data
    $sql = "INSERT INTO users (`mobile`,`name`,`referred_by`,`c_referred_by`,`d_referred_by`,`age`,`city`,`email`,`state`,`registered_datetime`,`password`) VALUES ('$mobile','$name','$referred_by','$c_referred_by','$d_referred_by','$age','$city','$email','$state','$datetime','$password')";
    $db->sql($sql);

    // Get the ID of the inserted user
    $sql = "SELECT id FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $userId = $res[0]['id'];

    // Generate refer code based on user ID
    $refer_code = 'PL' . str_pad($userId, 2, '0', STR_PAD_LEFT);

    // Update the refer code for the user
    $sql = "UPDATE users SET refer_code = '$refer_code' WHERE id = '$userId'";
    $db->sql($sql);

    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Successfully Registered";
    $response['data'] = $res;
    print_r(json_encode($response));
}
?>
