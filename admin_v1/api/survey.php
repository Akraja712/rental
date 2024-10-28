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
if (empty($_POST['video_id'])) {
    $response['success'] = false;
    $response['message'] = "Video ID is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$survey_id = $db->escapeString($_POST['survey_id']);
$video_id = $db->escapeString($_POST['video_id']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM survey WHERE id = $survey_id AND video_id = $video_id ";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['plan_id'] = $row['plan_id'];
        $temp['question'] = $row['question'];
        $temp['video_id'] = $row['video_id'];
        $sql_options = "SELECT option_1, option_2, option_3 FROM survey WHERE id = $survey_id";
        $db->sql($sql_options);
        $options = $db->getResult();
        $temp['correct_option'] = $options[0][$row['correct_option']];
        $temp['option_1'] = $row['option_1'];
        $temp['option_2'] = $row['option_2'];
        $temp['option_3'] = $row['option_3'];
        
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Survey Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else {
    $response['success'] = false;
    $response['message'] = "Survey not found";
    print_r(json_encode($response));
}





