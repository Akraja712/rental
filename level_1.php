<?php
include_once('includes/connection.php');
session_start();

// Check if session is set
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id']; // Retrieve user ID from session

function fetchUserDetails($level, $user_id) {
    $data = array(
        "user_id" => $user_id,
        "level" => $level,
    );

    $apiUrl = API_URL . "team_list.php";
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);

    if ($response === false) {
        // Error in cURL request
        return [];
    } else {
        $responseData = json_decode($response, true);
        if ($responseData !== null && isset($responseData["success"]) && $responseData["success"]) {
            return $responseData["data"];
        } else {
            return [];
        }
    }
}

// Fetch details for each level
$userdetails_level1 = fetchUserDetails("b", $user_id);
$userdetails_level2 = fetchUserDetails("c", $user_id);
$userdetails_level3 = fetchUserDetails("d", $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/jiyo">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .info-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .info-box h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .info-box p {
            font-size: 1.25rem;
            margin: 0;
        }
        .level-container {
            display: none;
            padding: 20px; 
        }
        .btn {
            background-color: #4A148C; 
            border-color: #4A148C; 
            color: white; 
            font-weight: 600;
            border-radius: 99999px;
        }
        .btn:hover {
            color: rgb(0, 0, 0);
            background-color: #4A148C;
        }
        .table {
            margin-top: 10px;
        }
        .table thead {
            background-color: #4A148C;
            color: white;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
            <div class="mb-4">
                <button class="btn level-btn" data-level="1">Level 1</button>
                <button class="btn level-btn" data-level="2">Level 2</button>
                <button class="btn level-btn" data-level="3">Level 3</button>
            </div>
            <div class="mt-4">
                <button class="btn invite-btn-global" onclick="window.location.href='invite_friends.php'">Invite My Friends</button>
            </div>

            <!-- Level Containers -->
            <?php
            $levels = [
                '1' => $userdetails_level1,
                '2' => $userdetails_level2,
                '3' => $userdetails_level3,
            ];
            $percentages = [
                '1' => 5, // Level 1 shows 5%
                '2' => 3, // Level 2 shows 3%
                '3' => 1, // Level 3 shows 2%
            ];
            foreach ($levels as $level => $userdetails): 
                $percentage = $percentages[$level]; // Get the percentage for the current level
            ?>
                <div class="level-container" id="level<?= $level ?>-container">
                    
                    <h2>Level <?= $level ?> - <?= $percentage ?>%</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Registered Date</th>
                                    <th>Total Income</th>
                                    <th>My Level Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($userdetails)): ?>
                                    <?php foreach ($userdetails as $index => $item): ?>
                                        <?php 
                                            // Calculate level income based on percentage
                                            $level_income = ($item['total_income'] * $percentage) / 100;
                                        ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($item['name']) ?></td>
                                            <td><?= htmlspecialchars($item['mobile']) ?></td>
                                            <td><?= htmlspecialchars($item['registered_datetime']) ?></td>
                                            <td><?= htmlspecialchars($item['total_income']) ?></td>
                                            <td><?= number_format($level_income, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center">No data found.</td></tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const levelButtons = document.querySelectorAll('.level-btn');
    const levelContainers = document.querySelectorAll('.level-container');

    levelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const level = this.getAttribute('data-level');

            // Enable all buttons first
            levelButtons.forEach(btn => {
                btn.disabled = false;
            });

            // Disable the current button
            this.disabled = true;

            // Hide all level containers
            levelContainers.forEach(container => {
                container.style.display = 'none';
            });

            // Show the selected level's container
            document.getElementById(`level${level}-container`).style.display = 'block';
        });
    });

    // Default to Level 1 on load
    document.getElementById('level1-container').style.display = 'block';
    levelButtons[0].disabled = true; // Disable Level 1 button initially
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
