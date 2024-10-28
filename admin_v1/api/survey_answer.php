<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['survey_id'])) {
    $response['success'] = false;
    $response['message'] = "Survey ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['user_option'])) {
    $response['success'] = false;
    $response['message'] = "User Option is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$survey_id = $db->escapeString($_POST['survey_id']);
$user_option = $db->escapeString($_POST['user_option']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM survey WHERE id = $survey_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "Survey not found";
    print_r(json_encode($response));
    return false;
}

    $sql = "INSERT INTO user_survey (`user_id`, `survey_id`, `user_option`) VALUES ('$user_id', '$survey_id', '$user_option')";
    $db->sql($sql);

    $response['success'] = true;
    $response['message'] = "Survey Inserted Successfully";
    print_r(json_encode($response));

?>
