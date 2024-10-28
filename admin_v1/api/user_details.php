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
    echo json_encode($response);
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql_user = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql_user);
$res_user = $db->getResult();
$num = $db->numRows($res_user);

if ($num >= 1) {
    $user_details = $res_user[0];
    $user_details['profile'] = DOMAIN_URL . $user_details['profile'];

    $sql_settings = "SELECT min_withdrawal FROM settings WHERE id = 1";
    $db->sql($sql_settings);
    $res_settings = $db->getResult();
    $min_withdrawal = $res_settings[0]['min_withdrawal'];

    $user_details['min_withdrawal'] = $min_withdrawal;

    // Fetch default about_us text
    $default_about_us = "SLVE Enterprises is a leading 5PL logistics company, specializing in efficient and reliable stock supply to retail stores. We manage end-to-end supply chains, ensuring seamless integration and optimization for our clients. With our expertise, your retail business can achieve timely deliveries and maintain a competitive edge.";

    $default_recharge_url = "https://slveenterprises.org/product/30052663/Penta-Logistics---Retail-Courses?vid=5543940";
    // If 'about_us' field is empty, use the default text
    if (empty($user_details['about_us'])) {
        $user_details['about_us'] = $default_about_us;
    }
    if (empty($user_details['recharge_url'])) {
        $user_details['recharge_url'] = $default_recharge_url;
    }
   
       

    // Fetch associated plans for the user
    $sql_plans = "SELECT plan.name FROM user_plan
                  LEFT JOIN plan ON user_plan.plan_id = plan.id
                  WHERE user_plan.user_id = $user_id";
    $db->sql($sql_plans);
    $res_plans = $db->getResult();

    $plan_names_query = "SELECT name FROM plan";
    $db->sql($plan_names_query);
    $plan_names_res = $db->getResult();
    $plan_names = array_column($plan_names_res, 'name');

    $user_plans = array();

    // Initialize plans for the user
    $user_details['plan_activated'] = array_fill_keys($plan_names, 0);

    // Set associated plans for the user
    foreach ($res_plans as $user_plan) {
        $user_details['plan_activated'][$user_plan['name']] = 1;
    }

    // Final response
    $response['success'] = true;
    $response['message'] = "User Details Retrieved Successfully";
    $response['data'] = array($user_details);
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "User Not found";
    echo json_encode($response);
}
?>
