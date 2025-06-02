<?php
include 'connectdb.php';

$conn = openCon();

if (!isset($_GET['logid'])) {
    header('Location: manage_vehiclelog.php');
    exit();
}

$logID = $_GET['logid'];

$sql = "SELECT * FROM vehicle_log WHERE LogID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logID);
$stmt->execute();
$result = $stmt->get_result();
$log = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicleID = $_POST['VehicleID'];
    $gateID = $_POST['GateID'];
    $guardID = $_POST['GuardID'];
    $timeIn = $_POST['TimeIn'];
    $timeOut = $_POST['TimeOut'];
    $status = $_POST['Status'];

    $sql = "UPDATE vehicle_log SET VehicleID=?, GateID=?, GuardID=?, TimeIn=?, TimeOut=?, Status=? WHERE LogID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isisssi", $vehicleID, $gateID, $guardID, $timeIn, $timeOut, $status, $logID);

    if ($stmt->execute()) {
        header("Location: manage_vehiclelog.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Vehicle Log</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
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
      text-align: center;
      margin-top: 50px;
      margin-bottom: 50px;
    }
    form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: auto;
    }
    input[type="text"], input[type="datetime-local"], input[type="number"] {
      width: 95%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: maroon;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<nav>
  <a href="adminlogin.php" style="float: right; color: white; font-weight: bold;">Logout</a>
  <a href="admindashboard.php" style="float: right; color: white; font-weight: bold;">Admin Dashboard</a>
  <a href="manage_vehiclelog.php" style="color: white; font-weight: bold;">Manage Vehicle Logs</a>
</nav>

<h1>Edit Vehicle Log</h1>

<form method="POST" action="">
    <input type="number" name="VehicleID" placeholder="Vehicle ID" value="<?= htmlspecialchars($log['VehicleID']) ?>" required>

    <div style="margin-bottom: 16px;">
  <select name="GateID" id="GateID" required style="width: 98%; padding: 9px; font-size: 12px; border-radius: 6px;">
    <option value="" disabled>Select Gate</option>
    <option value="Malolos_1" <?= $log['GateID'] == 'Malolos_1' ? 'selected' : '' ?>>Malolos_1</option>
    <option value="Malolos_2" <?= $log['GateID'] == 'Malolos_2' ? 'selected' : '' ?>>Malolos_2</option>
    <option value="Malolos_3" <?= $log['GateID'] == 'Malolos_3' ? 'selected' : '' ?>>Malolos_3</option>
    <option value="Malolos_4" <?= $log['GateID'] == 'Malolos_4' ? 'selected' : '' ?>>Malolos_4</option>
    <option value="Hagonoy_1" <?= $log['GateID'] == 'Hagonoy_1' ? 'selected' : '' ?>>Hagonoy_1</option>
    <option value="Meneses_1" <?= $log['GateID'] == 'Meneses_1' ? 'selected' : '' ?>>Meneses_1</option>
    <option value="Bustos_1" <?= $log['GateID'] == 'Bustos_1' ? 'selected' : '' ?>>Bustos_1</option>
    <option value="SanRafael_1" <?= $log['GateID'] == 'SanRafael_1' ? 'selected' : '' ?>>SanRafael_1</option>
    <option value="Sarmiento_1" <?= $log['GateID'] == 'Sarmiento_1' ? 'selected' : '' ?>>Sarmiento_1</option>
  </select>
</div>

    <input type="number" name="GuardID" placeholder="Guard ID" value="<?= htmlspecialchars($log['GuardID']) ?>" required>

    <input type="datetime-local" name="TimeIn" placeholder="Time In" value="<?= date('Y-m-d\TH:i', strtotime($log['TimeIn'])) ?>">

    <input type="datetime-local" name="TimeOut" placeholder="Time Out" value="<?= date('Y-m-d\TH:i', strtotime($log['TimeOut'])) ?>">

    <input type="text" name="Status" placeholder="Status" value="<?= htmlspecialchars($log['Status']) ?>" required>

    <button type="submit">Save Changes</button>
</form>


</body>
</html>
