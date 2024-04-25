<?php
include "db_conn.php";

if (isset($_GET['staffID']))
{
  $staffID = $_GET["staffID"];
  $sql = "DELETE FROM `staff` WHERE staffID = $staffID";
  $result = $db->query($sql);
} 
elseif (isset($_GET['patientID']))
{
  $patientID = $_GET["patientID"];
  $sql = "DELETE FROM `patient` WHERE patientID = $patientID";
  $result = $db->query($sql);
}
elseif (isset($_GET['appointmentID']))
{
  $appointmentID = $_GET["appointmentID"];
  $sql = "DELETE FROM `appointment` WHERE appointmentID = $appointmentID";
  $result = $db->query($sql);
}
else 
{
  echo 'Error Deleting User.';
}

if ($result && isset($_GET['staffID'])) {
  header("Location: manageStaff.php");
} 
elseif ($result && isset($_GET['patientID'])) {
  header("Location: viewPatient.php");
}
elseif ($result && isset($_GET['appointmentID'])) {
  header("Location: myAppointments.php");
}
else {
  echo "Failed Deletion.";
}