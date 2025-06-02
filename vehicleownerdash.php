<?php
session_start();
include("connectdb.php");

if (!isset($_SESSION['owner_id'])) {
  header("Location: vehicleownerlogin.php");
  exit();
}

$conn = openCon();
$ownerId = $_SESSION['owner_id'];  

// Fetch owner information for welcome message
$sqlOwner = "SELECT FirstName, LastName FROM vehicle_owner WHERE OwnerID = ?";
$stmtOwner = $conn->prepare($sqlOwner);
$stmtOwner->bind_param("i", $ownerId);
$stmtOwner->execute();
$resultOwner = $stmtOwner->get_result();
$ownerInfo = $resultOwner->fetch_assoc();
$ownerName = $ownerInfo ? $ownerInfo['FirstName'] . ' ' . $ownerInfo['LastName'] : 'User';

// Fetch vehicle logs for the logged-in user
$sqlLogs = "SELECT vl.*, v.PlateNumber, v.Type FROM vehicle_log vl 
            JOIN vehicle v ON vl.VehicleID = v.VehicleID 
            WHERE v.OwnerID = ? 
            ORDER BY vl.TimeIn DESC";
$stmtLogs = $conn->prepare($sqlLogs);
$stmtLogs->bind_param("i", $ownerId);
$stmtLogs->execute();
$resultLogs = $stmtLogs->get_result();

$vehicleLogs = [];
while ($row = $resultLogs->fetch_assoc()) {
    $vehicleLogs[] = $row;
}

// Fetch registered vehicles for the logged-in user
$sqlVehicles = "SELECT * FROM vehicle WHERE OwnerID = ?";
$stmtVehicles = $conn->prepare($sqlVehicles);
$stmtVehicles->bind_param("i", $ownerId);
$stmtVehicles->execute();
$resultVehicles = $stmtVehicles->get_result();

$registeredVehicles = [];
while ($row = $resultVehicles->fetch_assoc()) {
    $registeredVehicles[] = $row;
}

// Analytics data
$totalVehicles = count($registeredVehicles);
$totalLogs = count($vehicleLogs);
$activeVehicles = 0;
$todayLogs = 0;

foreach ($vehicleLogs as $log) {
    // Count vehicles currently inside (TimeIn exists but TimeOut is NULL)
    if (!empty($log['TimeIn']) && empty($log['TimeOut'])) {
        $activeVehicles++;
    }
    // Count today's logs
    if (date('Y-m-d', strtotime($log['TimeIn'])) == date('Y-m-d')) {
        $todayLogs++;
    }
}

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vehicle Owner Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    nav {
      background-color: maroon;
      padding: 15px 30px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    nav h1 {
      font-size: 24px;
      font-weight: 600;
    }

    nav .nav-links a {
      color: white;
      text-decoration: none;
      margin-left: 15px;
      font-size: 16px;
      padding: 8px 16px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    nav .nav-links a:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .welcome-header {
      background: linear-gradient(135deg, rgba(128, 0, 0, 0.9), rgba(139, 0, 0, 0.8));
      color: white;
      padding: 30px 20px;
      text-align: center;
      backdrop-filter: blur(10px);
    }

    .welcome-header h1 {
      font-size: 2.5rem;
      font-weight: 300;
      margin-bottom: 10px;
    }

    .welcome-header .username {
      font-size: 1.8rem;
      font-weight: 600;
      color: #FFD700;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .welcome-header p {
      margin-top: 10px;
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .container {
      max-width: 1400px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: linear-gradient(135deg, white, #f8f9fa);
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
      border: 1px solid rgba(128, 0, 0, 0.1);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .stat-card .icon {
      font-size: 2.5rem;
      color: maroon;
      margin-bottom: 15px;
    }

    .stat-card .number {
      font-size: 2.2rem;
      font-weight: 600;
      color: maroon;
      margin-bottom: 5px;
    }

    .stat-card .label {
      color: #666;
      font-size: 1rem;
      font-weight: 500;
    }

    .main-content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      margin-bottom: 30px;
    }

    .section-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .section-header {
      background: linear-gradient(135deg, maroon, darkred);
      color: white;
      padding: 20px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .section-header h2 {
      font-size: 1.5rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .register-btn {
      padding: 10px 20px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s;
      font-weight: 500;
    }

    .register-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      border-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-2px);
    }

    .table-container {
      padding: 25px;
      max-height: 600px;
      overflow-y: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 15px 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
      vertical-align: middle;
    }

    th {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-weight: 600;
      color: maroon;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    tr:hover {
      background: linear-gradient(135deg, #f8f9fa, #fff);
    }

    .status-badge {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      text-align: center;
    }

    .status-outside {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-inside {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .vehicle-type {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 500;
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      color: #1565c0;
      border: 1px solid #90caf9;
    }

    .empty-message {
      text-align: center;
      padding: 60px 20px;
      color: #888;
    }

    .empty-message i {
      font-size: 4rem;
      color: #ddd;
      margin-bottom: 20px;
    }

    .empty-message h3 {
      font-size: 1.5rem;
      margin-bottom: 10px;
      color: #666;
    }

    .empty-message p {
      font-size: 1rem;
      line-height: 1.6;
    }

    .recent-activity {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
    max-height: 300px; 
    overflow-y: auto;
    }

    .recent-activity::-webkit-scrollbar {
    width: 6px;
    }

    .recent-activity::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
    }

    .activity-item {
      padding: 15px 25px;
      border-bottom: 1px solid #eee;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .activity-item:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: white;
    }

    .activity-in {
      background: linear-gradient(135deg, #28a745, #20c997);
    }

    .activity-out {
      background: linear-gradient(135deg, #dc3545, #fd7e14);
    }

    .activity-details h4 {
      margin-bottom: 5px;
      color: #333;
      font-weight: 500;
    }

    .activity-details p {
      color: #666;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .main-content {
        grid-template-columns: 1fr;
      }
      
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .welcome-header h1 {
        font-size: 2rem;
      }
      
      .welcome-header .username {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<nav>
  <h1><i class="fas fa-car"></i> BulSU Online Vehicle Gate System</h1>
  <div class="nav-links">
    <a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</nav>

<div class="welcome-header">
  <h1>Welcome, <span class="username"><?php echo htmlspecialchars($ownerName); ?></span>!</h1>
  <p><i class="fas fa-calendar-day"></i> Today is <?php echo date('F j, Y'); ?></p>
</div>

<div class="container">
  
  <div class="stats-grid">
    <div class="stat-card">
      <div class="icon"><i class="fas fa-car"></i></div>
      <div class="number"><?php echo $totalVehicles; ?></div>
      <div class="label">Registered Vehicles</div>
    </div>
    <div class="stat-card">
      <div class="icon"><i class="fas fa-road"></i></div>
      <div class="number"><?php echo $totalLogs; ?></div>
      <div class="label">Total Logs</div>
    </div>
    <div class="stat-card">
      <div class="icon"><i class="fas fa-calendar-check"></i></div>
      <div class="number"><?php echo $todayLogs; ?></div>
      <div class="label">Today's Activities</div>
    </div>
  </div>

  <div class="main-content">

    <!-- Left Section: Vehicle Logs -->
    <div class="section-card">
      <div class="section-header">
        <h2><i class="fas fa-history"></i> Recent Vehicle Activity</h2>
        <a href="vehicleregister.php" class="register-btn">
          <i class="fas fa-plus"></i> Register New Vehicle
        </a>
      </div>
      
      <div class="table-container">
        <?php if (!empty($vehicleLogs)): ?>
          <table>
            <thead>
              <tr>
                <th>Plate Number</th>
                <th>Vehicle Type</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
                <th>Gate</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_slice($vehicleLogs, 0, 10) as $log): ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($log['PlateNumber']); ?></strong></td>
                  <td><span class="vehicle-type"><?php echo htmlspecialchars($log['Type']); ?></span></td>
                  <td><?php echo date('M j, Y g:i A', strtotime($log['TimeIn'])); ?></td>
                  <td><?php echo $log['TimeOut'] ? date('M j, Y g:i A', strtotime($log['TimeOut'])) : '---'; ?></td>
                  <td>
                    <?php 
                    $status = !empty($log['TimeOut']) ? 'Outside' : 'Inside';
                    $statusClass = strtolower($status);
                    ?>
                    <span class="status-badge status-<?php echo $statusClass; ?>">
                      <?php echo $status; ?>
                    </span>
                  </td>
                  <td>Gate <?php echo htmlspecialchars($log['GateID']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty-message">
            <i class="fas fa-clipboard-list"></i>
            <h3>No Activity Yet</h3>
            <p>Your vehicle gate activities will appear here once you start using the system.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Section: Registered Vehicles & Quick Stats -->
    <div>
      <div class="section-card">
        <div class="section-header">
          <h2><i class="fas fa-car-side"></i> Your Vehicles</h2>
        </div>
        
        <div class="table-container">
          <?php if (!empty($registeredVehicles)): ?>
            <table>
              <thead>
                <tr>
                  <th>Plate</th>
                  <th>Type</th>
                  <th>Model</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($registeredVehicles as $vehicle): ?>
                  <tr>
                    <td><strong><?php echo htmlspecialchars($vehicle['PlateNumber']); ?></strong></td>
                    <td><span class="vehicle-type"><?php echo htmlspecialchars($vehicle['Type']); ?></span></td>
                    <td><?php echo htmlspecialchars($vehicle['Model']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="empty-message">
              <i class="fas fa-car-side"></i>
              <h3>No Vehicles Registered</h3>
              <p>Click "Register New Vehicle" to add your first vehicle to the system.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recent Activity Timeline -->
      <?php if (!empty($vehicleLogs)): ?>
      <div class="recent-activity">
        <div class="section-header">
          <h2><i class="fas fa-clock"></i> Latest Activity</h2>
        </div>
        
        <?php foreach (array_slice($vehicleLogs, 0, 5) as $log): ?>
          <div class="activity-item">
            <div class="activity-icon <?php echo !empty($log['TimeOut']) ? 'activity-out' : 'activity-in'; ?>">
              <i class="fas fa-<?php echo !empty($log['TimeOut']) ? 'arrow-left' : 'arrow-right'; ?>"></i>
            </div>
            <div class="activity-details">
              <h4><?php echo htmlspecialchars($log['PlateNumber']); ?> - <?php echo !empty($log['TimeOut']) ? 'Exited' : 'Entered'; ?></h4>
              <p><?php echo date('M j, Y g:i A', strtotime(!empty($log['TimeOut']) ? $log['TimeOut'] : $log['TimeIn'])); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>

</div>

</body>
</html>