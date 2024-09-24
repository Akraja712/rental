<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');
include('../includes/custom-functions.php');
include('../includes/variables.php');
$db = new Database();
$db->connect();
$fn = new custom_functions;


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    echo json_encode($response);
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num == 1) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        if ($_FILES['image']['size'] > 0) {
            $uploadDirectory = '../upload/images/';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $filename = microtime(true) . '.' . $fileExtension;
            $full_path = $uploadDirectory . $filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
                $upload_image = 'upload/images/' . $filename;
                $sql = "UPDATE users SET profile = '$upload_image' WHERE id = '$user_id'";
                $db->sql($sql);

                $sql = "SELECT profile FROM users WHERE id = '$user_id'";
                $db->sql($sql);
                $user_details = $db->getResult();
                $response["success"] = true;
                $response["message"] = "Profile updated successfully";
                $response["profile"] = DOMAIN_URL . $user_details[0]['profile'];
            } 
        } 
    } else {
        $response["success"] = false;
        $response["message"] = "Image parameter is missing";
    }
} else {
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

echo json_encode($response);
return false;
?>
