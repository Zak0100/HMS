<head>  
  <style>
    .navbar {
      background-color: #b2b9f7;
      padding: 35px;
    }
    .navbar, #title {
      font-weight: 100;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <h3 id="title" class="text-dark">Hospital Management System</h3>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto justify-content-between" style="width: 60%;">
        <li class="nav-item">
          <a class="nav-link" href="myHealthRecords.php">My Health Records
                <i class="fa-solid fa-book"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="myAppointments.php">My Appointments
            <i class="fa-solid fa-calendar-days"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="book.php">Book an Appointment
            <i class="fa-regular fa-clock"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav">
      <li class="nav-item">
            <a class="nav-link" href="myDetails.php"><?php echo $_SESSION['firstName'] . " (Patient)"; ?>
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