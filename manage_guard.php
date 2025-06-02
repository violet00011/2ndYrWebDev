<?php
include 'connectdb.php';
$conn = openCon();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT * FROM guard";
$result = $conn->query($sql);
closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Guards</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    display: flex;
    background: linear-gradient(135deg, rgba(128, 0, 0, 0.1), rgba(0, 0, 0, 0.05));
}

.main {
    margin-left: 220px;
    padding: 30px;
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
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(1px);
    z-index: -1;
}

.header-card {
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(128, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title {
    font-size: 28px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 15px;
}

.logout-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.table-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 25px;
}

.table-header {
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    padding: 20px 30px;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-wrapper {
    overflow-x: auto;
    max-width: 100%;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
    min-width: 1000px;
}

table th, table td {
    padding: 16px 12px;
    text-align: left;
    border-bottom: 1px solid rgba(128, 0, 0, 0.1);
    font-size: 13px;
    white-space: nowrap;
}

table th {
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 11px;
    position: sticky;
    top: 0;
    z-index: 10;
}

table tbody tr {
    transition: all 0.3s ease;
}

table tbody tr:hover {
    background: linear-gradient(135deg, rgba(128, 0, 0, 0.05), rgba(128, 0, 0, 0.02));
    transform: scale(1.001);
}

table tbody tr:nth-child(even) {
    background: rgba(248, 249, 250, 0.5);
}

.guard-id {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.shift-badge {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 500;
    display: inline-block;
    margin: 2px 0;
}

.time-badge {
    background: linear-gradient(135deg, #6f42c1, #5a2d8a);
    color: white;
    padding: 3px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
    display: inline-block;
}

.gate-badge {
    background: linear-gradient(135deg, #fd7e14, #e55a00);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.action-btns {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.action-btns a {
    padding: 6px 12px;
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    border-radius: 15px;
    text-decoration: none;
    font-size: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    white-space: nowrap;
}

.action-btns a:hover {
    background: linear-gradient(135deg, #8B0000, #660000);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(128, 0, 0, 0.3);
}

.action-btns a.view-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.action-btns a.view-btn:hover {
    background: linear-gradient(135deg, #20c997, #17a2b8);
}

.action-btns a.delete-btn {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.action-btns a.delete-btn:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
}

.add-guard-container {
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.add-guard-btn {
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    padding: 15px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 25px rgba(128, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.add-guard-btn:hover {
    background: linear-gradient(135deg, #8B0000, #660000);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(128, 0, 0, 0.4);
}

.empty-state {
    text-align: center;
    padding: 60px 30px;
    color: #666;
}

.empty-state i {
    font-size: 64px;
    color: maroon;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: maroon;
}

.empty-state p {
    font-size: 16px;
    opacity: 0.7;
}

.password-masked {
    font-family: monospace;
    color: #666;
    letter-spacing: 2px;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 32px;
    color: maroon;
    margin-bottom: 10px;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: maroon;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 20px;
    }
    
    .header-card {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .header-title {
        font-size: 24px;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    table {
        min-width: 800px;
    }
    
    table th, table td {
        padding: 10px 8px;
        font-size: 11px;
    }
    
    .action-btns {
        flex-direction: column;
        gap: 4px;
    }
    
    .action-btns a {
        font-size: 9px;
        padding: 4px 8px;
    }
}

/* Scrollbar Styling */
.table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: rgba(128, 0, 0, 0.1);
    border-radius: 10px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, maroon, #8B0000);
    border-radius: 10px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #8B0000, #660000);
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="header-card">
        <div class="header-title">
            <i class="fas fa-shield-alt"></i>
            Manage Guards
        </div>
        <a href="adminlogin.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-number"><?= $result->num_rows ?></div>
            <div class="stat-label">Total Guards</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">24/7</div>
            <div class="stat-label">Security Coverage</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-number">
                <?php 
                mysqli_data_seek($result, 0);
                $gates = array();
                while($row = $result->fetch_assoc()) {
                    if (!in_array($row['GateID'], $gates)) {
                        $gates[] = $row['GateID'];
                    }
                }
                echo count($gates);
                ?>
            </div>
            <div class="stat-label">Gates Covered</div>
        </div>
    </div>
    <?php endif; ?>

    <div class="table-container">
        <div class="table-header">
            <i class="fas fa-table"></i>
            Guard Directory
        </div>
        
        <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-id-badge"></i> Guard ID</th>
                        <th><i class="fas fa-user"></i> Full Name</th>
                        <th><i class="fas fa-calendar-alt"></i> Shift Days</th>
                        <th><i class="fas fa-clock"></i> Start Time</th>
                        <th><i class="fas fa-clock"></i> End Time</th>
                        <th><i class="fas fa-door-open"></i> Gate</th>
                        <th><i class="fas fa-user-circle"></i> Username</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($result, 0);
                    while($row = $result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td>
                            <span class="guard-id">
                                G-<?= str_pad($row['GuardID'], 3, '0', STR_PAD_LEFT) ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            $fullName = htmlspecialchars($row['FirstName']) . ' ';
                            if (!empty($row['MiddleName'])) {
                                $fullName .= htmlspecialchars(substr($row['MiddleName'], 0, 1)) . '. ';
                            }
                            $fullName .= htmlspecialchars($row['LastName']);
                            echo $fullName;
                            ?>
                        </td>
                        <td>
                            <span class="shift-badge">
                                <?= htmlspecialchars($row['ShiftDays']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="time-badge">
                                <?= htmlspecialchars(date('h:i A', strtotime($row['ShiftHoursStart']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="time-badge">
                                <?= htmlspecialchars(date('h:i A', strtotime($row['ShiftHoursEnd']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="gate-badge">
                                Gate <?= htmlspecialchars($row['GateID']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($row['Username']) ?></td>
                        <td class="action-btns">
                            <a href="viewguard.php?guardid=<?= $row['GuardID']; ?>" class="view-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="editguard.php?id=<?= $row['GuardID'] ?>">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="deleteguard.php?id=<?= $row['GuardID'] ?>" 
                               class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this guard?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-shield-alt"></i>
            <h3>No Guards Found</h3>
            <p>Get started by adding your first security guard.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="add-guard-container">
        <a href="addguard.php" class="add-guard-btn">
            <i class="fas fa-plus"></i>
            Add New Guard
        </a>
    </div>
</div>
</body>
</html>