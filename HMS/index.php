<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Login</title>
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
        #title, #sub {
            text-align: center;
            font-weight: 100;
        }
    </style>
</head>
    <body>
        <div class="container">
            <div class="row justify-content-center align-items-center">
            <h1 id="title" class="text-light">Welcome to the Hospital Management System</h1>
                <div class="col-md-8" style="align-items: center;">
                    <hr class="text-light">
                    <h4 class="text-light" id="sub">Please Select an Option:</h4>
                </div>
                <div class="container-sm text-center mt-4">
                    <a href="patientLogin.php" class="btn btn-lg btn-secondary">Patient Login</a>
                    <a href="staffLogin.php" class="btn btn-lg btn-secondary">Staff Login</a>
                    <a href="adminLogin.php" class="btn btn-lg btn-secondary">Admin Login</a>
                    <h6 class="text-light pt-5">New Here? <a href="register.php">Click Here</a> to make an account.</h6>
                </div>
            </div>
        </div>
    </body>
</html>
