<?php
session_start();
include_once 'db_conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

$docLastName = $_SESSION['docLastName'];  


$stmt_current = $db->prepare("SELECT a.*, p.firstName, p.lastName FROM appointment a INNER JOIN patient p ON a.patientID = p.patientID WHERE a.docLastName = ? AND a.appointmentDate >= date('now')");
$stmt_current->bindValue(1, $docLastName, SQLITE3_TEXT);
$result_current = $stmt_current->execute();


$stmt_past = $db->prepare("SELECT a.*, p.firstName, p.lastName FROM appointment a INNER JOIN patient p ON a.patientID = p.patientID WHERE a.docLastName = ? AND a.appointmentDate < date('now')");
$stmt_past->bindValue(1, $docLastName, SQLITE3_TEXT);
$result_past = $stmt_past->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Doctor's Appointments</title>
    <style>
        .card { max-width: 500px; }
    </style>
</head>
<body>
<?php include_once 'navbars/doctorNav.php'; ?>

<div class="container">
    <h1 class="mt-5" style='font-weight: 100;'>Doctor's Appointments:</h1>
    <h3 class="mt-4" style='font-weight: 100;'>Current Appointments</h3>
    <div class="row mt-3">
        <?php
        if ($result_current) {
            while ($row = $result_current->fetchArray(SQLITE3_ASSOC)) {
                ?>
                <div class="col-md-6">
                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Appointment with Patient: <?php echo $row['firstName'] . ' ' . $row['lastName'] . ' (ID: ' . $row['patientID'] . ')'; ?></h5>
                            <hr style='width: 95%;'>
                            <p class="card-text">Date: <?php echo $row['appointmentDate']; ?></p>
                            <p class="card-text">Time: <?php echo $row['appointmentTime']; ?></p>
                            <p class="card-text">Room: <?php echo $row['roomNum']; ?></p>
                            <?php if ($row['isCheckedIn'] == 1) { ?>
                                <button class="btn btn-success" disabled>Checked In</button>
                            <?php } 
                            else { ?>
                                <button class="btn btn-danger" disabled>Not Checked In</button>
                           <?php } ?>
                            
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
                            <h5 class="card-title">Appointment with Patient: <?php echo $row['firstName'] . ' ' . $row['lastName'] . ' (ID: ' . $row['patientID'] . ')'; ?></h5>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>