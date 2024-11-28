<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$currentdate = date('Y-m-d');
$db->connect();

        // Get the current date and time
        $date = new DateTime('now');

        // Round off to the nearest hour
        $date->modify('+' . (60 - $date->format('i')) . ' minutes');
        $date->setTime($date->format('H'), 0, 0);
    
        // Format the date and time as a string
        $date_string = $date->format('Y-m-d H:i:s');
        $currentdate = date('Y-m-d');

        //users 
        if (isset($_GET['table']) && $_GET['table'] == 'users') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';

            if (isset($_GET['referred_by']) && $_GET['referred_by'] != '') {
                $referred_by = $db->escapeString($fn->xss_clean($_GET['referred_by']));
                $where .= "AND referred_by = '$referred_by' "; // Properly append the condition
            }
            if (isset($_GET['profile']) && $_GET['profile'] != '') {
                $profile = $db->escapeString($fn->xss_clean($_GET['profile']));
                if ($profile == 'text') {
                    $where .= "AND profile <> '' "; 
                } else if ($profile == 'NULL') {
                    $where .= "AND (profile = '' OR profile IS NULL) "; 
                }
            }
            if (isset($_GET['day_filter']) && $_GET['day_filter'] == 'today') {
                // Get today's date in Y-m-d format (you can adjust the format as needed)
                $today = date('Y-m-d');
                $where .= " AND DATE(registered_datetime) = '$today' ";
            } elseif (isset($_GET['day_filter']) && $_GET['day_filter'] == 'all') {
                // No additional condition needed, will show all records
            }
            
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = $db->escapeString($_GET['search']);
                    $where .= " AND (id LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR mobile LIKE '%" . $search . "%' OR refer_code LIKE '%" . $search . "%')";
                }
            if (isset($_GET['sort'])){
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
                $order = $db->escapeString($_GET['order']);
            }
           
            $sql = "SELECT COUNT(`id`) as total FROM `users` WHERE 1=1 " . $where;
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row)
                $total = $row['total'];

            $sql = "SELECT * FROM users WHERE 1=1 " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();

        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
           
                $operate = ' <a href="edit-users.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-users.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['name'] = $row['name'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['email'] = $row['email'];
                $tempRow['password'] = $row['password'];
                $tempRow['referred_by'] = $row['referred_by'];
                $tempRow['refer_code'] = $row['refer_code'];
                $tempRow['account_num'] = $row['account_num'];
                $tempRow['holder_name'] = $row['holder_name'];
                $tempRow['bank'] = $row['bank'];
                $tempRow['branch'] = $row['branch'];
                $tempRow['ifsc'] = $row['ifsc'];
                $tempRow['age'] = $row['age'];
                $tempRow['city'] = $row['city'];
                $tempRow['state'] = $row['state'];
                $tempRow['device_id'] = $row['device_id'];
                $tempRow['today_income'] = $row['today_income'];
                $tempRow['total_income'] = $row['total_income'];
                $tempRow['balance'] = $row['balance'];
                $tempRow['withdrawal_status'] = $row['withdrawal_status'];
                $tempRow['recharge'] = $row['recharge'];
                $tempRow['total_recharge'] = $row['total_recharge'];
                $tempRow['team_size'] = $row['team_size'];
                $tempRow['valid_team'] = $row['valid_team'];
                $tempRow['total_assets'] = $row['total_assets'];
                $tempRow['total_withdrawal'] = $row['total_withdrawal'];
                $tempRow['team_income'] = $row['team_income'];
                $tempRow['registered_datetime'] = $row['registered_datetime'];
                $tempRow['latitude'] = $row['latitude'];
                $tempRow['longitude'] = $row['longitude'];
                $tempRow['earning_wallet'] = $row['earning_wallet'];
                $tempRow['bonus_wallet'] = $row['bonus_wallet'];
               
                if (!empty($row['profile'])) {
                    $tempRow['profile'] = "<a data-lightbox='category' href='" . $row['profile'] . "' data-caption='" . $row['profile'] . "'><img src='" . $row['profile'] . "' title='" . $row['profile'] . "' height='50' /></a>";
                } else {
                    $tempRow['profile'] = 'No Image';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

        //plan
        if (isset($_GET['table']) && $_GET['table'] == 'plan') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($_GET['search']);
                $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
            }
            if (isset($_GET['sort'])){
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
                $order = $db->escapeString($_GET['order']);
            }
            $sql = "SELECT COUNT(`id`) as total FROM `plan` ";
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row)
                $total = $row['total'];
           
            $sql = "SELECT * FROM plan " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
        
                
                $operate = ' <a href="edit-plan.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-plan.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['name'] = $row['name'];
                $tempRow['description'] = $row['description'];
                $tempRow['demo_video'] = $row['demo_video'];
                $tempRow['per_code_cost'] = $row['per_code_cost'];
                $tempRow['daily_codes'] = $row['daily_codes'];
                $tempRow['daily_earnings'] = $row['daily_earnings'];
                $tempRow['monthly_earnings'] = $row['monthly_earnings'];
                $tempRow['price'] = $row['price'];
                $tempRow['type'] = $row['type'];
                $tempRow['min_refers'] = $row['min_refers'];
                $tempRow['invite_bonus'] = $row['invite_bonus'];
                if(!empty($row['image'])){
                    $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        
                }else{
                    $tempRow['image'] = 'No Image';
        
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

        //withdrawals table goes here
if (isset($_GET['table']) && $_GET['table'] == 'withdrawals') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    
    if ((isset($_GET['status'])  && $_GET['status'] != '')) {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "AND w.status=$status ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%'  OR w.datetime LIKE '%" . $search . "%' OR u.account_num LIKE '%" . $search . "%') ";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $join = "WHERE w.user_id = u.id ";

    $sql = "SELECT COUNT(u.id) as `total` FROM `withdrawals` w,`users` u $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT w.id AS id,w.*,u.name,u.mobile,u.account_num,u.holder_name,u.bank,u.branch,u.ifsc,w.status AS status FROM `withdrawals` w,`users` u $join 
          $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $amount = $row['amount'];
        $tempRow['column'] = $checkbox;
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['account_num'] = ','.$row['account_num'].',';
        $tempRow['holder_name'] = $row['holder_name'];
        $tempRow['bank'] = $row['bank'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['ifsc'] = $row['ifsc'];
        $amount = $row['amount'];

        if ($amount < 250) {
            $taxRate = 0.05; // 5% tax rate
        } elseif ($amount <= 500) {
            $taxRate = 0.1; // 10% tax rate
        } elseif ($amount <= 1000) {
            $taxRate = 0.15; // 15% tax rate
        } else {
            $taxRate = 0.2; // 20% tax rate
        }
        
        $taxAmount = $amount * $taxRate;
        $pay_amount = $amount - $taxAmount;
        $tempRow['pay_amount'] = $pay_amount;
        $tempRow['amount'] = $row['amount'];
        $tempRow['datetime'] = $row['datetime'];
        if($row['status']==1)
                $tempRow['status']="<p class='text text-success'>Paid</p>";        
        elseif($row['status']==0)
                 $tempRow['status']="<p class='text text-primary'>Unpaid</p>"; 
        else
               $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//transaction
if (isset($_GET['table']) && $_GET['table'] == 'transactions') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if (isset($_GET['type']) && $_GET['type'] != '') {
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= " AND l.type = '$type'";
    }

    if (isset($_GET['date']) && $_GET['date'] != '') {
        $selected_date = $db->escapeString($fn->xss_clean($_GET['date']));
        $formatted_date = date('Y-m-d', strtotime($selected_date));
        $where .= " AND DATE(l.datetime) = '$formatted_date'";
    }

    if (isset($_GET['price']) && $_GET['price'] != '') {
        $price = $db->escapeString($fn->xss_clean($_GET['price']));
        $where .= " AND p.price = '$price'";
    }
    
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= " AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id 
             LEFT JOIN `user_plan` up ON u.id = up.user_id 
             LEFT JOIN `plan` p ON up.plan_id = p.id 
             WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `transactions` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT DISTINCT l.id AS id, l.*, u.name, u.mobile, p.price  FROM `transactions` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['type'] = $row['type'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['price'] = $row['price'];
        
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}



//user plan
if (isset($_GET['table']) && $_GET['table'] == 'user_plan') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['name']) && $_GET['name'] != '') {
        $name = $db->escapeString($fn->xss_clean($_GET['name']));
        $where .= " AND p.name = '$name'";
    }
    if ((isset($_GET['joined_date']) && $_GET['joined_date'] != '')) {
        $joined_date = $db->escapeString($fn->xss_clean($_GET['joined_date']));
        $where .= " AND l.joined_date = '$joined_date'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND (u.id LIKE '%" . $search . "%' OR u.name LIKE '%" . $search ."%'  OR u.mobile LIKE '%" . $search . "%')";
        }
        $join = "LEFT JOIN `users` u ON l.user_id = u.id LEFT JOIN `plan` p ON l.plan_id = p.id WHERE l.id IS NOT NULL " . $where;

        $sql = "SELECT COUNT(l.id) AS total FROM `user_plan` l " . $join;
        $db->sql($sql);
        $res = $db->getResult();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        
        $sql = "SELECT l.id AS id, l.*, u.name AS user_name, u.mobile AS user_mobile, u.referred_by AS user_referred_by, p.name AS plan_name, p.price AS plan_price, p.daily_codes AS plan_daily_codes, p.per_code_cost AS plan_per_code_cost, p.daily_earnings AS plan_daily_earnings, p.monthly_earnings AS plan_monthly_earnings  FROM `user_plan` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
        $db->sql($sql);
        $res = $db->getResult();
        

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {


        
        //$operate = ' <a href="edit-user_plan.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate = ' <a class="text text-danger" href="delete-user_plan.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['user_name'] = $row['user_name'];
        $tempRow['user_mobile'] = $row['user_mobile'];
        $tempRow['user_referred_by'] = $row['user_referred_by'];
        $tempRow['plan_name'] = $row['plan_name'];
        $tempRow['plan_price'] = $row['plan_price'];
        $tempRow['plan_daily_codes'] = $row['plan_daily_codes'];
        $tempRow['plan_per_code_cost'] = $row['plan_per_code_cost'];
        $tempRow['plan_daily_earnings'] = $row['plan_daily_earnings'];
        $tempRow['plan_monthly_earnings'] = $row['plan_monthly_earnings'];
        $tempRow['income'] = $row['income'];
        $tempRow['joined_date'] = $row['joined_date'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//user plan
if (isset($_GET['table']) && $_GET['table'] == 'user_rental') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['name']) && $_GET['name'] != '') {
        $name = $db->escapeString($fn->xss_clean($_GET['name']));
        $where .= " AND r.name = '$name'";
    }
    if ((isset($_GET['joined_date']) && $_GET['joined_date'] != '')) {
        $joined_date = $db->escapeString($fn->xss_clean($_GET['joined_date']));
        $where .= " AND l.joined_date = '$joined_date'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND (u.id LIKE '%" . $search . "%' OR u.name LIKE '%" . $search ."%'  OR u.mobile LIKE '%" . $search . "%')";
        }
        $join = "LEFT JOIN `users` u ON l.user_id = u.id LEFT JOIN `rental` r ON l.rental_id = r.id WHERE l.id IS NOT NULL " . $where;

        $sql = "SELECT COUNT(l.id) AS total FROM `user_rental` l " . $join;
        $db->sql($sql);
        $res = $db->getResult();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        
        $sql = "SELECT l.id AS id, l.*, u.name AS user_name, u.mobile AS user_mobile, u.referred_by AS user_referred_by, r.name AS rental_name, r.price AS rental_price, r.course_charges AS rental_course_charges, r.per_month AS rental_per_month, r.monthly_rental_earnings AS rental_monthly_rental_earnings, r.daily_earnings AS rental_daily_earnings, r.min_refers AS rental_min_refers, r.invite_bonus AS rental_invite_bonus  FROM `user_rental` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
        $db->sql($sql);
        $res = $db->getResult();
        

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {


        
        //$operate = ' <a href="edit-user_plan.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate = ' <a class="text text-danger" href="delete-user_rental.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['user_name'] = $row['user_name'];
        $tempRow['user_mobile'] = $row['user_mobile'];
        $tempRow['user_referred_by'] = $row['user_referred_by'];
        $tempRow['rental_name'] = $row['rental_name'];
        $tempRow['rental_course_charges'] = $row['rental_course_charges'];
        $tempRow['rental_price'] = $row['rental_price'];
        $tempRow['rental_per_month'] = $row['rental_per_month'];
        $tempRow['rental_monthly_rental_earnings'] = $row['rental_monthly_rental_earnings'];
        $tempRow['rental_min_refers'] = $row['rental_min_refers'];
        $tempRow['rental_daily_earnings'] = $row['rental_daily_earnings'];
        $tempRow['rental_invite_bonus'] = $row['rental_invite_bonus'];
        $tempRow['income'] = $row['income'];
        $tempRow['joined_date'] = $row['joined_date'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//recharge
if (isset($_GET['table']) && $_GET['table'] == 'recharge') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= " AND l.status = '$status'";
    }

    if (isset($_GET['date']) && $_GET['date'] != '') {
        $selected_date = $db->escapeString($fn->xss_clean($_GET['date']));
        $formatted_date = date('Y-m-d', strtotime($selected_date));
        $where .= " AND DATE(l.datetime) = '$formatted_date'";
    }

    if (isset($_GET['hour']) && $_GET['hour'] != '') {
        $selected_hour = $db->escapeString($fn->xss_clean($_GET['hour']));
        $where .= " AND HOUR(l.datetime) = '$selected_hour'";
    }
    

    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= " AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR refer_code LIKE '%" . $search . "%')";
    }
    
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `recharge` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row) {
        $total = $row['total'];
    }

    $sql = "SELECT l.id AS id, l.*, u.name,u.mobile FROM `recharge` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

$bulkData = array();
$bulkData['total'] = $total;
$rows = array();
$tempRow = array();
foreach ($res as $row) {
        $operate = ' <a href="edit-recharge.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-recharge.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['recharge_amount'] = $row['recharge_amount'];
        $tempRow['datetime'] = $row['datetime'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>Verified</p>";
    elseif($row['status']==0)
        $tempRow['status']="<p class='text text-primary'>Not-Verified</p>";
    else
        $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $tempRow['column'] = $checkbox;
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//plan_slide
if (isset($_GET['table']) && $_GET['table'] == 'plan_slide') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `plan_slides` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM plan_slides " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-plan_slides.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-plan_slides.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['link'] = $row['link'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//home_slide
if (isset($_GET['table']) && $_GET['table'] == 'home_slide') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `home_slides` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM home_slides " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-home_slides.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-home_slides.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['link'] = $row['link'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//free_plan_image
if (isset($_GET['table']) && $_GET['table'] == 'free_plan_image') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `free_plan_image` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM free_plan_image " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-free_plan_image.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-free_plan_image.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//explore
if (isset($_GET['table']) && $_GET['table'] == 'explore') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `explore` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM explore " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-explore.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-explore.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['main_content'] = $row['main_content'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//Payment Setting
if (isset($_GET['table']) && $_GET['table'] == 'payment_setting') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `payment_setting` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM payment_setting " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-payment_setting.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-payment_setting.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['video_link'] = $row['video_link'];
        if(!empty($row['qr_image'])){
            $tempRow['qr_image'] = "<a data-lightbox='category' href='" . $row['qr_image'] . "' data-caption='" . $row['qr_image'] . "'><img src='" . $row['qr_image'] . "' title='" . $row['qr_image'] . "' height='50' /></a>";

        }else{
            $tempRow['qr_image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//otp
if (isset($_GET['table']) && $_GET['table'] == 'otp') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR mobile like '%" . $search . "%' OR otp like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `otp` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM otp " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-otp.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-otp.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['otp'] = $row['otp'];
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//hour withdrawal report table goes here
if (isset($_GET['table']) && $_GET['table'] == 'hour_withdrawal') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $where = " WHERE DATE(datetime) = '$date'";
    } 
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND u.mobile like '%" . $search . "%' OR w.datetime like '%" . $search . "%' OR u.upi like '%" . $search . "%' OR w.amount like  '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }  
    $sql = "SELECT COUNT(`id`) as total FROM `withdrawals` " . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
    
     $sql = "SELECT DATE_FORMAT(datetime, '%Y-%m-%d %H:00:00') AS hour_group, SUM(amount) AS total_withdrawal
     FROM `withdrawals`" . $where . " GROUP BY hour_group";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['hour_group'] = $row['hour_group'];
        $tempRow['total_withdrawal'] = $row['total_withdrawal'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    echo json_encode($bulkData);
}


//scratch_cards table
if (isset($_GET['table']) && $_GET['table'] == 'markets') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND l.id LIKE '%" . $search . "%' OR l.name LIKE '%" . $search . "%' OR p.products LIKE '%" . $search . "%'";
        }  
        $join = "LEFT JOIN `plan` p ON l.plan_id = p.id WHERE l.id IS NOT NULL " . $where;

        $sql = "SELECT COUNT(l.id) AS total FROM `markets` l " . $join ;
        $db->sql($sql);
        $res = $db->getResult();
        foreach ($res as $row) {
            $total = $row['total'];
        }
    
        $sql = "SELECT l.id AS id, l.*, p.products FROM `markets` l " . $join  . $where . " ORDER BY $sort $order LIMIT $offset, $limit";
        $db->sql($sql);
        $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

      
        $operate = '<a href="edit-markets.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-markets.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
       $tempRow['id'] = $row['id'];
       $tempRow['products'] = $row['products'];
       $tempRow['name'] = $row['name'];
       $tempRow['price'] = $row['price'];
    $tempRow['operate'] = $operate;
    $rows[] = $tempRow;
}
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//Recharge Trans
if (isset($_GET['table']) && $_GET['table'] == 'recharge_trans') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= " AND l.status = '$status'";
    }

    if (isset($_GET['date']) && $_GET['date'] != '') {
        $selected_date = $db->escapeString($fn->xss_clean($_GET['date']));
        $formatted_date = date('Y-m-d', strtotime($selected_date));
        $where .= " AND DATE(l.datetime) = '$formatted_date'";
    }
    
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

       

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR l.txn_id LIKE '%" . $search . "%' OR l.order_id LIKE '%" . $search . "%') ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `recharge_trans` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `recharge_trans` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['txn_id'] = $row['txn_id'];
        $tempRow['order_id'] = $row['order_id'];
        $tempRow['amount'] = $row['amount'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>Paid</p>";
        elseif($row['status']==0)
        $tempRow['status']="<p class='text text-primary'>Not-Paid</p>";
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['column'] = $checkbox;
        
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//Survey
if (isset($_GET['table']) && $_GET['table'] == 'survey') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['products']) && $_GET['products'] != '') {
        $products = $db->escapeString($fn->xss_clean($_GET['products']));
        $where .= " AND p.products = '$products'";
    }
    if ((isset($_GET['joined_date']) && $_GET['joined_date'] != '')) {
        $joined_date = $db->escapeString($fn->xss_clean($_GET['joined_date']));
        $where .= " AND l.joined_date = '$joined_date'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND (u.id LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR p.name LIKE '%" . $search . "%'  OR u.mobile LIKE '%" . $search . "%')";
        }
       
        $join = "LEFT JOIN `plan` p ON l.plan_id = p.id WHERE l.id IS NOT NULL " . $where;

        $sql = "SELECT COUNT(l.id) AS total FROM `survey` l " . $join;
        $db->sql($sql);
        $res = $db->getResult();
        foreach ($res as $row)
            $total = $row['total'];
       
         $sql = "SELECT l.id AS id,l.*,p.name FROM `survey` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
         $db->sql($sql);
         $res = $db->getResult();
        

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {


        
        $operate = ' <a href="edit-survey.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-survey.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['question'] = $row['question'];
        $tempRow['correct_option'] = str_replace('_', ' ', $row['correct_option']);
        $tempRow['option_1'] = $row['option_1'];
        $tempRow['option_2'] =$row['option_2'];
        $tempRow['option_3'] =  $row['option_3'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//video
if (isset($_GET['table']) && $_GET['table'] == 'video') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR mobile like '%" . $search . "%' OR otp like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `video` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM video " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-video.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-video.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['url'] = $row['url'];
        $tempRow['duration'] = $row['duration'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>Active</p>";
        elseif($row['status']==0)
        $tempRow['status']="<p class='text text-primary'>Deactivate</p>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//notifications table goes here
if (isset($_GET['table']) && $_GET['table'] == 'notifications') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "WHERE id like '%" . $search . "%' OR title like '%" . $search . "%' OR description like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `notifications`" . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM notifications " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . "," . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a class="text text-danger" href="delete-notification.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['title'] = $row['title'];
        $tempRow['description'] = $row['description'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//otp
if (isset($_GET['table']) && $_GET['table'] == 'payments') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR mobile like '%" . $search . "%' OR order_id like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `payments` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM payments " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        //$operate = ' <a href="edit-payments.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate = ' <a class="text text-danger" href="delete-payments.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['order_id'] = $row['order_id'];
        $tempRow['product_id'] = $row['product_id'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['claim'] = $row['claim'];
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
$db->disconnect();

