<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connectdb.php';

$conn = openCon();
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Admin
    $stmt = $conn->prepare("SELECT * FROM staff WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    if ($admin_result->num_rows === 1) {
        $row = $admin_result->fetch_assoc();
        $_SESSION['staff_id'] = $row['StaffID'];
        header("Location: admindashboard.php");
        exit();
    }
    $stmt->close();

    // Vehicle Owner
    $stmt = $conn->prepare("SELECT * FROM vehicle_owner WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $vehicle_result = $stmt->get_result();

    if ($vehicle_result->num_rows === 1) {
        $row = $vehicle_result->fetch_assoc();
        $_SESSION['owner_id'] = $row['OwnerID'];
        header("Location: vehicleownerdash.php");
        exit();
    }
    $stmt->close();

    // Guard 
    $stmt = $conn->prepare("SELECT * FROM guard WHERE Username = ? AND Passward = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $guard_result = $stmt->get_result();

    if ($guard_result->num_rows === 1) {
        $row = $guard_result->fetch_assoc();
        $_SESSION['guard_id'] = $row['GuardID'];
        $_SESSION['guard_name'] = $row['FirstName'] . ' ' . $row['LastName'];
        header("Location: guarddashboard.php");
        exit();
    }
    $stmt->close();

    $error_message = "Invalid username or password.";
}

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Log In | Vehicle System</title>
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
      align-items: center;
      justify-content: center;
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
      position: fixed;
      top: 0;
      width: 100%;
      height: 70px;
      z-index: 1000;
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

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 15px;
      max-width: 400px;
      width: 100%;
      text-align: center;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
      margin-top: 120px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .login-container h1 {
      font-size: 32px;
      color: maroon; 
      font-family: 'Anton SC', sans-serif;
      margin-bottom: 30px;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    input {
      width: 100%;
      padding: 15px;
      margin: 10px 0;
      border-radius: 8px;
      border: 2px solid #e0e0e0;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    input:focus {
      outline: none;
      border-color: maroon;
      box-shadow: 0 0 10px rgba(128, 0, 32, 0.2);
    }

    button {
      width: 100%;
      padding: 15px;
      margin-top: 20px;
      background: linear-gradient(135deg, maroon, #8B0000);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      background: linear-gradient(135deg, #8B0000, maroon);
    }

    .signup-link {
      margin-top: 20px;
      display: block;
      font-size: 14px;
      color: maroon;
      text-decoration: none;
      font-weight: 500;
    }

    .signup-link:hover {
      text-decoration: underline;
    }

    .error-message {
      margin-top: 15px;
      color: #dc3545;
      font-size: 14px;
      background: rgba(220, 53, 69, 0.1);
      padding: 10px;
      border-radius: 5px;
      border-left: 4px solid #dc3545;
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

      .login-container {
        margin-top: 150px;
        padding: 30px;
      }

      .login-container h1 {
        font-size: 28px;
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
      <a href="contactus.php"><i class="fas fa-envelope"></i> Contact Us</a>
    </div>
  </nav>

  <div class="login-container">
    <h1>Log In</h1>
    <form method="POST" action="login.php">
      <input type="text" name="Username" placeholder="Username" required>
      <input type="password" name="Password" placeholder="Password" required>
      <button type="submit">Log In</button>
      <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
      <?php endif; ?>
    </form>
    <a class="signup-link" href="vehicleregister.php">Don't have an account? Sign up</a>
  </div>

</body>
</html>