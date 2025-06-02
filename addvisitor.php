<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openCon();

    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $contactNumber = $_POST['ContactNumber'];
    $scheduledVisit = $_POST['ScheduledVisit'];
    $vehicleModel = $_POST['VehicleModel'];

    $sql = "INSERT INTO visitor (LastName, FirstName, MiddleName, ContactNumber, ScheduledVisit, VehicleModel) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $lastName, $firstName, $middleName, $contactNumber, $scheduledVisit, $vehicleModel);

    if ($stmt->execute()) {
        header("Location: manage_visitor.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    closeCon($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Visitor</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url("Assets/langit.jpg") no-repeat center center fixed;
      background-size: cover;
      padding: 20px;
    }
    nav {
      background-color: maroon;
      padding: 15px;
      margin-bottom: 20px;
    }
    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }
    h1 {
      color: maroon;
    }
    form {
        background: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        margin: auto;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    form input[type="text"], form input[type="number"], form input[type="datetime-local"] {
        width: 90%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: 'Poppins', sans-serif;
    }
    form button {
        background-color: maroon;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
    }
    form button:hover {
        background-color: #800000;
    }
    label {
        display: block;
        margin-top: 10px;
    }
    p {
        margin-top: 2px;
        margin-bottom: 2px;
    }
  </style>
</head>
<body>

<nav>
  <a href="adminlogin.php" style="float: right; color: white; font-weight: bold;">Logout</a>
  <a href="admindashboard.php" style="float: right; color: white; font-weight: bold;">Admin Dashboard</a>
  <a href="manage_visitor.php" style="color: white; font-weight: bold;">Manage Visitors</a>
</nav>

<form method="POST" action="">
    <h1 style="text-align: center; color: maroon; margin-top: 0;">Register New Visitor</h1>
    
    <input type="text" name="LastName" placeholder="Last Name" required>

    <input type="text" name="FirstName" placeholder="First Name" required>

    <input type="text" name="MiddleName" placeholder="Middle Name">

    <input type="number" name="ContactNumber" placeholder="Contact Number" required>

    <input type="datetime-local" name="ScheduledVisit" placeholder="Scheduled Visit" required>

    <input type="text" name="VehicleModel" placeholder="Vehicle Model" required>

    <button type="submit">Register</button>
</form>

</body>
</html>
