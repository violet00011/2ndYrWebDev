<?php   
include 'connectdb.php'; 

$conn = openCon();

$vehicleOwnerQuery = "SELECT OwnerID, LastName, FirstName, MiddleName, Department, ContactNumber, Email, Position, Username, Password FROM vehicle_owner";
$vehicleOwnerResult = $conn->query($vehicleOwnerQuery);

closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Vehicle Owners</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      background-color: #f5f5f5;
    }

    .main {
      margin-left: 220px;
      padding: 15px;
      flex-grow: 1;
      background: url("Assets/langit.jpg") no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      overflow-y: auto;
      position: relative;
    }

    .main::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(1px);
      z-index: -1;
    }

    .header {
      background: linear-gradient(135deg, #800000, #a52a2a);
      color: white;
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 15px;
      box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      font-size: 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logout-btn {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      padding: 10px 20px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
    }

    .content-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      height: calc(100vh - 120px);
      overflow-y: auto;
    }

    .stats-container {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: linear-gradient(135deg, #800000, #a52a2a);
      color: white;
      padding: 15px;
      border-radius: 10px;
      flex: 1;
      text-align: center;
      box-shadow: 0 4px 15px rgba(128, 0, 0, 0.2);
    }

    .stat-card i {
      font-size: 1.8rem;
      margin-bottom: 8px;
      opacity: 0.9;
    }

    .stat-card h3 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .stat-card p {
      font-size: 0.8rem;
      opacity: 0.9;
    }

    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .table-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .add-btn {
      background: linear-gradient(135deg, #800000, #a52a2a);
      color: white;
      padding: 8px 18px;
      border-radius: 20px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 3px 10px rgba(128, 0, 0, 0.3);
      font-size: 0.9rem;
    }

    .add-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(128, 0, 0, 0.4);
    }

    .table-container {
      overflow-x: auto;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      min-width: 1000px;
    }

    table th {
      background: linear-gradient(135deg, #800000, #a52a2a);
      color: white;
      padding: 18px 15px;
      text-align: left;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    table th i {
      margin-right: 8px;
    }

    table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #f0f0f0;
      font-size: 0.9rem;
      color: #555;
    }

    table tr {
      transition: all 0.3s ease;
    }

    table tr:hover {
      background: linear-gradient(135deg, rgba(128, 0, 0, 0.05), rgba(165, 42, 42, 0.05));
      transform: scale(1.01);
    }

    .action-btns {
      display: flex;
      gap: 8px;
    }

    .action-btn {
      padding: 8px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.85rem;
    }

    .btn-view {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
    }

    .btn-edit {
      background: linear-gradient(135deg, #ffc107, #fd7e14);
      color: white;
    }

    .btn-delete {
      background: linear-gradient(135deg, #dc3545, #e74c3c);
      color: white;
    }

    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .no-data {
      text-align: center;
      padding: 50px;
      color: #888;
      font-size: 1.1rem;
    }

    .no-data i {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.5;
    }

    .password-cell {
      position: relative;
    }

    .password-hidden {
      color: #888;
      font-style: italic;
    }

    .badge {
      background: linear-gradient(135deg, #800000, #a52a2a);
      color: white;
      padding: 4px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .full-name {
      font-weight: 600;
      color: #333;
    }

    @media (max-width: 768px) {
      .main {
        margin-left: 0;
        padding: 20px;
      }

      .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      .stats-container {
        flex-direction: column;
      }

      .table-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
  <div class="header">
    <h1>
      <i class="fas fa-users-cog"></i>
      Manage Vehicle Owners
    </h1>
    <a href="index.php" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i>
      Logout
    </a>
  </div>

  <div class="content-card">
    <div class="stats-container">
      <div class="stat-card">
        <i class="fas fa-users"></i>
        <h3><?= $vehicleOwnerResult->num_rows ?></h3>
        <p>Total Owners</p>
      </div>
      <div class="stat-card">
        <i class="fas fa-car"></i>
        <h3>Active</h3>
        <p>Vehicle System</p>
      </div>
    </div>

    <div class="table-header">
      <div class="table-title">
        <i class="fas fa-table"></i>
        Vehicle Owners Directory
      </div>
      <a href="addvehicleowner.php" class="add-btn">
        <i class="fas fa-plus"></i>
        Register New Owner
      </a>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th><i class="fas fa-id-badge"></i>Owner ID</th>
            <th><i class="fas fa-user"></i>Full Name</th>
            <th><i class="fas fa-building"></i>Department</th>
            <th><i class="fas fa-phone"></i>Contact</th>
            <th><i class="fas fa-envelope"></i>Email</th>
            <th><i class="fas fa-briefcase"></i>Position</th>
            <th><i class="fas fa-user-circle"></i>Username</th>
            <th><i class="fas fa-lock"></i>Password</th>
            <th><i class="fas fa-cogs"></i>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($vehicleOwnerResult->num_rows > 0): ?>
            <?php while($owner = $vehicleOwnerResult->fetch_assoc()): ?>
              <?php
                // Concatenate full name with middle initial
                $middleInitial = !empty($owner['MiddleName']) ? strtoupper(substr($owner['MiddleName'], 0, 1)) . '.' : '';
                $fullName = trim($owner['FirstName'] . ' ' . $middleInitial . ' ' . $owner['LastName']);
              ?>
              <tr>
                <td><span class="badge"><?= htmlspecialchars($owner['OwnerID']) ?></span></td>
                <td class="full-name"><?= htmlspecialchars($fullName) ?></td>
                <td>
                  <i class="fas fa-building" style="color: #800000; margin-right: 5px;"></i>
                  <?= htmlspecialchars($owner['Department']) ?>
                </td>
                <td>
                  <i class="fas fa-phone" style="color: #800000; margin-right: 5px;"></i>
                  <?= htmlspecialchars($owner['ContactNumber']) ?>
                </td>
                <td>
                  <i class="fas fa-envelope" style="color: #800000; margin-right: 5px;"></i>
                  <?= htmlspecialchars($owner['Email']) ?>
                </td>
                <td><?= htmlspecialchars($owner['Position']) ?></td>
                <td>
                  <i class="fas fa-user-circle" style="color: #800000; margin-right: 5px;"></i>
                  <?= htmlspecialchars($owner['Username']) ?>
                </td>
                <td class="password-cell">
                  <span class="password-hidden">
                    <i class="fas fa-eye-slash" style="margin-right: 5px;"></i>
                    Hidden
                  </span>
                </td>
                <td class="action-btns">
                  <a href="viewvehicleowner.php?ownerid=<?= $owner['OwnerID'] ?>" class="action-btn btn-view" title="View Details">
                    <i class="fas fa-eye"></i>
                    View
                  </a>
                  <a href="editvehicleowner.php?ownerid=<?= $owner['OwnerID'] ?>" class="action-btn btn-edit" title="Edit Owner">
                    <i class="fas fa-edit"></i>
                    Edit
                  </a>
                  <a href="deletevehicleowner.php?ownerid=<?= $owner['OwnerID'] ?>" 
                     class="action-btn btn-delete" 
                     title="Delete Owner"
                     onclick="return confirm('⚠️ Are you sure you want to delete this vehicle owner?\n\nThis action cannot be undone.')">
                    <i class="fas fa-trash"></i>
                    Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="no-data">
                <i class="fas fa-users-slash"></i>
                <br>
                No vehicle owners found in the system.
                <br>
                <small>Click "Register New Owner" to add the first owner.</small>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// Add some interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation to action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!this.classList.contains('btn-delete')) {
                this.style.opacity = '0.7';
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            }
        });
    });

    // Add search functionality (basic)
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Search owners...';
    searchInput.style.cssText = `
        padding: 10px 15px;
        border: 2px solid #ddd;
        border-radius: 25px;
        font-size: 14px;
        width: 250px;
        margin-left: 20px;
    `;
    
    const tableHeader = document.querySelector('.table-header');
    if (tableHeader) {
        tableHeader.querySelector('.table-title').appendChild(searchInput);
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
</script>

</body>
</html>