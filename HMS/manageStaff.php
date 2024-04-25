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
    $searchQuery = "SELECT staffID, firstName, lastName, email, role FROM staff WHERE 
                    firstName LIKE '%$query%' OR lastName LIKE '%$query%'";
    $result = $db->query($searchQuery);
} else {
   
    $result = $db->query("SELECT staffID, firstName, lastName, email, role FROM staff");
}



if (isset($_GET['success']) && $_GET['success'] == 1) {
    $alertClass = 'alert-success';
    $alertMessage = 'Creation Successful!';
}
elseif (isset($_GET['success']) && $_GET['success'] == 2) {
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
    <title>Manage Staff</title>
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

        #alert {
            width: 600px;
            margin-top: 2%;
            margin-left: auto;
            margin-right: auto;
        }

        #btn {
            width: 250px;
        }
        .centered-icon {
        text-align: center;
        vertical-align: middle;
        }
        @keyframes trash-edit {
            0%, 70% { transform: scale(1); }
            14%, 42% { transform: scale(1.3); }
            28% { transform: scale(1); }
            50% { opacity: 0.2; } 
        }
        
        #trash:hover, #edit:hover {
            animation: trash-edit 1.75s infinite;
        }
    </style>
</head>

<body>
    <?php include_once 'navbars/adminNav.php'; ?>

    <?php if (!empty($alertMessage)): ?>
        <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" id="alert" role="alert">
            <?php echo $alertMessage; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

        
        <div class="container-sm">
            <h2 class='mt-4' style='text-align: center; font-weight: 100;'><u>Manage Staff:</u></h2>
            <div class="row mt-2">
                <div class="col-md-6">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control mt-4" name="query"
                                placeholder="Enter name..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                            <button class="btn btn-primary mt-4" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="container-sm">
                        <a href="createStaff.php" id="btn" class="btn btn-primary mt-4">
                            Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <table class='center' border='1'>
            <tr>
                <th>StaffID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr> 
                <td>{$row['staffID']}</td>
                <td>{$row['firstName']}</td>
                <td>{$row['lastName']}</td>
                <td>{$row['email']}</td>
                <td>{$row['role']}</td>
                <td class='centered-icon'><a href='updateStaff.php?staffID={$row['staffID']}'><i class='far fa-pen-to-square' id='edit' ></i></a></td>
                <td class='centered-icon'><a href='delete.php?staffID={$row['staffID']}' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='fa-solid fa-trash' id='trash' ></i></a></td>
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
