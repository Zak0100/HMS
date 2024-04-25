<?php
session_start();

include_once 'db_conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        
        $alertClass = 'alert-danger';
        $alertMessage = 'Please Enter All Required Fields';
    } else {
        
        $checkQuery = "SELECT COUNT(*) as count FROM staff WHERE email = :email";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $result = $checkStmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row['count'] > 0) {
            
            $alertClass = 'alert-danger';
            $alertMessage = 'Email already exists. Please use a different email address.';
        } else {
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            
            $insertQuery = "INSERT INTO staff (firstName, lastName, email, password, role) 
                VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '$role')";

            if ($db->exec($insertQuery)) {
                header("Location: manageStaff.php?success=1");
                exit;
            } else {
                echo "Failed: " . $db->lastErrorMsg();
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
        <title>Create Staff</title>
        <style>
            
        </style>
    </head>
    <body>
    <?php include_once 'navbars/adminNav.php'; ?>
        
    <div class="container">
            <div class="row justify-content-center align-items-center">
                <h1 class="text-dark mt-4"style="text-align: center;">Add User</h1>
                <div class="col-md-6">
                    <hr class="text-dark">
                    <form action="" method="post">
                        <div class="mb-2 text-dark">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstName">
                        </div>
                        <div class="mb-2 text-dark">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastName">
                        </div>
                        <div class="mb-2 text-dark">
                            <label for="email" class="form-label">Email</label>
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-2 text-dark">
                            <label for="password" class="form-label">Password</label>
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-2 text-dark">
                            <label for="gender" class="form-label">Role</label>
                            <i class="fa-solid fa-list"></i>
                            <select class="form-control" id="role" name="role" required>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Doctor">Doctor</option>
                            </select>
                        </div>
                        <hr>
                        <?php if (!empty($alertMessage)): ?>
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
                                    <button type="submit" name="submit" class="btn btn-primary form-control">Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html