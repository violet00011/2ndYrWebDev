<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openCon();

    $gateID = $_POST['GateID'];
    $campus = $_POST['Campus'];
    $gateNumber = $_POST['GateNumber'];
    $status = $_POST['Status'];

    $sql = "INSERT INTO gate (GateID, Campus, GateNumber, Status) 
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $gateID, $campus, $gateNumber, $status);

    if ($stmt->execute()) {
        header("Location: manage_gate.php");
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
  <title>Add Gate</title>
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
    form input[type="text"], form select {
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
  </style>
</head>
<body>

<nav>
  <a href="adminlogin.php" style="float: right; color: white; font-weight: bold;">Logout</a>
  <a href="admindashboard.php" style="float: right; color: white; font-weight: bold;">Admin Dashboard</a>
  <a href="manage_gate.php" style="color: white; font-weight: bold;">Manage Gates</a>
</nav>

<form method="POST" action="">
    <h1 style="text-align: center; color: maroon; margin-top: 0;">Register New Gate</h1>
    
    <input type="text" name="GateID" placeholder="Gate ID" required>

    <input type="text" name="Campus" placeholder="Campus" required>

    <input type="text" name="GateNumber" placeholder="Gate Number" required>

    <label for="Status">Gate Status</label>
    <select name="Status" required>
        <option value="Entry">Entry</option>
        <option value="Exit">Exit</option>
        <option value="Entry/Exit">Entry/Exit</option>
    </select>

    <button type="submit">Register Gate</button>
</form>

</body>
</html>
