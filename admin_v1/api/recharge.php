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
include('../includes/custom-functions.php');
include('../includes/variables.php');
$db = new Database();
$db->connect();
$fn = new custom_functions;

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$recharge_amount = (isset($_POST['recharge_amount']) && $_POST['recharge_amount'] != "") ? $db->escapeString($_POST['recharge_amount']) : "";
$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
function isBetween9AMand9PM() {
    $currentHour = date('H');
    $startTimestamp = strtotime('09:00:00');
    $endTimestamp = strtotime('21:00:00');
    return ($currentHour >= date('H', $startTimestamp)) && ($currentHour < date('H', $endTimestamp));
}
if (!isBetween9AMand9PM()) {
    $response['success'] = false;
    $response['message'] = "Recharge time morning 09 AM to 09 PM";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $sql = "SELECT id FROM recharge WHERE user_id = $user_id AND status = 0";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num >= 1) {
        $response["success"]   = false;
        $response["message"] = "Please wait, your request is still pending";
        print_r(json_encode($response));
        return false;
        
    }
    $sql = "SELECT id FROM recharge WHERE user_id = $user_id AND DATE(datetime) = '$date' AND status = 2";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num >= 3) {
        $response["success"] = false;
        $response["message"] = "Maximum Limit Reached Today";
        print_r(json_encode($response));
        return false;
        
    }
    if (isset($_FILES['image']) && !empty($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
        if (!is_dir('../upload/images/')) {
            mkdir('../upload/images/', 0777, true);
        }
        $image = $db->escapeString($fn->xss_clean($_FILES['image']['name']));
        $extension = pathinfo($_FILES["image"]["name"])['extension'];
        $result = $fn->validate_image($_FILES["image"]);
        if (!$result) {
            $response["success"]   = false;
            $response["message"] = "Image type must jpg, jpeg, gif, or png!";
            print_r(json_encode($response));
            return false;
        }
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = '../upload/images/' . "" . $filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            $response["success"]   = false;
            $response["message"] = "Invalid directory to load image!";
            print_r(json_encode($response));
            return false;
        }
        $upload_image= 'upload/images/' . $filename;
        $sql = "INSERT INTO recharge (`user_id`,`recharge_amount`,`image`,`status`,`datetime`) VALUES ('$user_id','$recharge_amount','$upload_image',0,'$datetime')";
        $db->sql($sql);

        $response["success"]   = true;
        $response["message"] = "Recharge Added Successfully";
    }
    else{
        $response["success"]   = false;
        $response["message"] = "image parameter is missing.";

    }

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}
print_r(json_encode($response));
return false;
?>