<head>  
  <style>
    .navbar {
      background-color: #36394d;
      padding: 35px;
    }
    .navbar, #title {
      font-weight: 100;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <h3 id="title" class="text-light">Hospital Management System</h3>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto justify-content-between" style="width: 45%;">
        <li class="nav-item">
          <a class="nav-link" href="viewPatient.php">Manage Patient
                <i class="fa-solid fa-book"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manageStaff.php">Manage Staff
                <i class="fa-solid fa-users"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav">
      <li class="nav-item">
            <a class="nav-link" href="#"><?php echo $_SESSION['firstName'] . " (Admin)"; ?>
                <i class="fa-solid fa-user"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </li>
      </ul>
      
    </div>
  </div>
</nav>
