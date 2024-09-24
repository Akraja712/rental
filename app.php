<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/money.jpeg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #efefef;
        }
        .custom-container {
            width: 450px; 
            margin: 10px auto; /* Adjusted margin */
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: rgb(255, 255, 255);
        }
        .btn-custom {
            width: 100%;
            margin-top: 25px;
            border-radius: 10px;
            border: 2px solid #fed346;
        }
        .btn-customs {
            width: 100%;
            border-radius: 15px;
        }
        @media (max-width: 576px) {
            .nowrap-mobile {
                white-space: nowrap;
                font-size: 10px;
            }
            .btn-customs {
                width: 100%;
                border-radius: 15px;
                margin-top: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="custom-container">
            <center><img src="ratings.jpeg" alt="App Image" class="img-fluid"></center>
            <div class="form-group">
                <?php
                $servername = "localhost";
                $username = "u743445510_ratings_job";
                $password = "Ratingsjobs@2024";
                $database = "u743445510_ratings_job";

                $conn = new mysqli($servername, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query to get file path from app_settings table
                $sql_query = "SELECT file_upload FROM app_settings WHERE id = 1";
                $result = $conn->query($sql_query);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $file_path = $row['file_upload'];
                    $absolute_url = "https://admin.ratingsjob.online/" . $file_path; // Replace 'localhost/ratings_job/' with your actual directory path
                    echo "<a href='$absolute_url' download><button class='btn btn-primary btn-custom' style='background-color:#fed346; color:white; font-weight:bold;'>Download App Now</button></a>";
                } else {
                    echo "<p>No app file available for download.</p>";
                }

                // Close connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
