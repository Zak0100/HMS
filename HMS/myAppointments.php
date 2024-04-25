<?php
session_start();

include_once 'db_conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

$patientID = $_SESSION['patientID'];


$stmt_current = $db->prepare("SELECT * FROM appointment WHERE patientID = ? AND appointmentDate >= date('now')");
$stmt_current->bindValue(1, $patientID, SQLITE3_INTEGER);
$result_current = $stmt_current->execute();

$stmt_past = $db->prepare("SELECT * FROM appointment WHERE patientID = ? AND appointmentDate < date('now')");
$stmt_past->bindValue(1, $patientID, SQLITE3_INTEGER);
$result_past = $stmt_past->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>My Appointments</title>
    <style>
        .card {
            max-width: 500px;
        }
    </style>
</head>
<body>
    <?php include_once 'navbars/patientNav.php'; ?>

    <div class="container">
        <h1 class="mt-5" style='font-weight: 100;'>My Appointments:</h1>
        <h3 class="mt-4" style='font-weight: 100;'>Current Appointments</h3>
        <div class="row mt-3">
            <?php
            if ($result_current) {
                while ($row = $result_current->fetchArray(SQLITE3_ASSOC)) {
            ?>
                    <div class="col-md-6">
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Appointment With Dr. <?php echo $row['docLastName'] ?></h5>
                                <hr style='width: 95%;'>
                                <p class="card-text">Date: <?php echo $row['appointmentDate']; ?></p>
                                <p class="card-text">Time: <?php echo $row['appointmentTime']; ?></p>
                                <p class="card-text">Room: <?php echo $row['roomNum']; ?></p>
                                <a href="delete.php?appointmentID=<?php echo $row['appointmentID']; ?>" class="btn btn-danger" onclick='return confirm("Are you sure you want to delete this record?")'>Cancel</a>
                                <a href="updateAppoint.php?appointmentID=<?php echo $row['appointmentID']; ?>" class="btn btn-primary">Ammend</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo 'No current appointments found.';
            }
            ?>
        </div>

        <h3 class="mt-5" style='font-weight: 100;'>Past Appointments</h3>
        <div class="row mt-3">
            <?php
            if ($result_past) {
                while ($row = $result_past->fetchArray(SQLITE3_ASSOC)) {
            ?>
                    <div class="col-md-6">
                        <div class="card shadow-sm text-muted mb-5">
                            <div class="card-body">
                                <h5 class="card-title">Appointment With Dr. <?php echo $row['docLastName'] ?></h5>
                                <hr style='width: 95%;'>
                                <p class="card-text">Date: <?php echo $row['appointmentDate']; ?></p>
                                <p class="card-text">Time: <?php echo $row['appointmentTime']; ?></p>
                                <p class="card-text">Room: <?php echo $row['roomNum']; ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo 'No past appointments found.';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
