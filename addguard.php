<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openCon();

    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $shiftDays = $_POST['ShiftDays'];
    $shiftHoursStart = $_POST['ShiftHoursStart'];
    $shiftHoursEnd = $_POST['ShiftHoursEnd'];
    $gateID = $_POST['GateID'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $sql = "INSERT INTO guard (LastName, FirstName, MiddleName, ShiftDays, ShiftHoursStart, ShiftHoursEnd, GateID, Username, Passward) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $lastName, $firstName, $middleName, $shiftDays, $shiftHoursStart, $shiftHoursEnd, $gateID, $username, $password);

    if ($stmt->execute()) {
        header("Location: manage_guard.php");
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
  <title>Add Guard</title>
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
        form input[type="text"], form input[type="time"] {
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
    button {
      background-color: maroon;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    p{
        margin-top: 2px;
        margin-bottom:2px;
    }
  </style>
</head>
<body>

<nav>
  <a href="adminlogin.php" style="float: right; color: white; font-weight: bold;">Logout</a>
  <a href="admindashboard.php" style="float: right; color: white; font-weight: bold;">Admin Dashboard</a>
  <a href="manage_guard.php" style="color: white; font-weight: bold;">Manage Guards</a>
</nav>


<form method="POST" action="">
    <h1 style="text-align: center; color: maroon; margin-top: 0;">Add New Guard</h1>
    
    <input type="text" name="LastName" placeholder="Last Name" required>

    <input type="text" name="FirstName" placeholder="First Name" required>

    <input type="text" name="MiddleName" placeholder="Middle Name">

    <input type="text" name="ShiftDays" placeholder="Shift Days (e.g., Monday-Friday)" required>

    <p>Shift Start:</p>
    <input type="time" name="ShiftHoursStart" required>

    <p>Shift End:</p>
    <input type="time" name="ShiftHoursEnd" required>

    <input type="text" name="GateID" placeholder="Gate ID" required>

    <input type="text" name="Username" placeholder="Username" required>

    <input type="text" name="Password" placeholder="Password" required>

    <button type="submit">Add Guard</button>
</form>

</body>
</html>
