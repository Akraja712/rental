<?php
// includes/crud.php
$servername = "localhost";
$username = "u743445510_pocketfarm";
$password = "Pocketfarm@0111";
$dbname = "u743445510_pocketfarm";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

if ($conn) {
    // Get today's date
    $today = date('Y-m-d');

    // Query to retrieve today's uploaded images
    $sql = "SELECT image FROM recharge WHERE DATE(datetime) = '$today'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Create a zip archive
        $zip = new ZipArchive();
        $zipFileName = 'today_images.zip';
        $zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Add each image file to the zip archive
        while ($row = mysqli_fetch_assoc($result)) {
            $imageUrl = $row['image'];
        
            $zip->addFromString(basename($imageUrl), file_get_contents($imageUrl));
        }

        // Close the zip archive
        $zip->close();

        // Set headers for zip file download
        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename=\"$zipFileName\"");
        header('Content-Length: ' . filesize($zipFileName));
        header("Pragma: no-cache");
        header("Expires: 0");

        // Send the zip file for download
        readfile($zipFileName);

        // Delete the temporary zip file
        unlink($zipFileName);
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
} else {
    echo "Failed to connect to the database.";
}
?>
