<?php
include 'connectdb.php';
$conn = openCon();
$staffQuery = "SELECT * FROM staff";
$staffResult = $conn->query($staffQuery);
closeCon($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Staff</title>
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

table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
}

table th, table td {
    padding: 18px 20px;
    text-align: left;
    border-bottom: 1px solid rgba(128, 0, 0, 0.1);
    font-size: 14px;
}

table th {
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 12px;
}

table tbody tr {
    transition: all 0.3s ease;
}

table tbody tr:hover {
    background: linear-gradient(135deg, rgba(128, 0, 0, 0.05), rgba(128, 0, 0, 0.02));
    transform: scale(1.002);
}

table tbody tr:nth-child(even) {
    background: rgba(248, 249, 250, 0.5);
}

.action-btns {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-btns a {
    padding: 8px 16px;
    background: linear-gradient(135deg, maroon, #8B0000);
    color: white;
    border-radius: 20px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.action-btns a:hover {
    background: linear-gradient(135deg, #8B0000, #660000);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3);
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

.add-staff-container {
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.add-staff-btn {
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

.add-staff-btn:hover {
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
    
    table {
        font-size: 12px;
    }
    
    table th, table td {
        padding: 12px 8px;
    }
    
    .action-btns {
        flex-direction: column;
    }
    
    .action-btns a {
        font-size: 11px;
        padding: 6px 12px;
    }
}

/* Scrollbar Styling */
.table-container::-webkit-scrollbar {
    height: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: rgba(128, 0, 0, 0.1);
    border-radius: 10px;
}

.table-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, maroon, #8B0000);
    border-radius: 10px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #8B0000, #660000);
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main">
    <div class="header-card">
        <div class="header-title">
            <i class="fas fa-users"></i>
            Manage Staff
        </div>
        <a href="adminlogin.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>

    <div class="table-container">
        <div class="table-header">
            <i class="fas fa-table"></i>
            Staff Directory
        </div>
        
        <?php if ($staffResult->num_rows > 0): ?>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Last Name</th>
                        <th><i class="fas fa-user"></i> Middle Name</th>
                        <th><i class="fas fa-user"></i> First Name</th>
                        <th><i class="fas fa-briefcase"></i> Position</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-user-circle"></i> Username</th>
                        <th><i class="fas fa-lock"></i> Password</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($staff = $staffResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($staff['LastName']) ?></td>
                        <td><?= htmlspecialchars($staff['MiddleName']) ?></td>
                        <td><?= htmlspecialchars($staff['FirstName']) ?></td>
                        <td><?= htmlspecialchars($staff['Position']) ?></td>
                        <td><?= htmlspecialchars($staff['Email']) ?></td>
                        <td><?= htmlspecialchars($staff['Username']) ?></td>
                        <td><?= str_repeat('•', min(8, strlen($staff['Password']))) ?></td>
                        <td class="action-btns">
                            <a href="viewstaff.php?staffid=<?= $staff['StaffID'] ?>" class="view-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="editstaff.php?staffid=<?= $staff['StaffID'] ?>">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="deletestaff.php?staffid=<?= $staff['StaffID'] ?>" 
                               class="delete-btn"
                               onclick="return confirm('Are you sure you want to delete this staff member?')">
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
            <i class="fas fa-users-slash"></i>
            <h3>No Staff Members Found</h3>
            <p>Get started by adding your first staff member.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="add-staff-container">
        <a href="addstaff.php" class="add-staff-btn">
            <i class="fas fa-plus"></i>
            Add New Staff Member
        </a>
    </div>
</div>
</body>
</html>