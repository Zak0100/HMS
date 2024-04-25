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


if (isset($_SESSION['patientID'])) {
    $patientID = $_SESSION['patientID'];

    
    if (isset($_POST['submit'])) {
        
        $diagnosis = $_POST['diagnosis'];
        $bloodType = $_POST['bloodType'];
        $condition = $_POST['condition'];
        $medicalHistory = $_POST['medicalHistory'];

        if (empty($diagnosis) || empty($bloodType) || empty($condition) || empty($medicalHistory)) {
            $alertClass = 'alert-danger';
            $alertMessage = 'Please Enter All Details.';
        } 
        else {

            
            $stmt = $db->prepare("INSERT INTO healthRecord (patientID, diagnosis, bloodType, condition, medicalHistory) VALUES (?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $patientID);
            $stmt->bindParam(2, $diagnosis);
            $stmt->bindParam(3, $bloodType);
            $stmt->bindParam(4, $condition);
            $stmt->bindParam(5, $medicalHistory);

            if ($stmt->execute()) {
                
                $alertClass = 'alert-success';
                $alertMessage = 'Health record added successfully!';
            } else {
                
                $alertClass = 'alert-danger';
                $alertMessage = 'Failed to add health record. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Add Health Record</title>
    <style>
        
    </style>
</head>
<body>
   <?php include_once 'navbars/doctorNav.php'; ?>

    <div class="container">
        <div class="text-center mb-2 mt-4">
            <h3 id="h3">Add Health Record</h3>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" style="width:30vw; min-width:150px;">
                <hr>
                <div class="mb-2 text-dark">
                    <label for="diagnosis" class="form-label">Diagnosis</label>
                    <input type="text" class="form-control" name="diagnosis">
                </div>
                <div class="mb-2 text-dark">
                    <label for="bloodType" class="form-label">Blood Type</label>
                    <input type="text" class="form-control" name="bloodType">
                </div>
                <div class="mb-2 text-dark">
                    <label for="condition" class="form-label">Condition</label>
                    <input type="text" class="form-control" name="condition">
                </div>
                <div class="mb-2 text-dark">
                    <label for="medicalHistory" class="form-label">Medical History</label>
                    <input type="text" class="form-control" name="medicalHistory">
                </div>
                <hr>
                <?php if (!empty($alertMessage)): ?>
                    <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
                        <?php echo $alertMessage; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div>
                    <button type="submit" name="submit" class="btn btn-primary form-control">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
