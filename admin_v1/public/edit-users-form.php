<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>

<?php
if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}
$latitude = isset($_POST['latitude']) ? $db->escapeString($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) ? $db->escapeString($_POST['longitude']) : null;
$address = isset($_POST['address']) ? $db->escapeString($_POST['address']) : "Unknown";

if (isset($_POST['btnEdit'])) {

    $name = $db->escapeString($_POST['name']);
    $mobile = $db->escapeString($_POST['mobile']);
    $email= $db->escapeString($_POST['email']);
    $refer_code= $db->escapeString($_POST['refer_code']);
    $referred_by= $db->escapeString($_POST['referred_by']);
    $account_num = $db->escapeString($_POST['account_num']);
    $holder_name = $db->escapeString($_POST['holder_name']);
    $bank = $db->escapeString($_POST['bank']);
    $branch = $db->escapeString(($_POST['branch']));
    $ifsc = $db->escapeString(($_POST['ifsc']));
    $age = $db->escapeString(($_POST['age']));
    $city = $db->escapeString(($_POST['city']));
    $state = $db->escapeString(($_POST['state']));
    $device_id = $db->escapeString($_POST['device_id']);
    $today_income = $db->escapeString(($_POST['today_income']));
    $total_income = $db->escapeString(($_POST['total_income']));
    $balance = $db->escapeString(($_POST['balance']));
    $withdrawal_status = $db->escapeString(($_POST['withdrawal_status']));
    $income_status = $db->escapeString(($_POST['income_status']));
    $recharge = $db->escapeString(($_POST['recharge']));
    $total_recharge = $db->escapeString($_POST['total_recharge']);
    $team_size = $db->escapeString($_POST['team_size']);
    $valid_team = $db->escapeString($_POST['valid_team']);
    $total_assets = $db->escapeString($_POST['total_assets']);
    $total_withdrawal = $db->escapeString($_POST['total_withdrawal']);
    $team_income= $db->escapeString($_POST['team_income']);
    $registered_datetime= $db->escapeString($_POST['registered_datetime']);
    $blocked = $db->escapeString($_POST['blocked']);
    $password= $db->escapeString($_POST['password']);
    $latitude= $db->escapeString($_POST['latitude']);
    $longitude = $db->escapeString($_POST['longitude']);
    $earning_wallet= $db->escapeString($_POST['earning_wallet']);
    $bonus_wallet = $db->escapeString($_POST['bonus_wallet']);

    $error = array();

    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($age)) {
        $error['age'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($city)) {
        $error['city'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($email)) {
        $error['email'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($state)) {
        $error['state'] = " <span class='label label-danger'>Required!</span>";
    }

    
            $sql_query = "UPDATE users SET name='$name',mobile = '$mobile',password = '$password',email='$email',age='$age',city='$city',referred_by='$referred_by',refer_code='$refer_code',holder_name='$holder_name', bank='$bank', branch='$branch', ifsc='$ifsc', account_num='$account_num',withdrawal_status = '$withdrawal_status',recharge  = '$recharge ',balance = '$balance',today_income = '$today_income',device_id  = '$device_id',total_income  = '$total_income',state  = '$state',total_recharge  = '$total_recharge',team_size  = '$team_size',valid_team  = '$valid_team',total_assets  = '$total_assets',total_withdrawal  = '$total_withdrawal',team_income  = '$team_income',registered_datetime  = '$registered_datetime',blocked = '$blocked',earning_wallet = '$earning_wallet',bonus_wallet = '$bonus_wallet',income_status = '$income_status' WHERE id = $ID";
            $db->sql($sql_query);
            $update_result = $db->getResult();
    
            if (!empty($update_result)) {
                $update_result = 0;
            } else {
                $update_result = 1;
            }
    
            if ($update_result == 1) {
                $datetime = date('Y-m-d H:i:s'); 
                $tracking_sql = "INSERT INTO tracking (type, datetime, latitude, longitude, address) 
                    VALUES ('edit', '$datetime', '$latitude', '$longitude', '$address')";
                $db->sql($tracking_sql);
            }

            // check update result
            if ($update_result == 1) {
                $error['update_users'] = " <section class='content-header'><span class='label label-success'>User Details updated Successfully</span></section>";
            } else {
                $error['update_users'] = " <span class='label label-danger'>Failed to update</span>";
            }
        }
    


 
$data = array();


$sql_query = "SELECT * FROM users WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "users.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Users<small><a href='users.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to users</a></small></h1>
    <small><?php echo isset($error['update_users']) ? $error['update_users'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->

    <div class="row">
        <div class="col-md-11">

        <div class="box box-primary">
               <div class="box-header with-border">
                           <div class="form-group col-md-3">
                                <h4 class="box-title"> </h4>
                                <a class="btn btn-block btn-primary" href="add-recharge.php?id=<?php echo $ID ?>"><i class="fa fa-plus-square"></i> Add Recharge</a>
                            </div>
                            <div class="form-group col-md-3">
                                <h4 class="box-title"> </h4>
                                <a class="btn btn-block btn-success" href="add-bonus.php?id=<?php echo $ID ?>"><i class="fa fa-plus-square"></i> Add Bonus</a>
                            </div>
                </div>
                <!-- /.box-header -->
                <form id="edit_project_form" method="post" enctype="multipart/form-data">
                <div class="box-body">
                        <div class="row">
                              <div class="form-group">
                              <div class="col-md-3">
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                    <input type="email" class="form-control" name="email" value="<?php echo $res[0]['email']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Age</label> <i class="text-danger asterik">*</i><?php echo isset($error['age']) ? $error['age'] : ''; ?>
                                    <input type="number" class="form-control" name="age" value="<?php echo $res[0]['age']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Mobile</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                    <input type="number" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>">
                                </div>
                               </div>
                             </div>
                          <br>
                          <div class="row">
                              <div class="form-group">
                              <div class="col-md-3">
                                    <label for="exampleInputEmail1">State</label> <i class="text-danger asterik">*</i><?php echo isset($error['state']) ? $error['state'] : ''; ?>
                                    <input type="text" class="form-control" name="state" value="<?php echo $res[0]['state']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">City</label> <i class="text-danger asterik">*</i><?php echo isset($error['city']) ? $error['city'] : ''; ?>
                                    <input type="text" class="form-control" name="city" value="<?php echo $res[0]['city']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Refered By</label> <i class="text-danger asterik">*</i<?php echo isset ($error['referred_by']) ? $error['referred_by'] : ''; ?>>
                                    <input type="text" class="form-control" name="referred_by" value="<?php echo $res[0]['referred_by']; ?>">
                                 </div>  
                                 <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Refer Code</label> <i class="text-danger asterik">*</i><?php echo isset($error['refer_code']) ? $error['refer_code'] : ''; ?>
                                    <input type="text" class="form-control" name="refer_code" value="<?php echo $res[0]['refer_code']; ?>">
                                </div>
                               </div>
                             </div>
                             <br>
                             <div class="row">
                            <div class="form-group">
                            <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Password</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="password" value="<?php echo $res[0]['password']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                             <div class="row">
                            <div class="form-group">
                            <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Account Number</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="account_num" value="<?php echo $res[0]['account_num']; ?>">
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Holder Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="holder_name" value="<?php echo $res[0]['holder_name']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">IFSC</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="ifsc" value="<?php echo $res[0]['ifsc']; ?>">
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Bank</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="bank" value="<?php echo $res[0]['bank']; ?>">
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Branch</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="branch" value="<?php echo $res[0]['branch']; ?>">
                                </div>
                               
                                </div>
                            </div>
                            <br>
                    <div class="row">
                          <div class="form-group">
                            <div class='col-md-3'>
                              <label for="">Withdrawal Status</label><br>
                                    <input type="checkbox" id="withdrawal_button" class="js-switch" <?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="withdrawal_status" name="withdrawal_status" value="<?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 1 : 0 ?>">
                                </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Recharge </label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="recharge" value="<?php echo $res[0]['recharge']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Total Recharge</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="total_recharge" value="<?php echo $res[0]['total_recharge']; ?>">
                                </div>
                            <div class="col-md-3">
                                <label for="">Income Status</label><br>
                                    <input type="checkbox" id="income_button" class="js-switch" <?= isset($res[0]['income_status']) && $res[0]['income_status'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="income_status" name="income_status" value="<?= isset($res[0]['income_status']) && $res[0]['income_status'] == 1 ? 1 : 0 ?>">
                                </div>
                           </div>
                     </div>
                     <br>
                     <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Total Income</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="total_income" value="<?php echo $res[0]['total_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Today Income</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="today_income" value="<?php echo $res[0]['today_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Device ID</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="device_id" value="<?php echo $res[0]['device_id']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Total Withdrawals</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="total_withdrawal" value="<?php echo $res[0]['total_withdrawal']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Team Income</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="team_income" value="<?php echo $res[0]['team_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                   <label for="exampleInputEmail1">Earning Wallet</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="earning_wallet" value="<?php echo $res[0]['earning_wallet']; ?>">
                                </div>
                                <div class="col-md-3">
                                   <label for="exampleInputEmail1">Bonus Wallet</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="bonus_wallet" value="<?php echo $res[0]['bonus_wallet']; ?>">
                                </div>
                                <div class="col-md-3">
                                   <label for="exampleInputEmail1">Balance</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="balance" value="<?php echo $res[0]['balance']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                              <div class="col-md-3">
                                <label for="exampleInputEmail1">Registered Datetime</label><i class="text-danger asterik">*</i>
                                    <input type="datetime-local" class="form-control" name="registered_datetime" value="<?php echo $res[0]['registered_datetime']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Blocked</label><br>
                                    <input type="checkbox" id="blocked_button" class="js-switch" <?= isset($res[0]['blocked']) && $res[0]['blocked'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="blocked" name="blocked" value="<?= isset($res[0]['blocked']) && $res[0]['blocked'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                          
                        </div>
                        <div class="box-footer">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <input type="hidden" name="address" id="address">
                        <button type="submit" class="btn btn-primary " name="btnEdit">Update</button>
                    </div>
            
                     
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<?php $db->disconnect(); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    var changeCheckbox = document.querySelector('#withdrawal_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#withdrawal_status').val(1);

        } else {
            $('#withdrawal_status').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#blocked_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#blocked').val(1);

        } else {
            $('#blocked').val(0);
            }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#income_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#income_status').val(1);

        } else {
            $('#income_status').val(0);
            }
    };
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Enable location tracking when the page loads
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async function (position) {
                let lat = position.coords.latitude;
                let lon = position.coords.longitude;

                // Set the latitude and longitude input fields
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lon;

                // Fetch the address using reverse geocoding (OpenStreetMap API)
                try {
                    let response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                    let data = await response.json();
                    // Display the fetched address
                    document.getElementById("address").value = data.display_name || "Unknown";
                } catch (error) {
                    console.error("Error fetching address:", error);
                    document.getElementById("address").value = "Unknown";
                }
            },
            function (error) {
                console.log("Geolocation Error:", error); // Log error if location services are off or denied
                document.getElementById("latitude").value = ""; // Clear latitude
                document.getElementById("longitude").value = ""; // Clear longitude
                document.getElementById("address").value = "Unknown"; // Set a default value
            },
            {
                enableHighAccuracy: true,  // Request high accuracy (GPS)
                timeout: 10000,            // Set timeout (10 seconds)
                maximumAge: 0              // Ensure fresh location (no cache)
            }
        );
    } else {
        console.log("Geolocation is not supported by this browser.");
        document.getElementById("latitude").value = "";
        document.getElementById("longitude").value = "";
        document.getElementById("address").value = "Unknown";
    }

    // Handle form submission (Update button)
    document.getElementById("edit_project_form").addEventListener("submit", function(event) {
        // Check if latitude and longitude are empty
        if (document.getElementById("latitude").value === "" || document.getElementById("longitude").value === "") {
            event.preventDefault(); // Prevent form submission
            return false;
        }
        return true; // Proceed with the form submission if location is available
    });
});

</script>
