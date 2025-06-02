<?php
include 'connectdb.php';
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openCon();

    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $position = $_POST['Position'];
    $email = $_POST['Email'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $imagePath = '';
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $imageTmp = $_FILES['profileImage']['tmp_name'];
        $imageName = $_FILES['profileImage']['name'];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageExt, $allowedExt)) {
            $newImageName = uniqid('', true) . "." . $imageExt;
            $imagePath = 'uploads/' . $newImageName;

            move_uploaded_file($imageTmp, $imagePath);
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            closeCon($conn);
            exit;
        }
    }

    $sql = "INSERT INTO staff (LastName, FirstName, MiddleName, Position, Email, Username, Password, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $lastName, $firstName, $middleName, $position, $email, $username, $password, $imagePath);

    if ($stmt->execute()) {
        echo "Staff added successfully!";
        header("Location: manage_staff.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    closeCon($conn);
}

ob_end_flush();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Staff</title>
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
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    form input[type="text"], form input[type="email"], form input[type="password"] {
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
        align-self: center; /* Center the button */
        margin-top: 20px; /* Add space between the form inputs and the button */
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
  <a href="manage_staff.php" style="color: white; font-weight: bold;">Manage Staff</a>
</nav>

<form method="POST" action="" enctype="multipart/form-data">
    <h1 style="text-align: center; color: maroon; margin-top: 0;">Add New Staff</h1>
    
    <input type="text" name="LastName" placeholder="Last Name" required>

    <input type="text" name="FirstName" placeholder="First Name" required>

    <input type="text" name="MiddleName" placeholder="Middle Name">

    <input type="text" name="Position" placeholder="Position" required>

    <input type="email" name="Email" placeholder="Email" required>

    <input type="text" name="Username" placeholder="Username" required>

    <input type="password" name="Password" placeholder="Password" required>

    <label for="profileImage">Profile Image:</label>
    <input type="file" name="profileImage" id="profileImage" accept="image/*">

    <button type="submit">Add Staff</button>
</form>

</body>
</html>
