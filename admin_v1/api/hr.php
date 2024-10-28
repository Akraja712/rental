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

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM hr";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['image'] = DOMAIN_URL . $row['image'];
        $temp['course_charges'] = $row['course_charges'];
        $temp['monthly_hr_earnings'] = $row['monthly_hr_earnings'] . '%';
        $temp['per_month'] = $row['per_month'];
        $temp['description'] = $row['description'];
        $temp['daily_earnings'] = $row['daily_earnings'];

        $hr_id = $row['id'];
        $sql_check_hr = "SELECT * FROM hr_jobs WHERE user_id = $user_id AND hr_id = $hr_id";
        $db->sql($sql_check_hr);
        $hr_exists = $db->numRows() > 0;
        $temp['status'] = $hr_exists ? 1: 0;
        
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Hr Details Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "Hr not found";
    print_r(json_encode($response));
}
?>
