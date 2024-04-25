<?php

include_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $DOB = $_POST['DOB'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $gender = $_POST['gender'];

    
    $query = $db->prepare("INSERT INTO patient (firstName, lastName, DOB, email, password, gender) 
    VALUES (:firstName, :lastName, :DOB, :email, :password, :gender)");
    $query->bindParam(':firstName', $firstName);
    $query->bindParam(':lastName', $lastName);
    $query->bindParam(':DOB', $DOB);
    $query->bindParam(':email', $email);
    $query->bindParam(':password', $hashedPassword);
    $query->bindParam(':gender', $gender);

    if ($query->execute()) {
        $alertClass = 'alert-success';
        $alertMessage = 'Registration Successful!' . '<a href="patientLogin.php"> Click here to Login</a>';
    } else {
        $alertClass = 'alert-danger';
        $alertMessage = 'Error During Registration.';
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
        <title>Patient Registration</title>
        <style>
            body {
            background-image: url("img/bg1.jpg");
            background-size: cover; 
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }
        #title, #sub {
            text-align: center;
            font-weight: 100;
        }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="row justify-content-center align-items-center">
                <h1 class="text-light mt-5" id="title">Create Your Account</h1>
                <div class="col-md-5">
                    <hr class="text-light">
                    <h4 class="text-light mb-4" id="sub">Please Enter Your Details:</h4>
                    <?php if (!empty($alertMessage)): ?>
                        <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                            <?php echo $alertMessage; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="mb-2 text-light">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstName">
                        </div>
                        <div class="mb-2 text-light">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastName">
                        </div>
                        <div class="mb-2 text-light">
                            <label for="DOB" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="DOB" 
                            max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required>
                        </div>
                        <div class="mb-2 text-light">
                            <label for="email" class="form-label">Email</label>
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-2 text-light">
                            <label for="password" class="form-label">Password</label>
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-2 text-light">
                            <label for="gender" class="form-label">Gender</label>
                            <i class="fa-solid fa-list"></i>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <hr>
                        <div class="row mt-4">
                            <button type="submit" class="btn btn-primary mb-5">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>

