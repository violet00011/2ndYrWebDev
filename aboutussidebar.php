<?php
include 'connectdb.php';

$conn = openCon();
$sql = "SELECT * FROM staff";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us | BulSU Online Vehicle Gate System</title>
  <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
      color: white;
      display: flex;
      flex-direction: column;
    }

    .main-content {
      display: flex;
      flex: 1;
      align-items: flex-start;
      margin-left: 250px;
      flex-direction: column;
      padding: 20px;
    }

    .about-container {
      max-width: 1000px;
      margin: 20px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 20px;
      border-radius: 10px;
      color: black;
      display: flex;
      align-items: center;
      gap: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      flex-wrap: nowrap;
    }

    .about-left {
      flex: 1;
      min-width: 200px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .about-left img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      border: 2px solid maroon;
    }

    .about-right {
      flex: 2;
    }

    .about-right h2 {
      font-size: 24px;
      margin-bottom: 8px;
    }

    .about-right p {
      margin-bottom: 10px;
      line-height: 1.5;
      font-size: 16px;
    }

    .no-staff {
      color: white;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="main-content">
  <?php include 'sidebar.php'; ?>

  <?php
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $fullname = $row['FirstName'] . ' ' . $row['MiddleName'] . ' ' . $row['LastName'];
          $position = $row['Position'];
          $email = $row['Email'];
          $imgPath = !empty($row['image']) ? $row['image'] : 'Assets/defaultpfp.jpg';

          echo '<div class="about-container">';
          echo '  <div class="about-left">';
          echo '    <img src="' . htmlspecialchars($imgPath) . '" alt="Staff Photo">';
          echo '  </div>';
          echo '  <div class="about-right">';
          echo '    <h2>' . htmlspecialchars($fullname) . '</h2>';
          echo '    <p><strong>Position:</strong> ' . htmlspecialchars($position) . '</p>';
          echo '    <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>';
          echo '    <p>This staff is part of the team managing the BulSU Online Vehicle Gate System. Their role helps ensure smooth vehicle access and monitoring within the campus.</p>';
          echo '  </div>';
          echo '</div>';
      }
  } else {
      echo '<p class="no-staff">No staff members found.</p>';
  }

  $conn->close();
  ?>
</div>

</body>
</html>
