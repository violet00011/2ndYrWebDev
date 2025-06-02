<?php
include 'connectdb.php';

$conn = openCon();

if (!isset($_GET['ownerid'])) {
    header('Location: manage_vehicleowner.php');
    exit();
}

$id = $_GET['ownerid'];

$sql = "SELECT * FROM vehicle_owner WHERE OwnerID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$owner = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $department = $_POST['Department'];
    $contactNumber = $_POST['ContactNumber'];
    $email = $_POST['Email'];
    $position = $_POST['Position'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $sql = "UPDATE vehicle_owner 
            SET LastName=?, FirstName=?, MiddleName=?, Department=?, ContactNumber=?, Email=?, Position=?, Username=?, Password=? 
            WHERE OwnerID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssissssi", $lastName, $firstName, $middleName, $department, $contactNumber, $email, $position, $username, $password, $id);

    if ($stmt->execute()) {
        header("Location: manage_vehicleowner.php");
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
  <title>Edit Vehicle Owner</title>
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
    input[type="text"], input[type="email"], input[type="number"], input[type="password"] {
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
  <a href="adminlogin.php" style="float: right;">Logout</a>
  <a href="admindashboard.php" style="float: right;">Admin Dashboard</a>
  <a href="manage_vehicleowner.php">Manage Vehicle Owners</a>
</nav>

<h1>Edit Vehicle Owner</h1>

<form method="POST" action="">
    <input type="text" name="LastName" placeholder="Last Name" value="<?= htmlspecialchars($owner['LastName']) ?>" required>

    <input type="text" name="FirstName" placeholder="First Name" value="<?= htmlspecialchars($owner['FirstName']) ?>" required>

    <input type="text" name="MiddleName" placeholder="Middle Name" value="<?= htmlspecialchars($owner['MiddleName']) ?>">

    <input type="text" name="Department" placeholder="Department" value="<?= htmlspecialchars($owner['Department']) ?>" required>

    <input type="number" name="ContactNumber" placeholder="Contact Number" value="<?= htmlspecialchars($owner['ContactNumber']) ?>" required>

    <input type="email" name="Email" placeholder="Email" value="<?= htmlspecialchars($owner['Email']) ?>" required>

    <input type="text" name="Position" placeholder="Position" value="<?= htmlspecialchars($owner['Position']) ?>" required>

    <input type="text" name="Username" placeholder="Username" value="<?= htmlspecialchars($owner['Username']) ?>" required>

    <input type="password" name="Password" placeholder="Password" value="<?= htmlspecialchars($owner['Password']) ?>" required>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
