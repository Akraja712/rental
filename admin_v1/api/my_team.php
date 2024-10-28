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
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$plan_names_query = "SELECT name FROM plan";
$db->sql($plan_names_query);
$plan_names_res = $db->getResult();
$plan_names = array_column($plan_names_res, 'name');

$sql = "SELECT users.*, 
               DATE(users.registered_datetime) AS registered_datetime,
               GROUP_CONCAT(plan.name) AS plan_names
        FROM users 
        LEFT JOIN user_plan ON users.id = user_plan.user_id
        LEFT JOIN plan ON plan.id = user_plan.plan_id
        WHERE users.referred_by = (SELECT refer_code FROM users WHERE id = '$user_id')
        GROUP BY users.id";

$db->sql($sql);
$res = $db->getResult();

$num = $db->numRows($res);

if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Users Listed Successfully";

    foreach ($res as $key => $user) {
        $plans = array();
        
        foreach ($plan_names as $plan_name) {
            $plans[$plan_name] = in_array($plan_name, explode(',', $user['plan_names'])) ? 1 : 0;
        }
        
        $res[$key]['plans'] = $plans;
        // Masking mobile number
        $res[$key]['mobile'] = maskMobileNumber($user['mobile']);
    }
    
    // Hide the plan_names field
    foreach ($res as &$data) {
        unset($data['plan_names']);
    }
    
    $response['data'] = $res;

    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No Users found who were referred by the user with the specified user_id";
    print_r(json_encode($response));
}

function maskMobileNumber($mobile) {
    // Replace all but the first two and last two characters with *
    return substr($mobile, 0, 2) . str_repeat('*', strlen($mobile) - 4) . substr($mobile, -2);
}
?>