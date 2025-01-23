
<style>
tr{
            border: 2px solid black ;
            border-collapse: collapse;
            
        }

        tr .no{
          background-color: #4A148C;
          text-align: center;
          color: white;
        }
        
        .td{
            text-align: center;
        }

    </style>

<?php
include 'admin_v1/includes/crud.php';
include 'admin_v1/includes/functions.php'; // Include necessary utility functions

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
        echo '<th class="no">Id</th>';
        echo '<th class="no">Name</th>';
        echo '<th class="no">Mobile</th>';
        echo '<th class="no">Recharge</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        echo '<td class="td">' . $row['id'] . '</td>';
        echo '<td class="td">' . $row['name'] . '</td>';
        echo '<td class="td">' . $row['mobile'] . '</td>';
        echo '<td class="td">' . $row['recharge'] . '</td>';
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
