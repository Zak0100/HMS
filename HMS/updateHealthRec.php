<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

include "db_conn.php";
$patientID = $_GET["patientID"];

if(isset($_GET['patientID'])) {
    $patientID = $_GET['patientID'];

    $stmt = $db->prepare("SELECT firstName FROM patient WHERE patientID = ?");
    $stmt->bindValue(1, $patientID, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $firstName = $row['firstName'];
    } else {

        echo "Patient not found.";
        exit;
    }
} else {

    echo "Patient ID not provided.";
    exit;
}

if (isset($_POST["submit"])) {
    $diagnosis = $_POST['diagnosis'];
    $bloodType = $_POST['bloodType'];
    $condition = $_POST['condition'];
    $medicalHistory = $_POST['medicalHistory'];

    if (empty($diagnosis) || empty($bloodType) || empty($condition) || empty($medicalHistory)) {
    
    $alertClass = 'alert-danger';
    $alertMessage = 'Please Enter All Required Fields';

    }
    else
    {
        $sql = "UPDATE `healthRecord` SET `diagnosis`='$diagnosis',`bloodType`='$bloodType',`condition`='$condition',`medicalHistory`='$medicalHistory' WHERE patientID = $patientID";
        $result = $db->query($sql);
        if ($result) {
            header("Location: doctorHealth.php?success=2&patientID=$patientID");
        } else {
            echo "Failed: " . $db->lastErrorMsg();
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
    <title>Update Health Record</title>
    <style>
        #h3 {
            font-weight: 100;
        }
    </style>
</head>
    <body>
        <?php include_once 'navbars/doctorNav.php'; ?>

        <div class="container">
    <div class="text-center mb-2 mt-4">
      <h3 id="h3">Edit <?php echo $firstName ?>'s Health Record</h3>
    </div>

    <?php
    $sql = "SELECT * FROM healthRecord WHERE patientID = $patientID LIMIT 1";
    $result = $db->query($sql);
    $row = $result->fetchArray(SQLITE3_ASSOC);
    ?>

    <div class="container d-flex justify-content-center">
        <form method="post" style="width:30vw; min-width:150px;">
        <hr>
        <div class="mb-2 text-dark">
            <label for="diagnosis" class="form-label">Diagnosis</label>
            <input type="text" class="form-control" name="diagnosis" value="<?php echo $row['diagnosis'] ?>">
        </div>
        <div class="mb-2 text-dark">
            <label for="bloodType" class="form-label">Blood Type</label>
            <input type="text" class="form-control" name="bloodType" value="<?php echo $row['bloodType'] ?>">
        </div>
        <div class="mb-2 text-dark">
            <label for="condition" class="form-label">Condition</label>
            <i class="fa-solid fa-envelope"></i>
            <input type="condition" class="form-control" name="condition" value="<?php echo $row['condition'] ?>">
        </div>
        <div class="mb-2 text-dark">
            <label for="medicalHistory" class="form-label">Medical History</label>
            <i class="fa-sharp fa-regular fa-calendar-days"></i>
            <input type="text" class="form-control" name="medicalHistory" value="<?php echo $row['medicalHistory'] ?>">
        </div>
        <hr>
        <?php if (!empty($alertMessage)): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
            <div>
                <button type="submit" name="submit" class="btn btn-primary form-control">Update</button>
            </div>
            
        </div>
    </form>

    </div>
  </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" 
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>

