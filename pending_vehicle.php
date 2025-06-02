<?php
include 'connectdb.php';
$conn = openCon();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $getVehicle = $conn->prepare("SELECT * FROM vehicle_approval WHERE ID = ?");
        $getVehicle->bind_param("i", $id);
        $getVehicle->execute();
        $result = $getVehicle->get_result();
        $vehicle = $result->fetch_assoc();

        if ($vehicle) {
            $insert = $conn->prepare("INSERT INTO vehicle (PlateNumber, Type, Model, OwnerID, Status, PlateNumberImage) VALUES (?, ?, ?, ?, 'Active', ?)");
            $insert->bind_param("sssds", $vehicle['PlateNumber'], $vehicle['Type'], $vehicle['Model'], $vehicle['OwnerID'], $vehicle['PlateNumberImage']);
            $insert->execute();
        }

        $update = $conn->prepare("UPDATE vehicle_approval SET Reg_Stat = 'Approved' WHERE ID = ?");
        $update->bind_param("i", $id);
        $update->execute();

    } elseif ($action === 'deny') {
        $update = $conn->prepare("UPDATE vehicle_approval SET Reg_Stat = 'Denied' WHERE ID = ?");
        $update->bind_param("i", $id);
        $update->execute();
    }
}

$sql = "SELECT * FROM vehicle_approval WHERE Reg_Stat = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pending Vehicle Registrations</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    display: flex;
    background: url("Assets/langit.jpg") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
}

.main {
    margin-left: 220px;
    padding: 30px;
    flex-grow: 1;
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
    background: rgba(0, 0, 0, 0.05);
    pointer-events: none;
}

.content-wrapper {
    position: relative;
    z-index: 1;
}

.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    padding: 24px 32px;
    margin-bottom: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    color: maroon;
    font-size: 28px;
    font-weight: 600;
    margin: 0;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    font-size: 28px;
}

.logout-btn {
    background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(128, 0, 0, 0.4);
    background: linear-gradient(135deg, #8B0000 0%, #A0522D 100%);
}

.table-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
}

.table-header {
    background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
    color: white;
    padding: 20px 32px;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}

.table-header i {
    font-size: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
}

table th {
    background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
    color: white;
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none;
}

table th i {
    margin-right: 8px;
    font-size: 14px;
}

table td {
    padding: 16px 20px;
    text-align: left;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    color: #333;
    font-weight: 500;
}

table tr:hover {
    background: rgba(128, 0, 0, 0.05);
    transform: scale(1.001);
    transition: all 0.2s ease;
}

table tr:last-child td {
    border-bottom: none;
}

.action-btns {
    display: flex;
    gap: 8px;
    flex-direction: column;
}

.btn {
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 500;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
}

.btn-approve {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.btn-approve:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
}

.btn-deny {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
}

.btn-deny:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
}

.vehicle-image {
    max-height: 60px;
    max-width: 80px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    cursor: pointer;
}

.vehicle-image:hover {
    transform: scale(1.1);
}

.no-image {
    color: #999;
    font-style: italic;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.no-image i {
    color: #ccc;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-state i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: maroon;
    font-size: 24px;
}

.empty-state p {
    margin: 0;
    font-size: 16px;
}

.status-pending {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: rgba(255, 193, 7, 0.1);
    color: #FFC107;
    border: 1px solid rgba(255, 193, 7, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.plate-number {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 4px 8px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    display: inline-block;
}

@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 20px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .action-btns {
        flex-direction: row;
    }
    
    table {
        font-size: 14px;
    }
    
    table th, table td {
        padding: 12px 8px;
    }
    
    .vehicle-image {
        max-height: 40px;
        max-width: 60px;
    }
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-car"></i>
                Pending Vehicle Registrations
            </h1>
            <a href="index.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>

        <div class="table-card">
            <div class="table-header">
                <i class="fas fa-clock"></i>
                Vehicle Registration Approvals
            </div>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-id-card"></i> Plate Number</th>
                        <th><i class="fas fa-car-side"></i> Type</th>
                        <th><i class="fas fa-cog"></i> Model</th>
                        <th><i class="fas fa-user"></i> Owner ID</th>
                        <th><i class="fas fa-calendar-alt"></i> Date Registered</th>
                        <th><i class="fas fa-image"></i> Image</th>
                        <th><i class="fas fa-tasks"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ID']) ?></td>
                            <td>
                                <span class="plate-number"><?= htmlspecialchars($row['PlateNumber']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['Type']) ?></td>
                            <td><?= htmlspecialchars($row['Model']) ?></td>
                            <td><?= htmlspecialchars($row['OwnerID']) ?></td>
                            <td><?= htmlspecialchars($row['DateRegistered']) ?></td>
                            <td>
                                <?php if ($row['PlateNumberImage']): ?>
                                    <img src="<?= htmlspecialchars($row['PlateNumberImage']) ?>" 
                                         alt="Vehicle Plate" 
                                         class="vehicle-image"
                                         onclick="window.open(this.src, '_blank')">
                                <?php else: ?>
                                    <span class="no-image">
                                        <i class="fas fa-image"></i>
                                        No Image
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-approve">
                                            <i class="fas fa-check"></i>
                                            Approve
                                        </button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                        <input type="hidden" name="action" value="deny">
                                        <button type="submit" class="btn btn-deny" onclick="return confirm('Are you sure you want to deny this registration?')">
                                            <i class="fas fa-times"></i>
                                            Deny
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h3>No Pending Registrations</h3>
                                    <p>All vehicle registrations have been processed.</p>
                                </div>
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

<?php closeCon($conn); ?>