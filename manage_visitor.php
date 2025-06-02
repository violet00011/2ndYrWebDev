<?php
include 'connectdb.php';

$conn = openCon();

$visitorQuery = "SELECT VisitorID, LastName, FirstName, MiddleName, ContactNumber, ScheduledVisit, VehicleModel, PlateNumber, GateID, Purpose, Status FROM visitor";
$visitorResult = $conn->query($visitorQuery);

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Visitors</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: sans-serif;
      display: flex;
      background: #f5f5f5;
      min-height: 100vh;
    }

    .main {
      margin-left: 220px;
      padding: 20px;
      flex-grow: 1;
      background: #f5f5f5;
      min-height: 100vh;
    }

    nav {
      background: linear-gradient(135deg, maroon, #a52a2a);
      color: white;
      padding: 20px;
      font-size: 18px;
      margin-bottom: 20px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(128, 0, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: space-between;
      backdrop-filter: blur(10px);
    }

    .nav-title {
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: bold;
    }

    .nav-title i {
      font-size: 24px;
    }

    .logout-btn {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 25px;
      background: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .stats-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 25px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      gap: 15px;
      transition: all 0.3s ease;
      border: 1px solid rgba(128, 0, 0, 0.1);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border-color: maroon;
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: white;
      background: linear-gradient(135deg, maroon, #a52a2a);
    }

    .stat-info h3 {
      font-size: 24px;
      color: maroon;
      margin-bottom: 5px;
    }

    .stat-info p {
      color: #666;
      font-size: 14px;
    }

    .table-container {
      background: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(128, 0, 0, 0.1);
    }

    .table-header {
      background: linear-gradient(135deg, maroon, #a52a2a);
      color: white;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .table-title {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 18px;
      font-weight: bold;
    }

    .search-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      padding: 10px 15px 10px 40px;
      border: none;
      border-radius: 25px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 14px;
      width: 250px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .search-box input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.7);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th, table td {
      padding: 15px 12px;
      text-align: left;
      border-bottom: 1px solid #f1f1f1;
      vertical-align: middle;
    }

    table th {
      background: linear-gradient(135deg, #8B0000, maroon);
      color: white;
      font-weight: bold;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    table tbody tr {
      transition: all 0.3s ease;
    }

    table tbody tr:hover {
      background: linear-gradient(135deg, #fff8f8, #ffeaea);
      transform: scale(1.01);
      box-shadow: 0 2px 10px rgba(128, 0, 0, 0.1);
    }

    table tbody tr:nth-child(even) {
      background: #fafafa;
    }

    table tbody tr:nth-child(even):hover {
      background: linear-gradient(135deg, #fff8f8, #ffeaea);
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .status-active {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .status-expired {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .action-btns {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .action-btns a {
      padding: 8px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 12px;
      font-weight: bold;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 6px;
      border: 1px solid transparent;
    }

    .btn-view {
      background: linear-gradient(135deg, #17a2b8, #138496);
      color: white;
    }

    .btn-view:hover {
      background: linear-gradient(135deg, #138496, #117a8b);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    }

    .btn-edit {
      background: linear-gradient(135deg, #28a745, #218838);
      color: white;
    }

    .btn-edit:hover {
      background: linear-gradient(135deg, #218838, #1e7e34);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-delete {
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
    }

    .btn-delete:hover {
      background: linear-gradient(135deg, #c82333, #bd2130);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .add-visitor-btn {
      margin-top: 20px;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 15px 25px;
      background: linear-gradient(135deg, maroon, #a52a2a);
      color: white;
      text-decoration: none;
      border-radius: 12px;
      font-weight: bold;
      font-size: 16px;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3);
    }

    .add-visitor-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(128, 0, 0, 0.4);
      background: linear-gradient(135deg, #8B0000, #DC143C);
    }

    .no-data {
      text-align: center;
      padding: 60px 20px;
      color: #666;
    }

    .no-data i {
      font-size: 4rem;
      margin-bottom: 20px;
      color: #ddd;
    }

    .no-data h3 {
      margin-bottom: 10px;
      color: #999;
    }

    .visitor-id {
      font-family: monospace;
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 4px 8px;
      border-radius: 6px;
      border: 1px solid #dee2e6;
      font-weight: bold;
      color: maroon;
    }

    .gate-badge {
      background: linear-gradient(135deg, maroon, #a52a2a);
      color: white;
      padding: 4px 10px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .vehicle-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .vehicle-model {
      font-weight: bold;
      color: #333;
    }

    .plate-number {
      font-size: 11px;
      color: #666;
      font-family: monospace;
      background: #f8f9fa;
      padding: 2px 6px;
      border-radius: 4px;
    }

    .contact-info {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #555;
    }

    .purpose-text {
      max-width: 150px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      color: #666;
    }

    @media (max-width: 768px) {
      .main {
        margin-left: 0;
        padding: 15px;
      }
      
      .stats-cards {
        grid-template-columns: 1fr;
      }
      
      .search-box input {
        width: 200px;
      }
      
      table {
        font-size: 12px;
      }
      
      .action-btns {
        flex-direction: column;
        gap: 5px;
      }
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
  <nav>
    <div class="nav-title">
      <i class="fas fa-users"></i>
      Manage Visitors
    </div>
    <a href="index.php" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i>
      Logout
    </a>
  </nav>

  <!-- Statistics Cards -->
  <div class="stats-cards">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="stat-info">
        <h3><?= $visitorResult->num_rows ?></h3>
        <p>Total Visitors</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-user-clock"></i>
      </div>
      <div class="stat-info">
        <h3><?php 
          $conn = openCon();
          $activeQuery = "SELECT COUNT(*) as count FROM visitor WHERE Status = 'Active'";
          $activeResult = $conn->query($activeQuery);
          $activeCount = $activeResult->fetch_assoc()['count'];
          echo $activeCount;
          closeCon($conn);
        ?></h3>
        <p>Active Visits</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-calendar-day"></i>
      </div>
      <div class="stat-info">
        <h3><?php 
          $conn = openCon();
          $todayQuery = "SELECT COUNT(*) as count FROM visitor WHERE DATE(ScheduledVisit) = CURDATE()";
          $todayResult = $conn->query($todayQuery);
          $todayCount = $todayResult->fetch_assoc()['count'];
          echo $todayCount;
          closeCon($conn);
        ?></h3>
        <p>Today's Visits</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-car"></i>
      </div>
      <div class="stat-info">
        <h3><?php 
          $conn = openCon();
          $vehicleQuery = "SELECT COUNT(*) as count FROM visitor WHERE VehicleModel IS NOT NULL AND VehicleModel != ''";
          $vehicleResult = $conn->query($vehicleQuery);
          $vehicleCount = $vehicleResult->fetch_assoc()['count'];
          echo $vehicleCount;
          closeCon($conn);
        ?></h3>
        <p>With Vehicles</p>
      </div>
    </div>
  </div>

  <div class="table-container">
    <div class="table-header">
      <div class="table-title">
        <i class="fas fa-table"></i>
        Visitor Records
      </div>
      <div class="search-container">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search visitors..." id="searchInput">
        </div>
      </div>
    </div>
    
    <table id="visitorTable">
      <thead>
        <tr>
          <th><i class="fas fa-id-card"></i> Visitor ID</th>
          <th><i class="fas fa-user"></i> Full Name</th>
          <th><i class="fas fa-phone"></i> Contact</th>
          <th><i class="fas fa-calendar"></i> Visit Schedule</th>
          <th><i class="fas fa-car"></i> Vehicle Info</th>
          <th><i class="fas fa-door-open"></i> Gate</th>
          <th><i class="fas fa-clipboard"></i> Purpose</th>
          <th><i class="fas fa-info-circle"></i> Status</th>
          <th><i class="fas fa-cogs"></i> Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($visitorResult->num_rows > 0): ?>
          <?php while($visitor = $visitorResult->fetch_assoc()): ?>
            <tr>
              <td>
                <span class="visitor-id"><?= htmlspecialchars($visitor['VisitorID']) ?></span>
              </td>
              <td>
                <strong><?= htmlspecialchars($visitor['LastName']) ?>, <?= htmlspecialchars($visitor['FirstName']) ?></strong>
                <?php if (!empty($visitor['MiddleName'])): ?>
                  <br><small style="color: #666;"><?= htmlspecialchars($visitor['MiddleName']) ?></small>
                <?php endif; ?>
              </td>
              <td>
                <div class="contact-info">
                  <i class="fas fa-phone" style="color: maroon;"></i>
                  <?= htmlspecialchars($visitor['ContactNumber']) ?>
                </div>
              </td>
              <td>
                <i class="fas fa-calendar-alt" style="color: maroon; margin-right: 5px;"></i>
                <?= date('M j, Y', strtotime($visitor['ScheduledVisit'])) ?>
                <br><small style="color: #666;"><?= date('h:i A', strtotime($visitor['ScheduledVisit'])) ?></small>
              </td>
              <td>
                <?php if (!empty($visitor['VehicleModel'])): ?>
                  <div class="vehicle-info">
                    <div class="vehicle-model">
                      <i class="fas fa-car" style="color: maroon; margin-right: 5px;"></i>
                      <?= htmlspecialchars($visitor['VehicleModel']) ?>
                    </div>
                    <?php if (!empty($visitor['PlateNumber'])): ?>
                      <div class="plate-number"><?= htmlspecialchars($visitor['PlateNumber']) ?></div>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <span style="color: #999;"><i class="fas fa-walking"></i> On foot</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="gate-badge">
                  <i class="fas fa-door-open"></i>
                  <?= htmlspecialchars(str_replace('_', ' ', $visitor['GateID'])) ?>
                </span>
              </td>
              <td>
                <div class="purpose-text" title="<?= htmlspecialchars($visitor['Purpose']) ?>">
                  <i class="fas fa-clipboard-list" style="color: maroon; margin-right: 5px;"></i>
                  <?= htmlspecialchars($visitor['Purpose']) ?>
                </div>
              </td>
              <td>
                <?php 
                $statusClass = 'status-pending';
                $statusIcon = 'clock';
                if ($visitor['Status'] == 'Active') {
                  $statusClass = 'status-active';
                  $statusIcon = 'check-circle';
                } elseif ($visitor['Status'] == 'Expired') {
                  $statusClass = 'status-expired';
                  $statusIcon = 'times-circle';
                }
                ?>
                <span class="status-badge <?= $statusClass ?>">
                  <i class="fas fa-<?= $statusIcon ?>"></i>
                  <?= htmlspecialchars($visitor['Status']) ?>
                </span>
              </td>
              <td class="action-btns">
                <a href="viewvisitor.php?visitorid=<?= $visitor['VisitorID'] ?>" class="btn-view">
                  <i class="fas fa-eye"></i>
                  View
                </a>
                <a href="editvisitor.php?visitorid=<?= $visitor['VisitorID'] ?>" class="btn-edit">
                  <i class="fas fa-edit"></i>
                  Edit
                </a>
                <a href="deletevisitor.php?visitorid=<?= $visitor['VisitorID'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this visitor record?')">
                  <i class="fas fa-trash"></i>
                  Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="no-data">
              <i class="fas fa-users"></i>
              <h3>No visitors found</h3>
              <p>Start by registering your first visitor</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <a href="addvisitor.php" class="add-visitor-btn">
    <i class="fas fa-user-plus"></i>
    Register New Visitor
  </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Search functionality
  const searchInput = document.getElementById('searchInput');
  const table = document.getElementById('visitorTable');
  const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

  searchInput.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    
    for (let i = 0; i < rows.length; i++) {
      let row = rows[i];
      let cells = row.getElementsByTagName('td');
      let found = false;
      
      for (let j = 0; j < cells.length; j++) {
        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }
      
      if (found) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    }
  });

  // Add row hover effects
  const tableRows = document.querySelectorAll('tbody tr');
  tableRows.forEach(row => {
    row.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.01)';
    });
    
    row.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });

  // Smooth scroll for long tables
  const tableContainer = document.querySelector('.table-container');
  if (tableContainer.scrollHeight > tableContainer.clientHeight) {
    tableContainer.style.maxHeight = '70vh';
    tableContainer.style.overflowY = 'auto';
  }
});
</script>

</body>
</html>