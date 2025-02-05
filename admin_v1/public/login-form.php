<?php
include('./includes/variables.php');
include_once('includes/custom-functions.php');
$fn = new custom_functions;
if (isset($_POST['btnLogin'])) {
    // get email and password
    $email = $db->escapeString($_POST['email']);
    $password = $db->escapeString($_POST['password']);

    
    // Get location data
    $latitude = isset($_POST['latitude']) ? $db->escapeString($_POST['latitude']) : null;
    $longitude = isset($_POST['longitude']) ? $db->escapeString($_POST['longitude']) : null;
    $address = isset($_POST['address']) ? $db->escapeString($_POST['address']) : "Unknown";

    // set time for session timeout
    $currentTime = time() + 25200;
    $expired = 3600;
    // create array variable to handle error
    $error = array();
    // check whether $email is empty or not
    if (empty($email)) {
        $error['email'] = "*Email should be filled.";
    }
    // check whether $password is empty or not
    if (empty($password)) {
        $error['password'] = "*Password should be filled.";
    }
    // if email and password is not empty, check in database
    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM admin WHERE id = 1";
        $db->sql($sql);
        $res = $db->getResult();
        $d_email = $res[0]['email']; // Fetch the count from the result
        $d_password = $res[0]['password'];
        $encrypt_password = md5($password);
        if ($email == $d_email && $encrypt_password == $d_password) {
                $_SESSION['id'] = '1';
                $_SESSION['role'] ='admin';
                $_SESSION['username'] = 'admin';
                $_SESSION['email'] = $email;
                $_SESSION['timeout'] = $currentTime + $expired;

              // Insert login tracking data
              $type = 'login';
              $datetime = date('Y-m-d H:i:s');
              $sql = "INSERT INTO tracking (type, datetime, latitude, longitude, address) 
                      VALUES ('$type', '$datetime', '$latitude', '$longitude', '$address')";
              $db->sql($sql);
                
                header("location: home.php");
        } else {
            $error['failed'] = "<span class='label label-danger'>Invalid Email or Password!</span>";
        }
    }
}
?>
<?php echo isset($error['update_user']) ? $error['update_user'] : ''; ?>
<div class="col-md-4 col-md-offset-4 " style="margin-top:150px;">
    <!-- general form elements -->
    <div class='row'>
        <div class="col-md-12 text-center">
            <img src="dist/img/jiyo.jpeg" height="110">
            <h3>Jiyo-Dashboard</h3>
        </div>
        <div class="box box-info col-md-12">
            <div class="box-header with-border">
                <h3 class="box-title">Admin Login</h3>
                <center>
                    <div class="msg"><?php echo isset($error['failed']) ? $error['failed'] : ''; ?></div>
                </center>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form method="post" enctype="multipart/form-data" id="loginForm">
            <div class="box-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email :</label>
                    <input type="text" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Password :</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <!-- Hidden Fields for Location -->
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="address" id="address">

                <div class="box-footer">
                <button type="submit" name="btnLogin" id="btnLogin" class="btn btn-info pull-left" disabled>Login</button>

                </div>
            </div>
        </form>
        </div><!-- /.box -->
    </div>
</div>
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async function (position) {
                let lat = position.coords.latitude;
                let lon = position.coords.longitude;

                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lon;

                // Fetch address using reverse geocoding (OpenStreetMap API)
                try {
                    let response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                    let data = await response.json();
                    document.getElementById("address").value = data.display_name || "Unknown";
                } catch (error) {
                    console.error("Error fetching address:", error);
                    document.getElementById("address").value = "Unknown";
                }
                
                // Enable the login button after location is received
                document.getElementById("btnLogin").disabled = false;
            },
            function (error) {
                console.log("Geolocation Error:", error); // Log error instead of showing alert
                document.getElementById("btnLogin").disabled = true; // Disable login button
            },
            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
        );
    } else {
        console.log("Geolocation is not supported by this browser."); // Log message instead of alert
        document.getElementById("btnLogin").disabled = true;
    }
});
</script>


<?php include('footer.php'); ?>