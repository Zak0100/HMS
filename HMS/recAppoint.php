<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Receptionist') {
    header('Location: index.php'); 
    exit;
}

include_once 'db_conn.php';


if (isset($_GET['appointmentID'])) {
    $appointmentID = $_GET['appointmentID'];

    
    $stmt = $db->prepare("UPDATE appointment SET isCheckedIn = 1 WHERE appointmentID = ?");
    $stmt->bindParam(1, $appointmentID);

    
    if ($stmt->execute()) {
        $alertClass = 'alert-success';
        $alertMessage = 'Successfully Checked In.';
    } else {
        $alertClass = 'alert-danger';
        $alertMessage = 'Failed to Check In.';
    }
}


$stmt = $db->prepare("SELECT appointment.*, patient.firstName, patient.lastName FROM appointment INNER JOIN patient ON appointment.patientID = patient.patientID WHERE appointment.appointmentDate >= date('now') AND appointment.isCheckedIn = 0 ORDER BY appointment.appointmentDate ASC, appointment.appointmentTime ASC");
$result = $stmt->execute();


$stmtCheckedIn = $db->prepare("SELECT appointment.*, patient.firstName, patient.lastName FROM appointment INNER JOIN patient ON appointment.patientID = patient.patientID WHERE appointment.isCheckedIn = 1 ORDER BY appointment.appointmentDate ASC, appointment.appointmentTime ASC");
$resultCheckedIn = $stmtCheckedIn->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #header {
            font-weight: 100;
        }
        .card {
            max-width: 500px;
            min-width: 400px;
        }
    </style>
</head>
<body>
    <?php include_once 'navbars/receptionistNav.php'; ?>

    <div class="container">
        <h1 class="mt-5 mb-4" id='header'>Upcoming Appointments</h1>
        <hr style='width: 75%'>
        <?php          
        if (!empty($alertMessage)): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php
            if ($result) {
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            ?>
            <div class="col py-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Appointment For <?php echo $row['firstName'] . ' ' . $row['lastName'] . ' (ID: ' . $row['patientID'] . ')'; ?></h5>
                        <hr style='width: 95%'>
                        <p class="card-text"><b>With:</b> <?php echo 'Dr. ' . $row['docLastName']; ?></p>
                        <p class="card-text"><b>Date:</b> <?php echo $row['appointmentDate']; ?></p>
                        <p class="card-text"><b>Time:</b> <?php echo $row['appointmentTime']; ?></p>
                        <p class="card-text"><b>Room:</b> <?php echo $row['roomNum']; ?></p>
                        <?php if (!$row['isCheckedIn']) { ?>
                        <a href="recAppoint.php?appointmentID=<?php echo $row['appointmentID']; ?>" class="btn btn-primary">Check-In</a>
                        <?php } else { ?>
                        <button class="btn btn-success" disabled>Checked In</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>No upcoming appointments found.</p>";
            }
            ?>
        </div>
        
       
        <h1 class="mt-5 mb-4" id='header'>Checked-In Appointments</h1>
        <hr style='width: 75%'>
        <div class="row">
            <?php
            if ($resultCheckedIn) {
                while ($rowCheckedIn = $resultCheckedIn->fetchArray(SQLITE3_ASSOC)) {
            ?>
            <div class="col py-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Appointment For <?php echo $rowCheckedIn['firstName'] . ' ' . $rowCheckedIn['lastName'] . ' (ID: ' . $rowCheckedIn['patientID'] . ')'; ?></h5>
                        <hr style='width: 95%'>
                        <p class="card-text"><b>With:</b> <?php echo 'Dr. ' . $rowCheckedIn['docLastName']; ?></p>
                        <p class="card-text"><b>Date:</b> <?php echo $rowCheckedIn['appointmentDate']; ?></p>
                        <p class="card-text"><b>Time:</b> <?php echo $rowCheckedIn['appointmentTime']; ?></p>
                        <p class="card-text"><b>Room:</b> <?php echo $rowCheckedIn['roomNum']; ?></p>
                        <button class="btn btn-success" disabled>Checked In</button>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>No checked-in appointments found.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
