<?php
session_start();

if (isset($_SESSION['email']) && isset($_SESSION['firstName'])) {
    $firstName = $_SESSION['firstName'];
} else {
    
    header('Location: index.php');
    exit;
}

include_once 'db_conn.php';

if (!$db) {
    die("Connection failed: " . $db->lastErrorMsg());
}

if(isset($_GET['patientID'])) {
    $patientID = $_GET['patientID'];

    
    $stmt = $db->prepare("SELECT firstName FROM patient WHERE patientID = ?");
    $stmt->bindValue(1, $patientID, SQLITE3_INTEGER);
    $result = $stmt->execute();

    
    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $firstName = $row['firstName'];

        $_SESSION['patientID'] = $patientID;
    } else {
        
        echo "Patient not found.";
        exit;
    }
} else {
    
    echo "Patient ID not provided.";
    exit;
}

$result = $db->query("SELECT * FROM healthRecord WHERE patientID = $patientID");

if (isset($_GET['success']) && $_GET['success'] == 2) {
    $alertClass = 'alert-success';
    $alertMessage = 'Update Successful!';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Patient Health</title>
    <style>
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: blanchedalmond;
        }

        .center {
            margin-left: auto;
            margin-right: auto;
            margin-top: 2%;
        }
        .container-sm {
            width: 600px;
        }
        .centered-icon {
        text-align: center;
        vertical-align: middle;
        }
        #alert {
            width: 600px;
            margin-top: 2%;
            margin-left: auto;
            margin-right: auto;
        }
        #h1 {
            font-weight: 100;
            text-align: center;
        }
        @keyframes edit {
            0%, 70% { transform: scale(1); }
            14%, 42% { transform: scale(1.3); }
            28% { transform: scale(1); }
        }
        
        #edit:hover {
            animation: edit 1s infinite;
        }

    </style>
</head>

<body>
   <?php
    if($_SESSION['role'] == 'Doctor') {
        include_once 'navbars/doctorNav.php';
    }
    else {
        include_once 'navbars/adminNav.php';
    }
    ?>
        <h1 class="mt-4 mb-2" id="h1"><?php echo $firstName?>'s Records:</h1>

        <?php          
        if (!empty($alertMessage)): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-auto">
                <a href="addHealthRec.php" class="btn btn-primary mt-3">Add New Health Record</a>
                </div>
            </div>
        </div>

        <table class='center' border='1'>
            <tr>
                <th>Record ID</th>
                <th>Patient ID</th>
                <th>Diagnosis</th>
                <th>Blood Type</th>
                <th>Condition</th>
                <th>Medical History</th>
                <th>Update</th>
            </tr>
            <?php
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<tr>
                    <td>{$row['recordID']}</td>
                    <td>{$row['patientID']}</td>
                    <td>{$row['diagnosis']}</td>
                    <td>{$row['bloodType']}</td>
                    <td>{$row['condition']}</td>
                    <td>{$row['medicalHistory']}</td>
                    <td class='centered-icon'><a href='updateHealthRec.php?patientID=
                    {$row['patientID']}'><i class='far fa-pen-to-square' id='edit'></i></a></td>

                </tr>";

            }
            ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
$db->close();
?>
