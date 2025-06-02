<?php
session_start();

// Check if the user is logged in as vehicle owner
if (!isset($_SESSION['owner_id'])) {
    header("Location: vehicleownerlogin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connectdb.php';
    $conn = openCon();

    $ownerId = $_SESSION['owner_id'];
    $plateNumber = $_POST['plateNumber'];
    $type = $_POST['type'];
    $model = $_POST['model'];
    $dateRegistered = date('Y-m-d');

    // Handle image upload
    $imageName = $_FILES['vehicleImage']['name'];
    $imageTmpName = $_FILES['vehicleImage']['tmp_name'];
    $imagePath = 'uploads/' . basename($imageName);

    // Make sure the 'uploads' folder exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($imageTmpName, $imagePath)) {
        // Save to vehicle_approval table instead of vehicle
        $query = "INSERT INTO vehicle_approval (PlateNumber, Type, Model, OwnerID, PlateNumberImage, DateRegistered, Reg_Stat)
                  VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssiss", $plateNumber, $type, $model, $ownerId, $imagePath, $dateRegistered);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Vehicle submitted for approval.'); window.location.href='vehicleownerdash.php';</script>";
            exit();
        } else {
            echo "Error inserting into database.";
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register New Vehicle</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        nav {
            background-color: maroon;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-left img {
            width: 40px;
            height: 40px;
        }

        .nav-left h1 {
            font-size: 20px;
            color: white;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            width: 60%;
            margin-top: 80px;
            padding: 30px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            margin-bottom: 20px;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .submit-btn {
            background-color: maroon;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .submit-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <img src="Assets/bulsulogo.png" alt="BulSU Logo">
        <h1>BulSU Online Vehicle Gate System</h1>
    </div>
    <div class="nav-right">
        <a href="vehicleownerdash.php">Back to Dashboard</a>
        <a href="logout.php">Log Out</a>
    </div>
</nav>

<div class="container">
    <h2>Register New Vehicle</h2>
    <form action="vehicleregister.php" method="POST" enctype="multipart/form-data">

        <!-- Plate Number -->
        <div class="form-group">
            <input type="text" id="plateNumber" name="plateNumber" placeholder="Plate Number" required>
        </div>

        <!-- Vehicle Type -->
        <div class="form-group">
            <select id="type" name="type" required>
                <option value="" disabled selected>Select Vehicle Type</option>
                <option value="Motorcycle">Motorcycle</option>
                <option value="Sedan">Sedan</option>
                <option value="Hatchback">Hatchback</option>
                <option value="SUV">SUV</option>
                <option value="Pickup Truck">Pickup Truck</option>
                <option value="Crossover">Crossover</option>
                <option value="MPV">MPV</option>
            </select>
        </div>

        <!-- Vehicle Model -->
        <div class="form-group">
            <input type="text" id="model" name="model" placeholder="Vehicle Model" required>
        </div>

        <!-- Vehicle Image -->
        <div class="form-group">
            <label for="vehicleImage">Plate Number Image</label>
            <input type="file" id="vehicleImage" name="vehicleImage" accept="image/*" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">Submit for Approval</button>
    </form>
</div>

</body>
</html>
