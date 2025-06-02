<?php
session_start();  // Start session to access the session data

if (!isset($_SESSION['visitor_data'])) {
    // Redirect back to form if session data is not set
    header("Location: onetimesched.php");
    exit;
}

$visitor_data = $_SESSION['visitor_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Confirmation | BulSU Online Vehicle Gate System</title>
  <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
      background: linear-gradient(135deg, #800000, #a00000);
      color: white;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .nav-left h1 {
      font-size: 22px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 30px;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 400;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 20px;
    }

    nav a:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-1px);
    }

    main {
      text-align: center;
      padding: 60px 20px 40px;
      color: white;
      text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
      flex-grow: 1;
    }

    main h1 {
      font-family: 'Anton SC', sans-serif;
      font-size: 36px;
      margin-bottom: 15px;
      background: linear-gradient(45deg, #fff, #f0f0f0);
      background-clip: text;
    }

    main > p {
      font-size: 18px;
      margin-bottom: 20px;
      font-weight: 300;
    }

    .confirmation-box {
      background: rgba(255, 255, 255, 0.95);
      max-width: 650px;
      margin: 40px auto;
      padding: 40px;
      border-radius: 20px;
      color: #333;
      text-align: left;
    }

    .confirmation-box h2 {
      margin-bottom: 25px;
      color: #28a745;
      font-size: 24px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .confirmation-box h2::before {
      content: "✅";
      font-size: 28px;
    }

    .info-list {
      margin-bottom: 25px;
    }

    .info-list p {
      margin-bottom: 12px;
      font-size: 16px;
      allignment: center;
    }

    .info-list p strong {
      color: maroon;
      font-weight: 600;
      min-width: 180px;
      display: inline-block;
    }

    .note {
      margin-top: 30px;
      padding: 20px;
      background: linear-gradient(135deg, rgba(128, 0, 0, 0.1), rgba(128, 0, 0, 0.05));
      border-radius: 15px;
      border: 1px solid rgba(128, 0, 0, 0.2);
      font-size: 15px;
      color: maroon;
      line-height: 1.6;
    }

    .note-icon {
      font-size: 20px;
      margin-right: 8px;
    }

    .btn-container {
      text-align: center;
      margin-top: 35px;
    }

    .btn-container a {
      display: inline-block;
      background: linear-gradient(135deg, maroon, #a00000);
      color: white;
      padding: 15px 35px;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .btn-container a:hover {
      transform: translateY(-2px);
      background: linear-gradient(135deg, #a00000, #c00000);
    }

    @media (max-width: 768px) {
      nav {
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
      }
      
      .nav-right {
        gap: 20px;
      }
      
      main {
        padding: 40px 15px 30px;
      }
      
      main h1 {
        font-size: 28px;
      }
      
      .confirmation-box {
        padding: 30px 20px;
        margin: 30px auto;
      }
      
      .info-list p strong {
        min-width: auto;
        display: block;
        margin-bottom: 5px;
      }
    }
  </style>
</head>
<body>

<nav>
  <div class="nav-left">
    <h1>BulSU Vehicle Gate</h1>
  </div>
  <div class="nav-right">
    <a href="index.html">Homepage</a>
    <a href="#">About Us</a>
  </div>
</nav>

<main>
  <h1>Visit Request Submitted!</h1>
  <p>Please review your submitted details below</p>

  <div class="confirmation-box">
    <h2>Submitted Information</h2>
    
    <div class="info-list">
      <p><strong>Name:</strong> <?= htmlspecialchars($visitor_data['FirstName'] . ' ' . $visitor_data['MiddleName'] . ' ' . $visitor_data['LastName']) ?></p>
      
      <p><strong>Contact Number:</strong> <?= htmlspecialchars($visitor_data['ContactNumber']) ?></p>
      
      <p><strong>Visit Date and Time:</strong> <?= htmlspecialchars($visitor_data['ScheduledVisit']) ?></p>
      
      <p><strong>Vehicle Model:</strong> <?= htmlspecialchars($visitor_data['VehicleModel']) ?></p>
      
      <p><strong>Plate Number:</strong> <?= htmlspecialchars($visitor_data['PlateNumber']) ?></p>
      
      <p><strong>Gate:</strong> <?= htmlspecialchars($visitor_data['GateID']) ?></p>
      
      <p><strong>Purpose of Visit:</strong> <?= htmlspecialchars($visitor_data['Purpose']) ?></p>
    </div>

    <div class="note">
      <span class="note-icon">🔔</span><strong>Important Reminders:</strong><br><br>
      • Please wait for confirmation from the guard<br>
      • You will be notified via text message once your visit is approved<br>
      • Make sure to bring a valid ID and arrive on time
    </div>

    <div class="btn-container">
      <a href="index.php">Done</a>
    </div>
  </div>
</main>

</body>
</html>