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

if (isset($_POST['submit'])) {
    $appointmentDate = $_POST['appointmentDate'];
    $appointmentTime = $_POST['appointmentTime'];
    $docLastName = $_POST['docLastName'];

    
    $stmt_check = $db->prepare("SELECT * FROM appointment WHERE appointmentDate = ? AND appointmentTime = ? AND docLastName = ?");
    $stmt_check->bindParam(1, $appointmentDate);
    $stmt_check->bindParam(2, $appointmentTime);
    $stmt_check->bindParam(3, $docLastName);
    $result_check = $stmt_check->execute();

    if ($result_check->fetchArray()) {
        
        $alertClass = 'alert-danger';
        $alertMessage = 'Appointment already booked for this date, time, and doctor.';
    } else {
        
        $patientID = $_SESSION['patientID'];
        $roomNum = rand(99, 999);

        
        $stmt_insert = $db->prepare("INSERT INTO appointment (patientID, appointmentDate, appointmentTime, docLastName, roomNum) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bindParam(1, $patientID);
        $stmt_insert->bindParam(2, $appointmentDate);
        $stmt_insert->bindParam(3, $appointmentTime);
        $stmt_insert->bindParam(4, $docLastName);
        $stmt_insert->bindParam(5, $roomNum);

        if ($stmt_insert->execute()) {
           
            $alertClass = 'alert-success';
            $alertMessage = 'Appointment booked successfully!';
        } else {
            
            $alertClass = 'alert-danger';
            $alertMessage = 'Failed to book appointment. Please try again.';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #header {
            font-weight: 100;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include_once 'navbars/patientNav.php'; ?>

    <div class="container d-flex justify-content-center">
        <form method="post" style="width:30vw; min-width:150px;">
            <h1 class='mt-5' id='header'>Book an Appointment</h1>
            <hr>
            <div class="mb-2 text-dark">
                <label for="appointmentDate" class="form-label">Appointment Date</label>
                <input type="date" class="form-control" name="appointmentDate" min="<?php echo date('Y-m-d', strtotime('+2 days')); ?>" required>   
            </div>
            <div class="mb-2 text-dark">
                <label for="appointmentTime" class="form-label">Appointment Time</label>
                <select class="form-select" name="appointmentTime" required>
                    <?php
                    // Define start and end time
                    $startTime = strtotime('9:00');
                    $endTime = strtotime('17:00');
                    $interval = 30 * 60; // 30 minutes in seconds

                    // Generate time options
                    for ($time = $startTime; $time <= $endTime; $time += $interval) {
                        echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-2 text-dark">
                <label for="docLastName" class="form-label">Doctor</label>
                <select class="form-select" name="docLastName" required>
                    <?php
                    // Fetch doctor details from the staff table where the role is Doctor
                    $stmt = $db->query("SELECT staffID, lastName FROM staff WHERE role = 'Doctor'");
                    while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
                        echo "<option value='{$row['lastName']}'>" . 'Dr. ' . "{$row['lastName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <hr>
            <?php
            if (!empty($alertMessage)): ?>
                <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
                    <?php echo $alertMessage; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div>
                <button type="submit" name="submit" class="btn btn-primary form-control">Book</button>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
