<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include_once 'db_conn.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = '';
    if (empty($email) || empty($password)) {
        $error .= "Please fill all details.";
    }

    if (empty($error)) {

        $query = "SELECT * FROM staff WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($row = $result->fetchArray()) {

            if (password_verify($password, $row['password'])) {

                $_SESSION['email'] = $row['email'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] === 'Doctor') {
                    $_SESSION['docLastName'] = $row['lastName'];
                    header('Location: doctorAppoint.php'); 
                } else if ($row['role'] === 'Receptionist') {
                    header('Location: recAppoint.php'); 
                }
                exit;
            } else {
                
                $error = "Incorrect password.";
            }
        } else {
            
            $error = "User not found.";
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
    <title>Staff Login</title>
    <style>
        body {
            background-image: url("img/bg1.jpg");
            background-size: cover; 
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            padding-top: 10%;
        }
        #sub {
            text-align: center;
            font-weight: 100;
        }
    </style>
</head>
    <body>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <h1 class="text-light mb-4" id="sub">Welcome to the Staff Login:</h1>
                    <hr class="text-light">
                    <form action="" method="post">
                        <div class="mb-3 text-light">
                            <label for="email" class="form-label"><b>Email</b></label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3 text-light">
                            <label for="password" class="form-label"><b>Password</b></label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <hr>
                        <?php if(isset($error) && !empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                         <?php endif; ?>
                        <div class="row mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</html>









