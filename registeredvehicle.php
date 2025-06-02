<?php  
include 'connectdb.php'; 

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = openCon();

// Fetch only APPROVED vehicles and join with vehicle_owner
$vehicleQuery = "
  SELECT v.VehicleID, v.PlateNumber, v.Type, v.Model, v.OwnerID, v.PlateNumberImage, v.DateRegistered,
         CONCAT(o.FirstName, ' ', o.LastName) AS OwnerFullName, v.Reg_Stat
  FROM vehicle v
  INNER JOIN vehicle_owner o ON v.OwnerID = o.OwnerID
  WHERE v.Reg_Stat = 'Approved'
";
$vehicleResult = $conn->query($vehicleQuery);

closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Vehicles</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      background: #f5f5f5;
      overflow-x: hidden;
    }

    .main {
      margin-left: 220px;
      padding: 0;
      flex-grow: 1;
      background: url("Assets/langit.jpg") no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      position: relative;
    }

    .main::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(139, 0, 0, 0.05) 0%, rgba(255, 255, 255, 0.1) 100%);
      backdrop-filter: blur(2px);
      z-index: 1;
    }

    .content-wrapper {
      position: relative;
      z-index: 2;
      padding: 25px;
    }

    nav {
      background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
      color: white;
      padding: 25px 35px;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 35px;
      border-radius: 20px;
      box-shadow: 
        0 20px 40px rgba(139, 0, 0, 0.2),
        0 0 0 1px rgba(255, 255, 255, 0.1) inset;
      backdrop-filter: blur(20px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    nav::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transition: left 0.8s ease;
    }

    nav:hover::before {
      left: 100%;
    }

    nav:hover {
      transform: translateY(-3px) scale(1.01);
      box-shadow: 
        0 25px 50px rgba(139, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.2) inset;
    }

    .nav-title {
      display: flex;
      align-items: center;
      gap: 15px;
      position: relative;
      z-index: 2;
    }

    .nav-title i {
      font-size: 32px;
      color: #FFD700;
      filter: drop-shadow(0 2px 4px rgba(255, 215, 0, 0.3));
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .logout-btn {
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      padding: 12px 24px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      z-index: 2;
      overflow: hidden;
    }

    .logout-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .logout-btn:hover::before {
      left: 100%;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      border-color: rgba(255, 255, 255, 0.3);
    }

    .table-container {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(30px);
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 
        0 30px 80px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.5) inset;
      border: 1px solid rgba(255, 255, 255, 0.3);
      position: relative;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .table-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, maroon 0%, #FFD700 50%, maroon 100%);
      z-index: 1;
    }

    .table-container:hover {
      transform: translateY(-6px);
      box-shadow: 
        0 40px 100px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.6) inset;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      position: relative;
    }

    table th, table td {
      padding: 16px 24px;
      text-align: left;
      border-bottom: 1px solid rgba(139, 0, 0, 0.08);
      position: relative;
      vertical-align: middle;
    }

    table th:first-child, table td:first-child {
      padding-left: 30px;
      width: -30%;
    }

    table th:nth-child(2), table td:nth-child(2) {
      width: 10%;
    }

    table th:nth-child(3), table td:nth-child(3) {
      width: 12%;
    }

    table th:nth-child(4), table td:nth-child(4) {
      width: 15%;
    }

    table th:nth-child(5), table td:nth-child(5) {
      width: 20%;
    }

    table th:nth-child(6), table td:nth-child(6) {
      width: 10%;
    }

    table th:nth-child(7), table td:nth-child(7) {
      width: 13%;
    }

    table th:last-child, table td:last-child {
      padding-right: 30px;
      width: 5%;
    }

    table th {
      background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
      color: white;
      font-weight: 700;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: sticky;
      top: 0;
      z-index: 10;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    table th i {
      margin-right: 8px;
      color: #FFD700;
      filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
    }

    table th::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #FFD700 0%, #FFA500 50%, #FFD700 100%);
    }

    table tbody tr {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      cursor: pointer;
    }

    table tbody tr::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 5px;
      height: 100%;
      background: linear-gradient(135deg, maroon 0%, #FFD700 100%);
      transform: scaleY(0);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 0 3px 3px 0;
    }

    table tbody tr:hover {
      background: linear-gradient(90deg, rgba(139, 0, 0, 0.03) 0%, rgba(255, 215, 0, 0.02) 100%);
      transform: translateX(3px);
      box-shadow: 0 8px 32px rgba(139, 0, 0, 0.08);
    }

    table tbody tr:hover::before {
      transform: scaleY(1);
    }

    table td {
      font-weight: 500;
      color: #2c3e50;
      font-size: 15px;
      margin-left:100px;
    }

    .action-btns {
      display: flex;
      gap: 10px;
      justify-content: center;
    }

    .action-btns a {
      padding: 10px 18px;
      color: white;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      gap: 8px;
      position: relative;
      overflow: hidden;
      min-width: 90px;
      justify-content: center;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .action-btns a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.6s ease;
    }

    .action-btns a:hover::before {
      left: 100%;
    }

    .edit-btn {
      background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
    }

    .edit-btn:hover {
      background: linear-gradient(135deg, #388E3C 0%, #1B5E20 100%);
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(76, 175, 80, 0.4);
    }

    .delete-btn {
      background: linear-gradient(135deg, #f44336 0%, #C62828 100%);
      box-shadow: 0 6px 20px rgba(244, 67, 54, 0.3);
    }

    .delete-btn:hover {
      background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%);
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(244, 67, 54, 0.4);
    }

    .action-btns a:active {
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    img.plate-img {
      max-width: 150px;
      height: 70px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      border: 2px solid rgba(139, 0, 0, 0.1);
    }

    img.plate-img:hover {
      transform: scale(1.15) rotate(2deg);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
      z-index: 100;
      position: relative;
      border-color: maroon;
    }

    .status-badge {
      padding: 8px 16px;
      border-radius: 25px;
      font-weight: 700;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
      color: white;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
      transition: all 0.3s ease;
    }

    .status-badge:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .status-badge i {
      font-size: 12px;
      filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
    }

    .no-data {
      text-align: center;
      padding: 80px 40px;
      color: #7f8c8d;
      font-size: 20px;
      font-weight: 500;
    }

    .no-data i {
      font-size: 64px;
      color: maroon;
      margin-bottom: 25px;
      display: block;
      opacity: 0.7;
    }

    .vehicle-id {
      font-weight: 800;
      color: maroon;
      font-size: 16px;
      text-shadow: 0 1px 2px rgba(139, 0, 0, 0.1);
    }

    .plate-number {
      font-weight: 700;
      color: #34495e;
      font-size: 15px;
      letter-spacing: 0.5px;
    }

    .owner-name {
      color: #2c3e50;
      font-weight: 600;
      font-size: 15px;
    }

    .vehicle-details {
      color: #5d6d7e;
      font-size: 14px;
      font-weight: 500;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .table-container {
      animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    nav {
      animation: slideInLeft 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    table tbody tr {
      animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
      animation-fill-mode: both;
    }

    table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    table tbody tr:nth-child(2) { animation-delay: 0.15s; }
    table tbody tr:nth-child(3) { animation-delay: 0.2s; }
    table tbody tr:nth-child(4) { animation-delay: 0.25s; }
    table tbody tr:nth-child(5) { animation-delay: 0.3s; }

    @media (max-width: 768px) {
      .main {
        margin-left: 0;
      }
      
      .content-wrapper {
        padding: 15px;
      }
      
      nav {
        padding: 20px 25px;
        font-size: 22px;
      }
      
      .action-btns {
        flex-direction: column;
        gap: 6px;
      }
      
      .action-btns a {
        min-width: auto;
        padding: 8px 14px;
        font-size: 12px;
      }
      
      table th, table td {
        padding: 12px 16px;
        font-size: 13px;
      }
      
      img.plate-img {
        max-width: 80px;
        height: 55px;
      }
      
      .table-container {
        border-radius: 16px;
      }
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(139, 0, 0, 0.1);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #8B0000 0%, maroon 100%);
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
  <div class="content-wrapper">
    <nav>
      <div class="nav-title">
        <i class="fas fa-car"></i>
        Registered Vehicles
      </div>
      <a href="adminlogin.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
        Logout
      </a> 
    </nav>

    <div class="table-container">
      <table>
        <thead>
          <tr>
          <th></th>
            <th><i class="fas fa-hashtag"></i> Vehicle ID</th>
            <th><i class="fas fa-clipboard-list"></i> Plate Number</th>
            <th><i class="fas fa-car-side"></i> Type</th>
            <th><i class="fas fa-cogs"></i> Model</th>
            <th><i class="fas fa-user"></i> Owner Name</th>
            <th><i class="fas fa-check-circle"></i> Status</th>
            <th><i class="fas fa-image"></i> Plate Image</th>
            <th><i class="fas fa-tools"></i> Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($vehicleResult && $vehicleResult->num_rows > 0): ?>
            <?php while($vehicle = $vehicleResult->fetch_assoc()): ?>
              <tr>
                <td class="vehicle-id"><?= htmlspecialchars($vehicle['VehicleID']) ?></td>
                <td class="plate-number"><?= htmlspecialchars($vehicle['PlateNumber']) ?></td>
                <td class="vehicle-details"><?= htmlspecialchars($vehicle['Type']) ?></td>
                <td class="vehicle-details"><?= htmlspecialchars($vehicle['Model']) ?></td>
                <td class="owner-name"><?= htmlspecialchars($vehicle['OwnerFullName']) ?></td>
                <td>
                  <span class="status-badge">
                    <i class="fas fa-check"></i>
                    <?= htmlspecialchars($vehicle['Reg_Stat']) ?>
                  </span>
                </td>
                <td>
                  <?php if (!empty($vehicle['PlateNumberImage'])): ?>
                    <img src="<?= htmlspecialchars($vehicle['PlateNumberImage']) ?>" alt="Plate Image" class="plate-img" title="Click to view larger">
                  <?php else: ?>
                    <span style="color: #bdc3c7; font-style: italic;">
                      <i class="fas fa-image-slash"></i> No Image
                    </span>
                  <?php endif; ?>
                </td>
                <td class="action-btns">
                  <a href="editvehicle.php?vehicleid=<?= $vehicle['VehicleID'] ?>" class="edit-btn" title="Edit Vehicle">
                    <i class="fas fa-edit"></i>
                    Edit
                  </a>
                  <a href="deletevehicle.php?vehicleid=<?= $vehicle['VehicleID'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this vehicle?')"                               title="Delete Vehicle">
                    <i class="fas fa-trash"></i>
                    Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="no-data">
                <i class="fas fa-car-crash"></i>
                No approved vehicles found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>