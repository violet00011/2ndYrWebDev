<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up Success</title>
  <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: url("Assets/langit.jpg") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .success-container {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
      max-width: 500px;
    }

    .success-container h1 {
      color: maroon;
      font-family: 'Anton SC', sans-serif;
      margin-bottom: 20px;
    }

    .success-container p {
      font-size: 18px;
      margin-bottom: 30px;
    }

    .success-container a {
      padding: 10px 20px;
      background-color: maroon;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
    }

    .success-container a:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>
  <div class="success-container">
    <h1>Registration Successful!</h1>
    <p>Your vehicle owner account has been created successfully.</p>
    <a href="login.php">LogIn</a>
  </div>
</body>
</html>
