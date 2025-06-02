<?php 
include 'connectdb.php';

session_start();  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = openCon();

    $required_fields = ['LastName', 'FirstName', 'MiddleName', 'ContactNumber', 'ScheduledVisit', 'VehicleModel', 'PlateNumber', 'GateID', 'Purpose'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        echo "<script>alert('Missing fields: " . implode(', ', $missing_fields) . "');</script>";
    } else {

        $LastName = $_POST['LastName'];
        $FirstName = $_POST['FirstName'];
        $MiddleName = $_POST['MiddleName'];
        $ContactNumber = $_POST['ContactNumber'];
        $ScheduledVisit = $_POST['ScheduledVisit'];
        $VehicleModel = $_POST['VehicleModel'];
        $PlateNumber = $_POST['PlateNumber'];
        $GateID = $_POST['GateID'];
        $Purpose = $_POST['Purpose'];

        $_SESSION['visitor_data'] = [
            'LastName' => $LastName,
            'FirstName' => $FirstName,
            'MiddleName' => $MiddleName,
            'ContactNumber' => $ContactNumber,
            'ScheduledVisit' => $ScheduledVisit,
            'VehicleModel' => $VehicleModel,
            'PlateNumber' => $PlateNumber,
            'GateID' => $GateID,
            'Purpose' => $Purpose
        ];
      
        $sql = "INSERT INTO visitor 
            (LastName, FirstName, MiddleName, ContactNumber, ScheduledVisit, VehicleModel, PlateNumber, GateID, Purpose)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $LastName, $FirstName, $MiddleName, $ContactNumber, $ScheduledVisit, $VehicleModel, $PlateNumber, $GateID, $Purpose);

        if ($stmt->execute()) {
            header("Location: onetimeconfirmation.php");
            exit;
        } else {
            echo "<script>alert('Database Error: " . addslashes($stmt->error) . "');</script>";
        }

        $stmt->close();
    }

    closeCon($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Schedule One-Time Visit | BulSU Online Vehicle Gate System</title>
  <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    }

    nav {
      background-color: maroon;
      color: white;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      position: relative;
      height: 70px;
    }

    nav::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(128, 0, 32, 0.9), rgba(100, 0, 25, 0.95));
      backdrop-filter: blur(10px);
      z-index: -1;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .nav-left img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }

    .nav-left img:hover {
      border-color: rgba(255, 255, 255, 0.8);
      transform: scale(1.05);
    }

    nav .nav-left h1 {
      font-size: 22px;
      font-weight: 600;
      letter-spacing: -0.5px;
    }

    nav .nav-right {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 400;
      padding: 10px 20px;
      border-radius: 25px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    nav a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    nav a:hover::before {
      left: 100%;
    }

    nav a:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    main {
      text-align: center;
      padding: 40px 20px 20px;
      color: white;
      flex-grow: 1;
    }

    main h1 {
      font-family: 'Anton SC', sans-serif;
      font-size: 48px;
      text-transform: uppercase;
      margin-bottom: 20px;
      color: white;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
    }

    .form-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      max-width: 900px;
      margin: 20px auto;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .form-container h2 {
      font-family: 'Anton SC', sans-serif;
      font-size: 28px;
      color: maroon;
      margin-bottom: 30px;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .form-row {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .form-row input,
    .form-row select {
      flex: 1;
      min-width: 200px;
      padding: 15px;
      margin: 8px 0;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .form-row input:focus,
    .form-row select:focus {
      outline: none;
      border-color: maroon;
      box-shadow: 0 0 10px rgba(128, 0, 32, 0.2);
    }

    .form-container textarea {
      width: 100%;
      padding: 15px;
      margin: 15px 0;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      resize: vertical;
      min-height: 100px;
      transition: all 0.3s ease;
    }

    .form-container textarea:focus {
      outline: none;
      border-color: maroon;
      box-shadow: 0 0 10px rgba(128, 0, 32, 0.2);
    }

    .form-container button {
      padding: 15px 30px;
      background: linear-gradient(135deg, maroon, #8B0000);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      margin-top: 10px;
    }

    .form-container button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      background: linear-gradient(135deg, #8B0000, maroon);
    }

    .login-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: maroon;
      font-weight: 500;
      padding: 10px 20px;
      border: 2px solid maroon;
      border-radius: 25px;
      transition: all 0.3s ease;
    }

    .login-link:hover {
      background-color: maroon;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
      nav {
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
        height: auto;
      }

      nav .nav-right {
        gap: 20px;
      }

      nav .nav-left h1 {
        font-size: 18px;
        text-align: center;
      }

      nav a {
        padding: 8px 15px;
        font-size: 14px;
      }

      main h1 {
        font-size: 32px;
      }

      .form-container {
        padding: 25px;
        margin: 10px;
      }

      .form-row {
        flex-direction: column;
      }

      .form-row input,
      .form-row select {
        min-width: auto;
      }
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
    <a href="index.php"><i class="fas fa-home"></i> Homepage</a>
    <a href="aboutus.php"><i class="fas fa-users"></i> About Us</a>
    <a href="contactusmain.php"><i class="fas fa-envelope"></i> Contact Us</a>
  </div>
</nav>

<main>
  <h1>Schedule One-Time Visit</h1>
  <div class="form-container">  
    <h2>Visitor Information</h2>
    <form method="POST">
      <div class="form-row">
        <input type="text" name="LastName" placeholder="Last Name" required>
        <input type="text" name="FirstName" placeholder="First Name" required>
        <input type="text" name="MiddleName" placeholder="Middle Name" required>
      </div>

      <div class="form-row">
        <input type="text" name="ContactNumber" placeholder="Contact Number" required>
        <input type="datetime-local" name="ScheduledVisit" required>
      </div>

      <div class="form-row">
        <input type="text" name="VehicleModel" placeholder="Vehicle Model" required>
        <input type="text" name="PlateNumber" placeholder="Plate Number" required>
        <select name="GateID" required>
          <option value="" disabled selected>Select Gate</option>
          <option value="Malolos_1">Malolos_1</option>
          <option value="Malolos_2">Malolos_2</option>
          <option value="Malolos_3">Malolos_3</option>
          <option value="Malolos_4">Malolos_4</option>
          <option value="Hagonoy_1">Hagonoy_1</option>
          <option value="Meneses_1">Meneses_1</option>
          <option value="Bustos_1">Bustos_1</option>
          <option value="SanRafael_1">SanRafael_1</option>
          <option value="Sarmiento_1">Sarmiento_1</option>
        </select>
      </div>

      <textarea name="Purpose" rows="4" placeholder="Purpose of Visit" required></textarea>
      <button type="submit">Submit Request</button>
    </form>

    <a href="vehicleownerlogin.php" class="login-link">Already have an account? Log In</a>
  </div>
</main>

</body>
</html>