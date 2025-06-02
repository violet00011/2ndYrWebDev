<?php
include 'connectdb.php';
$conn = openCon();
$gateQuery = "SELECT GateID, Campus, Address FROM gate";
$gateResult = $conn->query($gateQuery);
closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Campus Profile</title>
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
}

nav {
    background: linear-gradient(135deg, #800000 0%, #a00000 100%);
    color: white;
    padding: 20px 30px;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(128, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-title i {
    font-size: 28px;
}

.logout-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 3px 10px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 50;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.content-header {
    background: rgba(255, 255, 255, 0.95);
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.content-header h2 {
    margin: 0;
    color: #800000;
    font-size: 20px;
    font-weight: 600;
}

.content-header p {
    margin: 8px 0 0 0;
    color: #666;
    font-size: 11px;
}

.table-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    background: linear-gradient(135deg, #800000 0%, #a00000 100%);
    color: white;
    padding: 20px;
    text-align: left;
    font-weight: 600;
    font-size: 16px;
    letter-spacing: 0.5px;
}

table td {
    padding: 20px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    font-size: 15px;
    color: #333;
}

table tr {
    transition: all 0.3s ease;
}

table tr:hover {
    background: linear-gradient(135deg, rgba(128, 0, 0, 0.05) 0%, rgba(160, 0, 0, 0.03) 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

table tr:last-child td {
    border-bottom: none;
}

.action-btns {
    display: flex;
    gap: 12px;
    align-items: center;
}

.btn {
    padding: 10px 18px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #479D25;
    color: white;
    box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(128, 0, 0, 0.4);
    background: linear-gradient(135deg, #a00000 0%, #c00000 100%);
}

.btn-delete {
    background: #9D2525;
    color: white;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
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
    color: #800000;
    font-size: 24px;
}

.empty-state p {
    margin: 0;
    font-size: 16px;
}

.campus-icon {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.campus-icon i {
    color: #800000;
    font-size: 18px;
}

.address-text {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #666;
}

.address-text i {
    color: #800000;
    font-size: 16px;
}

@media (max-width: 768px) {
    .main {
        margin-left: 0;
        padding: 15px;
    }
    
    nav {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .action-btns {
        flex-direction: column;
    }
    
    table {
        font-size: 14px;
    }
    
    table th, table td {
        padding: 15px 10px;
    }
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <nav>
        <div class="nav-title">
            <i class="fas fa-university"></i>
            Campus Profile
        </div>
        <a href="adminlogin.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </nav>
    
    <div class="content-header">
        <h2>Campus Management</h2>
        <p>Manage and view all campus locations and their details</p>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-building"></i> Campus</th>
                    <th><i class="fas fa-map-marker-alt"></i> Address</th>
                    <th><i class="fas fa-cogs"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($gateResult->num_rows > 0): ?>
                    <?php while($gate = $gateResult->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="campus-icon">
                                <i class="fas fa-school"></i>
                                <?= htmlspecialchars($gate['Campus']) ?>
                            </div>
                        </td>
                        <td>
                            <div class="address-text">
                                <i class="fas fa-location-dot"></i>
                                <?= htmlspecialchars($gate['Address']) ?>
                            </div>
                        </td>
                        <td class="action-btns">
                            <a href="viewcampus.php?gateid=<?= $gate['GateID'] ?>" class="btn btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            <a href="deletegate.php?gateid=<?= $gate['GateID'] ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('Are you sure you want to delete this gate?')">
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>No Campus Records</h3>
                                <p>No campus records found in the system.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>