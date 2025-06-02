<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openCon();

    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $department = $_POST['Department'];
    $contactNumber = $_POST['ContactNumber'];
    $email = $_POST['Email'];
    $position = $_POST['Position'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $sql = "INSERT INTO vehicle_owner (LastName, FirstName, MiddleName, Department, ContactNumber, Email, Position, Username, Password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $lastName, $firstName, $middleName, $department, $contactNumber, $email, $position, $username, $password);

    if ($stmt->execute()) {
        header("Location: manage_vehicleowner.php");
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
  <title>Add Vehicle Owner</title>
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
    form input[type="text"], form input[type="email"], form input[type="number"], form input[type="password"] {
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
  <a href="manage_vehicleowner.php" style="color: white; font-weight: bold;">Manage Vehicle Owners</a>
</nav>

<form method="POST" action="">
    <h1 style="text-align: center; color: maroon; margin-top: 0;">Register New Vehicle Owner</h1>
    
    <input type="text" name="LastName" placeholder="Last Name" required>

    <input type="text" name="FirstName" placeholder="First Name" required>

    <input type="text" name="MiddleName" placeholder="Middle Name">

    <input type="text" name="Department" placeholder="Department" required>

    <input type="number" name="ContactNumber" placeholder="Contact Number" required>

    <input type="email" name="Email" placeholder="Email" required>

    <input type="text" name="Position" placeholder="Position" required>

    <input type="text" name="Username" placeholder="Username" required>

    <input type="password" name="Password" placeholder="Password" required>

    <button type="submit">Register</button>
</form>

</body>
</html>
