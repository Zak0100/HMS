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


if (isset($_GET['query'])) {
    $query = $_GET['query'];
    
    $searchQuery = "SELECT patientID, firstName, lastName, DOB, email, gender FROM patient WHERE 
                    firstName LIKE '%$query%' OR lastName LIKE '%$query%' OR patientID LIKE '%$query%'";
    
    $result = $db->query($searchQuery);
} else {

    $result = $db->query("SELECT patientID, firstName, lastName, DOB, email, gender FROM patient");
}


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
    <title>View Patients</title>
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
        @keyframes trash-edit-medical {
            0%, 70% { transform: scale(1); }
            14%, 42% { transform: scale(1.3); }
            28% { transform: scale(1); }
            50% { opacity: 0.2; } 
        }
        
        #trash:hover, #edit:hover, #medical:hover {
            animation: trash-edit-medical 1.75s infinite;
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

<?php  if (!empty($alertMessage)): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

    <div class="center">
    <?php if($_SESSION['role'] !== 'Doctor') { ?>
    <h2 class='mt-4 mb-4' style='text-align: center; font-weight: 100;'><u>Manage Patient:</u></h2>
    <?php } ?>
        <div class="container-sm">
            <form method="GET" action="">
                <div class="input-group mb-3 mt-1">
                    <input type="text" id="searchInput" class="form-control" name="query" placeholder="Enter name..."
                        value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
                        pattern="[a-zA-Z0-9\s]+" title="Only letters and numbers are allowed">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>


        <table class='center' border='1'>
            <tr>
                <th>PatientID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>DOB</th>
                <th>Email</th>
                <th>Gender</th>
                <?php if ($_SESSION['role'] != "Doctor"): ?>
                    <th>Update</th>
                    <th>Delete</th>
                <?php else: ?>
                    <th>Health Record</th>
                <?php endif; ?>
            </tr>
            <?php
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<tr>
                    <td>{$row['patientID']}</td>
                    <td>{$row['firstName']}</td>
                    <td>{$row['lastName']}</td>
                    <td>{$row['DOB']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['gender']}</td>";

                if ($_SESSION['role'] != "Doctor") {
                    echo "<td class='centered-icon'><a href='updatePatient.php?patientID={$row['patientID']}'><i class='far fa-pen-to-square' id='edit' ></i></a></td>
                        <td class='centered-icon'><a href='delete.php?patientID={$row['patientID']}' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='fa-solid fa-trash' id='trash' ></i></a></td>";
                } else {
                    echo "<td class='centered-icon'><a href='doctorHealth.php?patientID={$row['patientID']}'><i class='fa-solid fa-file-medical' id='medical' ></i></a></td>";
                    
                }

                echo "</tr>";

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
