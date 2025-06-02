<?php
session_start();
include('connectdb.php');
$conn = openCon();

if (isset($_POST['timein_btn'])) {
  $vehicleID = $_POST['vehicle_id'];
  $guardID = $_SESSION['guard_id'];

  // Fetch assigned gate from the guard account
  $gateQuery = "SELECT GateID FROM guard WHERE GuardID = ?";
  $stmt = mysqli_prepare($conn, $gateQuery);
  mysqli_stmt_bind_param($stmt, "i", $guardID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $gateRow = mysqli_fetch_assoc($result);
  $gateID = $gateRow['GateID'];

  // Insert into vehicle_log
  $insertQuery = "INSERT INTO vehicle_log (VehicleID, GateID, GuardID, TimeIn, Status) 
                  VALUES (?, ?, ?, NOW(), 'Inside')";
  $stmtInsert = mysqli_prepare($conn, $insertQuery);
  mysqli_stmt_bind_param($stmtInsert, "isi", $vehicleID, $gateID, $guardID);

  if (mysqli_stmt_execute($stmtInsert)) {
    header("Location: guarddashboard.php?success=1");
    exit();
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
