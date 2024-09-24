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
$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
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
        $sql = "INSERT INTO review (`user_id`,`image`,`status`,`datetime`) VALUES ('$user_id','$upload_image',0,'$datetime')";
        $db->sql($sql);
        $response["success"]   = true;
        $response["message"] = "Review Screenshot Upload Successfully";
    }
    else{
        $response["success"]   = false;
        $response["message"] = "image parameter is missing.";

    }

print_r(json_encode($response));
return false;
?>