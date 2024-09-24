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
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
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
if (empty($_POST['referred_by'])) {
    $response['success'] = false;
    $response['message'] = "Refer code is empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['password'])) {
    $response['success'] = false;
    $response['message'] = "Password is empty";
    print_r(json_encode($response));
    return false;
}

$name = $db->escapeString($_POST['name']);
$mobile = $db->escapeString($_POST['mobile']);
$referred_by = $db->escapeString($_POST['referred_by']);
$device_id = $db->escapeString($_POST['device_id']);
$age = $db->escapeString($_POST['age']);
$city = $db->escapeString($_POST['city']);
$email = $db->escapeString($_POST['email']);
$state = $db->escapeString($_POST['state']);
$password = $db->escapeString($_POST['password']);
$c_referred_by = '';
$d_referred_by = '';
$datetime = date('Y-m-d H:i:s');

if (!empty($_POST['referred_by'])) {
    $referred_by = $db->escapeString($_POST['referred_by']);
    $sql = "SELECT id FROM users WHERE refer_code='$referred_by'";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num == 0) {
        $response['success'] = false;
        $response['message'] ="Invalid Referred By";
        print_r(json_encode($response));
        return false;
    }
}
$sql = "SELECT * FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] ="Mobile Number Already Registered";
    print_r(json_encode($response));
    return false;
} else {

    $sql = "SELECT id,referred_by FROM users WHERE refer_code = '$referred_by' AND refer_code != ''";
    $db->sql($sql);
    $refres = $db->getResult();
    $num = $db->numRows($refres);
    if ($num == 0) {
        $response['success'] = false;
        $response['message'] = "Invalid Refer Code";
        print_r(json_encode($response));
        return false;
    }else{
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
    function generateRandomString($length) {
        // Define an array containing digits and alphabets
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
    
        // Shuffle the array to make the selection random
        shuffle($characters);
    
        // Select random characters from the shuffled array
        $random_string = implode('', array_slice($characters, 0, $length));
    
        return $random_string;
    }
    $refer_code = generateRandomString(6);


    $sql = "INSERT INTO users (`mobile`,`name`,`referred_by`,`c_referred_by`,`d_referred_by`,`account_num`,`holder_name`,`bank`,`branch`,`ifsc`,`device_id`,`age`,`city`,`email`,`state`,`registered_datetime`,`refer_code`,`password`,`min_withdrawal`) VALUES ('$mobile','$name','$referred_by','$c_referred_by','$d_referred_by','','','','','','$device_id','$age','$city','$email','$state','$datetime','$refer_code','$password',500)";
    $db->sql($sql);

    $sql_query = "UPDATE users SET team_size = team_size + 1 WHERE refer_code =  '$referred_by'";
    $db->sql($sql_query);
    

    


    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Successfully Registered";
    $response['data'] = $res;
    print_r(json_encode($response));
}
?>