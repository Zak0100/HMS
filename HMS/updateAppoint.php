<?php
session_start();

include_once 'db_conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['appointmentID'])) {
    $appointmentID = $_GET['appointmentID'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $appointmentDate = $_POST['appointmentDate'];
        $appointmentTime = $_POST['appointmentTime'];
        $docLastName = $_POST['docLastName'];
        $roomNum = rand(99, 999);

        $stmt = $db->prepare("UPDATE appointment SET appointmentDate = ?, appointmentTime = ?, docLastName = ?, roomNum = ? WHERE appointmentID = ?");
        $stmt->bindParam(1, $appointmentDate);
        $stmt->bindParam(2, $appointmentTime);
        $stmt->bindParam(3, $docLastName);
        $stmt->bindParam(4, $roomNum);
        $stmt->bindParam(5, $appointmentID);


        if ($stmt->execute()) {

            $alertClass = 'alert-success';
            $alertMessage = 'Appointment updated successfully!';
        } else {

            $alertClass = 'alert-danger';
            $alertMessage = 'Failed to update appointment. Please try again.';
        }
    }


    $stmt_fetch = $db->prepare("SELECT * FROM appointment WHERE appointmentID = ?");
    $stmt_fetch->bindParam(1, $appointmentID);
    $result_fetch = $stmt_fetch->execute();
    $appointment = $result_fetch->fetchArray(SQLITE3_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
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
            <h1 class='mt-5' id='header'>Update Appointment</h1>
            <hr>
            <div class="mb-2 text-dark">
                <label for="appointmentDate" class="form-label">Appointment Date</label>
                <input type="date" class="form-control" name="appointmentDate" value="<?php echo $appointment['appointmentDate']; ?>" 
                min="<?php echo date('Y-m-d', strtotime('+2 days')); ?>" required>
            </div>
            <div class="mb-2 text-dark">
                <label for="appointmentTime" class="form-label">Appointment Time</label>
                <select class="form-select" name="appointmentTime" required>
                    <?php
                    $startTime = strtotime('9:00');
                    $endTime = strtotime('17:00');
                    $interval = 30 * 60; 

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
                <button type="submit" name="submit" class="btn btn-primary form-control">Update</button>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
