<?php
include 'connectdb.php';
$conn = openCon();
$gateQuery = "SELECT * FROM gate";
$gateResult = $conn->query($gateQuery);
closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Gates</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    display: flex;
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
    flex-wrap: wrap;
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
}

.btn-view {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.btn-view:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
}

.btn-edit {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
}

.btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
    box-shadow: 0 2px 8px rgb(124, 51, 51, 0.03));
}

.btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
}

.add-btn-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn-add {
    background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
    color: white;
    padding: 14px 28px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(128, 0, 0, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(128, 0, 0, 0.4);
    background: linear-gradient(135deg, #8B0000 0%, #A0522D 100%);
}

.btn-add::before {
    content: '';
    font-size: 18px;
    font-weight: bold;
}

.btn-add i {
    font-size: 16px;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #666;
    font-style: italic;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(76, 175, 80, 0.1);
    color: #4CAF50;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.status-inactive {
    background: rgba(244, 67, 54, 0.1);
    color: #f44336;
    border: 1px solid rgba(244, 67, 54, 0.3);
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
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
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
                <i class="fas fa-door-open"></i>
                Manage Gates
            </h1>
            <a href="adminlogin.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>

        <div class="table-card">
            <div class="table-header">
                <i class="fas fa-list-ul"></i>
                Gate Management System
            </div>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-id-card"></i> Gate ID</th>
                        <th><i class="fas fa-university"></i> Campus</th>
                        <th><i class="fas fa-door-open"></i> Gate Number</th>
                        <th><i class="fas fa-circle-check"></i> Status</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($gateResult->num_rows > 0): ?>
                        <?php while($gate = $gateResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($gate['GateID']) ?></td>
                            <td><?= htmlspecialchars($gate['Campus']) ?></td>
                            <td><?= htmlspecialchars($gate['GateNumber']) ?></td>
                            <td>
                                <span class="status-badge <?= strtolower($gate['Status']) === 'active' ? 'status-active' : 'status-inactive' ?>">
                                    <?= htmlspecialchars($gate['Status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="viewgate.php?gateid=<?= $gate['GateID'] ?>" class="btn btn-view">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <a href="editgate.php?gateid=<?= $gate['GateID'] ?>" class="btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <a href="deletegate.php?gateid=<?= $gate['GateID'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this gate?')">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">No gates found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="add-btn-container">
            <a href="addgate.php" class="btn-add">Add New Gate</a>
        </div>
    </div>
</div>
</body>
</html>