<?php  
include 'connectdb.php'; 

$conn = openCon();

$vehicleQuery = "SELECT * FROM vehicle";
$vehicleResult = $conn->query($vehicleQuery);

closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Vehicles</title>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
    }

    .main {
      margin-left: 220px;
      padding: 20px;
      flex-grow: 1;
      background: url("Assets/langit.jpg") no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
    }

    nav {
      background-color: maroon;
      color: white;
      padding: 15px;
      font-size: 18px;
      margin-bottom: 20px;
    }

    .table-container {
      margin-top: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    table th, table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: maroon;
      color: white;
    }

    table tr:hover {
      background-color: #f1f1f1;
    }

    .action-btns {
      display: flex;
      gap: 10px;
    }

    .action-btns a {
      padding: 10px 10px;
      background-color: maroon;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .action-btns a:hover {
      background-color: #5c0000;
    }

    img.plate-img {
      max-width: 100px;
      height: auto;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
<nav style="margin-bottom: 20px;">
  Manage Vehicles
  <a href="adminlogin.php" style="float: right; color: white; font-weight: bold;">Logout</a> 
  <a href="admindashboard.php" style="float: right; color: white; font-weight: bold; margin-right: 10px;">Admin Dashboard</a> 
</nav>


  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Vehicle ID</th>
          <th>Plate Number</th>
          <th>Type</th>
          <th>Model</th>
          <th>Owner ID</th>
          <th>Status</th>
          <th>Plate Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($vehicleResult->num_rows > 0): ?>
          <?php while($vehicle = $vehicleResult->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($vehicle['VehicleID']) ?></td>
              <td><?= htmlspecialchars($vehicle['PlateNumber']) ?></td>
              <td><?= htmlspecialchars($vehicle['Type']) ?></td>
              <td><?= htmlspecialchars($vehicle['Model']) ?></td>
              <td><?= htmlspecialchars($vehicle['OwnerID']) ?></td>
              <td><?= htmlspecialchars($vehicle['Status']) ?></td>
              <td>
                <?php if (!empty($vehicle['PlateNumberImage'])): ?>
                  <img src="<?= htmlspecialchars($vehicle['PlateNumberImage']) ?>" alt="Plate Image" class="plate-img">
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </td>
              <td class="action-btns">
              <a href="#?gateid=<?= $gate['GateID'] ?>">View</a>
                <a href="editvehicle.php?vehicleid=<?= $vehicle['VehicleID'] ?>">Edit</a>
                <a href="deletevehicle.php?vehicleid=<?= $vehicle['VehicleID'] ?>" onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8">No vehicles found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="action-btns" style="margin-top: 20px;">
    <a href="addvehicle.php">Add New Vehicle</a>
  </div>
</div>

</body>
</html>
