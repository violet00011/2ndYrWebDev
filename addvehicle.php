<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openCon();

    $plateNumber = $_POST['PlateNumber'];
    $type = $_POST['Type'];
    $model = $_POST['Model'];
    $ownerID = $_POST['OwnerID'];
    
    $plateNumberImage = "";
    if (isset($_FILES['PlateNumberImage']) && $_FILES['PlateNumberImage']['error'] == 0) {
        $imageTmpName = $_FILES['PlateNumberImage']['tmp_name'];
        $imageName = basename($_FILES['PlateNumberImage']['name']);
        $imageDir = "uploads/"; 
        $imagePath = $imageDir . $imageName;
        
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $plateNumberImage = $imagePath;
        } else {
            echo "Error uploading image.";
        }
    }

    $sql = "INSERT INTO vehicle (PlateNumber, Type, Model, OwnerID, PlateNumberImage) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $plateNumber, $type, $model, $ownerID, $plateNumberImage);

    if ($stmt->execute()) {
        header("Location: manage_vehicle.php");
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
  <title>Add Vehicle</title>
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
    form input[type="text"], form input[type="number"], form input[type="file"] {
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
        margin-top: 5px;
        margin-bottom: 15px;
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
  <a href="manage_vehicle.php" style="color: white; font-weight: bold;">Manage Vehicles</a>
</nav>

<form method="POST" action="" enctype="multipart/form-data">
    <h1 style="text-align: center; color: maroon; margin-top: 0;">Register New Vehicle</h1>
    
    <input type="text" name="PlateNumber" placeholder="Plate Number" required>

    <input type="text" name="Type" placeholder="Vehicle Type" required>

    <input type="text" name="Model" placeholder="Vehicle Model" required>

    <input type="number" name="OwnerID" placeholder="Owner ID" required>

    <label for="PlateNumberImage">Upload Plate Number Image</label>
    <input type="file" name="PlateNumberImage" accept="image/*">

    <button type="submit">Register Vehicle</button>
</form>

</body>
</html>
