<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Confirmation | Vehicle Owner</title>
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
      font-family: 'Anton SC', sans-serif;
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .dropdown {
      position: relative;
    }

    .dropdown-toggle {
      background: none;
      border: none;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      top: 100%;
      right: 0;
      background-color: white;
      min-width: 150px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 999;
    }

    .dropdown-content a {
      color: maroon;
      padding: 10px 15px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #eee;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    nav a {
      color: white;
      text-decoration: none;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .confirmation-container {
      background: rgba(255, 255, 255, 0.9);
      margin-top: 120px;
      padding: 50px;
      border-radius: 10px;
      max-width: 600px;
      width: 90%;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .page-title {
      margin-bottom: 20px;
      color: maroon;
      font-family: 'Anton SC', sans-serif;
      font-size: 28px;
    }

    .message {
      font-size: 18px;
      color: black;
      margin-bottom: 30px;
    }

    a.back-home {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: maroon;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
    }

    a.back-home:hover {
      opacity: 0.85;
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
    <a href="index.php">Homepage</a>
    <div class="dropdown">
      <button class="dropdown-toggle">Log in as ↴</button>
      <div class="dropdown-content">
        <a href="#">Admin</a>
        <a href="#">Guard</a>
        <a href="#">Vehicle Owner</a>
      </div>
    </div>
    <a href="aboutus.php">About Us</a>
  </div>
</nav>

<div class="confirmation-container">
  <h1 class="page-title">Registration Submitted!</h1>
  <p class="message">
    Please wait for your account approval.<br>
    You will receive a confirmation email once your application has been reviewed.<br><br>
    Thank you for your patience!
  </p>
  <a href="index.php" class="back-home">Back to Homepage</a>
</div>

</body>
</html>
