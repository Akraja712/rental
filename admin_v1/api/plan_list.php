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
if (empty($_POST['type'])) {
    $response['success'] = false;
    $response['message'] = "Type is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$type = $db->escapeString($_POST['type']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM plan WHERE type = '$type' ORDER BY price";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['products'] = $row['products'];
        $temp['price'] = $row['price'];
        $temp['unit'] = $row['unit'];
        $temp['image'] = DOMAIN_URL . $row['image'];
        $temp['daily_income'] = $row['daily_income'];
        $temp['monthly_income'] = $row['monthly_income'];
        $temp['invite_bonus'] = $row['invite_bonus'];
        $temp['daily_quantity'] = $row['daily_quantity'];
        $temp['num_times'] = $row['num_times'];
        $temp['stock'] = $row['stock'];
        $temp['min_refers'] = $row['min_refers'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Plan Details Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No plans found";
    print_r(json_encode($response));
}
?>
