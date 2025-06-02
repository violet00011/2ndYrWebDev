<?php
include 'connectdb.php';

$conn = openCon();

if (!isset($_GET['id'])) {
    header('Location: manage_guard.php');
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM guard WHERE GuardID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$guard = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $shiftDays = $_POST['ShiftDays'];
    $shiftHoursStart = $_POST['ShiftHoursStart'];
    $shiftHoursEnd = $_POST['ShiftHoursEnd'];
    $gateID = $_POST['GateID'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $sql = "UPDATE guard SET LastName=?, FirstName=?, MiddleName=?, ShiftDays=?, ShiftHoursStart=?, ShiftHoursEnd=?, GateID=?, Username=?, Passward=? WHERE GuardID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $lastName, $firstName, $middleName, $shiftDays, $shiftHoursStart, $shiftHoursEnd, $gateID, $username, $password, $id);

    if ($stmt->execute()) {
        header("Location: manage_guard.php");
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
  <title>Edit Guard</title>
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
    input[type="text"], input[type="time"] {
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
  <a href="manage_guard.php" style="color: white; font-weight: bold;">Manage Guards</a>
</nav>


<h1>Edit Guard</h1>

<form method="POST" action="">
    <input type="text" name="LastName" placeholder="Last Name" value="<?= htmlspecialchars($guard['LastName']) ?>" required>

    <input type="text" name="FirstName" placeholder="First Name" value="<?= htmlspecialchars($guard['FirstName']) ?>" required>

    <input type="text" name="MiddleName" placeholder="Middle Name" value="<?= htmlspecialchars($guard['MiddleName']) ?>">

    <input type="text" name="ShiftDays" placeholder="Shift Days (e.g., Monday-Friday)" value="<?= htmlspecialchars($guard['ShiftDays']) ?>" required>

    <p>Shift Start:</p>
    <input type="time" name="ShiftHoursStart" value="<?= htmlspecialchars($guard['ShiftHoursStart']) ?>" required>

    <p>Shift End:</p>
    <input type="time" name="ShiftHoursEnd" value="<?= htmlspecialchars($guard['ShiftHoursEnd']) ?>" required>

    <input type="text" name="GateID" placeholder="Gate ID" value="<?= htmlspecialchars($guard['GateID']) ?>" required>

    <input type="text" name="Username" placeholder="Username" value="<?= htmlspecialchars($guard['Username']) ?>" required>

    <input type="text" name="Password" placeholder="Password" value="<?= htmlspecialchars($guard['Passward']) ?>" required>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
