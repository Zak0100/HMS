<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

include_once 'db_conn.php';

if (!$db) {
    die("Connection failed: " . $db->lastErrorMsg());
}

$patientID = $_SESSION['patientID'];

$result = $db->query("SELECT hr.recordID, hr.diagnosis, hr.bloodType, hr.medicalHistory, p.firstName, p.lastName, p.DOB 
                      FROM healthRecord hr
                      INNER JOIN patient p ON hr.patientID = p.patientID
                      WHERE hr.patientID = '$patientID'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Health Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .center {
            margin-top: 2%;
        }
        #card {
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        #header {
            font-weight: 100;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include_once 'navbars/patientNav.php'; ?>
    <div class="center container">
        <h1 class='mb-4 mt-3' id='header' >My Health Records</h1>
            <?php
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<div id='card' class='col-md-6 shadow-sm'>
                        <div class='card mb-3'>
                            <div class='card-body'>
                            <h2 class='mb-4' style='font-weight: 100;'>General</h2>
                                <p class='card-text'><b>First Name:</b> {$row['firstName']}</p>
                                <p class='card-text'><b>Last Name:</b> {$row['lastName']}</p>
                                <p class='card-text'><b>Date of Birth:</b> {$row['DOB']}</p>
                                <p class='card-text'><b>Diagnosis:</b> {$row['diagnosis']}</p>
                                <p class='card-text'><b>Blood Type:</b> {$row['bloodType']}</p>
                                <p class='card-text'><b>Medical History:</b> {$row['medicalHistory']}</p>
                            </div>
                        </div>
                    </div>";
            }
            ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$db->close();
?>
