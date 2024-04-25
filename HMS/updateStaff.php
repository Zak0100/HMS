<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

include "db_conn.php";
$staffID = $_GET["staffID"];


if (isset($_POST["submit"])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (empty($firstName) || empty($lastName) || empty($email)) {
    
    $alertClass = 'alert-danger';
    $alertMessage = 'Please Enter All Required Fields';

    }
    else
    {
    $sql = "UPDATE `staff` SET `firstName`='$firstName',`lastName`='$lastName',`email`='$email',`role`='$role' WHERE staffID = $staffID";

    $result = $db->query($sql);

    if ($result) {
        header("Location: manageStaff.php?success=2");
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
    <title>Update Staff Record</title>
    <style>
        
    </style>
</head>
    <body>
        <?php include_once 'navbars/adminNav.php'; ?>

        <div class="container">
    <div class="text-center mb-2 mt-4">
      <h3>Edit User Information</h3>
    </div>

    <?php
    $sql = "SELECT * FROM staff WHERE staffID = $staffID LIMIT 1";
    $result = $db->query($sql);
    $row = $result->fetchArray(SQLITE3_ASSOC);
    ?>

    <div class="container d-flex justify-content-center">
        <form method="post" style="width:30vw; min-width:150px;">
        <hr>
        <div class="mb-2 text-dark">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" name="firstName" value="<?php echo $row['firstName'] ?>">
        </div>
        <div class="mb-2 text-dark">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="lastName" value="<?php echo $row['lastName'] ?>">
        </div>
        <div class="mb-2 text-dark">
            <label for="email" class="form-label">Email</label>
            <i class="fa-solid fa-envelope"></i>
            <input type="email" class="form-control" name="email" value="<?php echo $row['email'] ?>">
        </div>
        <div class="mb-2 text-dark">
            <label for="gender" class="form-label">Role</label>
            <i class="fa-solid fa-list"></i>
            <select class="form-control" id="role" name="role" required>
                <option value="Receptionist" <?php echo ($row['role'] == 'Receptionist') ? "selected" : ""; ?>>Receptionist</option>
                <option value="Doctor" <?php echo ($row['role'] == 'Doctor') ? "selected" : ""; ?>>Doctor</option>
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
        <div class="row">
            <div class="col-md-6">
                <div>
                    <a class="btn btn-danger form-control" href="manageStaff.php" role="button">Cancel</a>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <button type="submit" name="submit" class="btn btn-primary form-control">Update</button>
                </div>
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

