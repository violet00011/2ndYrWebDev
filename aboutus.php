<?php
include 'connectdb.php';
$conn = openCon();
$sql = "SELECT * FROM staff";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Our Staff</title>
<link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: url("Assets/langit.jpg") no-repeat center center fixed;
  background-size: cover;
  color: #333;
  min-height: 100vh;
  overflow-x: hidden;
}

nav {
  background: rgba(128, 0, 0, 0.95);
  backdrop-filter: blur(15px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding: 0px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 1000;
  transition: all 0.3s ease;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
  height:80px;
}

.nav-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

.nav-left img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.3);
  transition: all 0.3s ease;
}

.nav-left img:hover {
  transform: scale(1.05);
  border-color: white;
}

.nav-left h1 {
  font-size: 1.1rem;
  color: white;
  font-weight: 600;
  text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.nav-right a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  padding: 8px 16px;
  border-radius: 20px;
  transition: all 0.3s ease;
}

.nav-right a:hover {
  background: rgba(255, 255, 255, 0.15);
}

h1 {
  text-align: center;
  color: white;
  margin: 50px 0;
  font-size: 3.0rem;
  font-family: 'Anton SC', sans-serif;
  text-shadow: 3px 3px 15px rgba(0, 0, 0, 0.5);
  position: relative;
  animation: fadeInDown 1s ease-out;
}

h1::after {
  content: '';
  position: absolute;
  bottom: -15px;
  left: 50%;
  transform: translateX(-50%);
  width: 150px;
  height: 5px;
  background: linear-gradient(90deg, maroon, #ff6b6b, maroon);
  border-radius: 3px;
  animation: expandWidth 1s ease-out 0.5s both;
}

.staff-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 30px;
  padding: 50px 30px;
  max-width: 1400px;
  margin: 0 auto;
}

.staff-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(15px);
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
  text-align: center;
  transition: all 0.4s ease;
  border: 1px solid rgba(255, 255, 255, 0.3);
  position: relative;
  overflow: hidden;
  animation: fadeInUp 0.8s ease-out;
}

.staff-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}

.staff-photo-container {
  position: relative;
  display: inline-block;
  margin-bottom: 20px;
}

.staff-card img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 50%;
  border: 4px solid maroon;
  transition: all 0.3s ease;
}

.staff-card:hover img {
  transform: scale(1.02);
}

.staff-card h3 {
  margin: 20px 0 15px;
  font-size: 1.4rem;
  color: maroon;
  font-weight: 600;
  position: relative;
  transition: all 0.3s ease;
}

.staff-card:hover h3 {
  color: #8B0000;
  transform: translateY(-2px);
}

.staff-info {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 15px;
}

.info-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 10px 15px;
  background: rgba(128, 0, 0, 0.1);
  border-radius: 25px;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.info-item:hover {
  background: rgba(128, 0, 0, 0.2);
}

.info-item i {
  color: maroon;
  font-size: 1rem;
  width: 16px;
  text-align: center;
}

.info-item strong {
  color: maroon;
  font-weight: 600;
}

.no-staff-message {
  grid-column: 1 / -1;
  text-align: center;
  color: white;
  font-size: 1.2rem;
  padding: 60px 20px;
  background: rgba(128, 0, 0, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes expandWidth {
  from {
    width: 0;
  }
  to {
    width: 150px;
  }
}

@media (max-width: 768px) {
  nav {
    flex-direction: column;
    gap: 20px;
    padding: 20px;
  }
  
  .nav-right {
    gap: 15px;
    flex-wrap: wrap;
    justify-content: center;
  }
  
  h1 {
    font-size: 2.5rem;
    margin: 30px 0;
  }
  
  .staff-container {
    grid-template-columns: 1fr;
    padding: 30px 20px;
    gap: 25px;
  }
  
  .staff-card {
    padding: 25px;
  }
  
  .nav-left h1 {
    font-size: 1.1rem;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .nav-left {
    flex-direction: column;
    gap: 10px;
  }
  
  h1 {
    font-size: 2rem;
  }
  
  .staff-card img {
    width: 100px;
    height: 100px;
  }
  
  .staff-photo-container::before {
    width: 120px;
    height: 120px;
  }
}

.staff-card:nth-child(1) { animation-delay: 0.1s; }
.staff-card:nth-child(2) { animation-delay: 0.2s; }
.staff-card:nth-child(3) { animation-delay: 0.3s; }
.staff-card:nth-child(4) { animation-delay: 0.4s; }
.staff-card:nth-child(5) { animation-delay: 0.5s; }
.staff-card:nth-child(6) { animation-delay: 0.6s; }
</style>
</head>
<body>
<nav>
  <div class="nav-left">
    <img src="Assets/bulsulogo.png" alt="BulSU Logo" />
    <h1>BulSU Online Vehicle Gate System</h1>
  </div>
  <div class="nav-right">
    <a href="index.php"><i class="fas fa-home"></i> Homepage</a>
    <a href="aboutus.php"><i class="fas fa-users"></i> About Us</a>
    <a href="contactusmain.php"><i class="fas fa-envelope"></i> Contact Us</a>
  </div>
</nav>

<h1>About Us</h1>

<div class="staff-container">
<?php
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $fullname = $row['FirstName'] . ' ' . $row['MiddleName'] . ' ' . $row['LastName'];
    $position = $row['Position'];
    $email = $row['Email'];
    $imgPath = !empty($row['image']) ? $row['image'] : 'Assets/defaultpfp.jpg';
    
    echo '<div class="staff-card">';
    echo '<div class="staff-photo-container">';
    echo '<img src="' . htmlspecialchars($imgPath) . '" alt="Staff Photo">';
    echo '</div>';
    echo '<h3>' . htmlspecialchars($fullname) . '</h3>';
    echo '<div class="staff-info">';
    echo '<div class="info-item">';
    echo '<i class="fas fa-briefcase"></i>';
    echo '<span><strong>Position:</strong> ' . htmlspecialchars($position) . '</span>';
    echo '</div>';
    echo '<div class="info-item">';
    echo '<i class="fas fa-envelope"></i>';
    echo '<span><strong>Email:</strong> ' . htmlspecialchars($email) . '</span>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
} else {
  echo '<div class="no-staff-message">';
  echo '<i class="fas fa-users" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.7;"></i>';
  echo '<p>No staff members found.</p>';
  echo '</div>';
}
$conn->close();
?>
</div>
</body>
</html>