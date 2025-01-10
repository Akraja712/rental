<?php
include 'includes/crud.php';
include 'includes/functions.php'; // Include necessary utility functions

// Initialize the $db and $fn variables
$db = new Database();
$fn = new Functions();
$db->connect();



if (isset($_POST['mobile']) && !empty($_POST['mobile'])) {
    // Sanitize input using htmlspecialchars
    $mobile = $db->escapeString(htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8'));

    // Query to check if the mobile number exists
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    

    if (!empty($res)) {
        // If mobile number is found, return the data as a table
        $row = $res[0];
        // echo '<script>alert("Mobile number found. User details:");</script>';
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Id</th>';
        echo '<th>Name</th>';
        echo '<th>Mobile</th>';
        echo '<th>Recharge</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['mobile'] . '</td>';
        echo '<td>' . $row['recharge'] . '</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    } else {
        // If mobile number is not found, return a message
       echo '<script>alert("Mobile number is not registered:");</script>';
    }
} else {
    echo '<div class="alert alert-danger">Invalid request.</div>';
}
?>
