<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Details</title>
    <link rel="icon" type="image/x-icon" href="admin_v1/dist/img/money.jpeg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* General container styles */
        .friends-container {
            position: relative;
            padding: 40px 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            border: 1px solid #ddd;
        }

        /* Headings */
        h2 {
            margin-bottom: 20px;
            font-size: 2.75rem;
            font-weight: 700;
            color: #3eb3a8;
            text-align: center;
            border-bottom: 3px solid #3eb3a8;
            padding-bottom: 10px;
        }
        
        /* Sub-headings */
        h4 {
            font-size: 1.6rem;
            color: #3eb3a8;
            margin-bottom: 10px;
        }

        /* Paragraph text */
        p {
            font-size: 1.3rem;
            color: #495057;
            margin-bottom: 20px;
        }

        /* Button styles */
        .btn-custom {
            font-size: 1.1rem;
            padding: 12px 24px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.3s;
            border: 2px solid #3eb3a8;
            background-color: #3eb3a8;
            color: #ffffff;
        }

        .btn-custom:hover {
            background-color: #3eb3a8;
            transform: translateY(-3px);
            border: 2px solid #3eb3a8;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            h2 {
                font-size: 2.25rem;
            }

            h4 {
                font-size: 1.4rem;
            }

            p {
                font-size: 1.1rem;
            }

            .btn-custom {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <?php include_once('sidebar.php'); ?>
        <div class="col py-3">
         <div class="col-md-8">
            <div class="friends-container">
                <h2>Company Details</h2>
                <!-- Company Details -->
                <div class="mt-4">
                    <h4>Company Name:</h4>
                    <p>Money Book</p>

                    <h4>Company Address:</h4>
                    <p>Shakti Tower 1, 766, Anna Salai, Thousand Lights, Chennai, Tamil Nadu 600002</p>

                    <h4>Company Registered Certificate:</h4>
                    <button class="btn btn-custom" onclick="viewCertificate()">
                        <i class="bi bi-file-earmark-pdf"></i> View Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function viewCertificate() {
        window.open('pdf/register_certificate.pdf', '_blank');
    }
</script>
</body>
</html>
