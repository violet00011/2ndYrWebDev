    <?php
    session_start(); 
    include('connectdb.php');
    $conn = openCon();

    $guard_id = $_SESSION['guard_id'];

    $guard_query = "SELECT g.FirstName, g.LastName, g.Username, g.GateID, gt.Campus, gt.GateNumber, gt.Address 
                    FROM guard g 
                    LEFT JOIN gate gt ON g.GateID = gt.GateID 
                    WHERE g.GuardID = ?";
    $guard_stmt = $conn->prepare($guard_query);
    $guard_stmt->bind_param("i", $guard_id);
    $guard_stmt->execute();
    $guard_result = $guard_stmt->get_result();
    $guard_info = $guard_result->fetch_assoc();

    $guard_name = $guard_info ? $guard_info['FirstName'] . ' ' . $guard_info['LastName'] : 'Guard';
    $gate_id = $guard_info['GateID'];
    $gate_info = $guard_info['Campus'] . ' - Gate ' . $guard_info['GateNumber'];

    $visitor_query = "SELECT * FROM visitor WHERE Status = 'Pending' AND GateID = ?";
    $visitor_stmt = $conn->prepare($visitor_query);
    $visitor_stmt->bind_param("s", $gate_id);
    $visitor_stmt->execute();
    $visitor_result = $visitor_stmt->get_result();

    $log_query = "SELECT * FROM vehicle_log WHERE GateID = ? ORDER BY TimeIn DESC";
    $log_stmt = $conn->prepare($log_query);
    $log_stmt->bind_param("s", $gate_id);
    $log_stmt->execute();
    $log_result = $log_stmt->get_result();

    $vehicle_logs = [];
    while ($row = $log_result->fetch_assoc()) {
        $vehicle_logs[] = $row;
    }

    $total_logs = count($vehicle_logs);
    $vehicles_inside = 0;
    $today_entries = 0;
    $pending_visitors = 0;

    foreach ($vehicle_logs as $log) {
        if (!empty($log['TimeIn']) && ($log['TimeOut'] == NULL || $log['TimeOut'] == '0000-00-00 00:00:00')) {
            $vehicles_inside++;
        }
        if (!empty($log['TimeIn']) && date('Y-m-d', strtotime($log['TimeIn'])) == date('Y-m-d')) {
            $today_entries++;
        }
    }

    mysqli_data_seek($visitor_result, 0);
    while ($visitor = mysqli_fetch_assoc($visitor_result)) {
        $pending_visitors++;
    }

    mysqli_data_seek($visitor_result, 0);

    $log_query_display = "SELECT * FROM vehicle_log WHERE GateID = ? ORDER BY TimeIn DESC LIMIT 20";
    $log_stmt_display = $conn->prepare($log_query_display);
    $log_stmt_display->bind_param("s", $gate_id);
    $log_stmt_display->execute();
    $log_result_display = $log_stmt_display->get_result();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Guard Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    }

    body {
    background: url("Assets/langit.jpg") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    }

    nav {
    background-color: maroon;
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .nav-left {
    display: flex;
    align-items: center;
    gap: 15px;
    }

    .nav-left img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    }

    .nav-left h1 {
    font-size: 20px;
    font-weight: 600;
    }

    .nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
    }

    nav a {
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s;
    }

    nav a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    }

    .welcome-header {
    background: linear-gradient(135deg, rgba(128, 0, 0, 0.95), rgba(139, 0, 0, 0.9));
    color: white;
    padding: 30px 20px;
    text-align: center;
    backdrop-filter: blur(10px);
    margin-bottom: 30px;
    }

    .welcome-header h1 {
    font-size: 2.5rem;
    font-weight: 300;
    margin-bottom: 10px;
    }

    .welcome-header .guard-name {
    font-size: 2rem;
    font-weight: 600;
    color: #FFD700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    margin-bottom: 15px;
    }

    .gate-assignment {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.1);
    padding: 12px 20px;
    border-radius: 25px;
    font-size: 1.1rem;
    font-weight: 500;
    border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .current-time {
    margin-top: 15px;
    opacity: 0.9;
    font-size: 1.1rem;
    }

    .container {
    max-width: 1400px;
    margin: 0 auto;
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
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 30px;
    }

    .timein-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 20px;
    }

    .content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
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

    .section-content {
    padding: 25px;
    }

    .timein-form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 15px;
    align-items: end;
    }

    .form-group {
    display: flex;
    flex-direction: column;
    }

    .form-group label {
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    }

    .form-group input {
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
    }

    .form-group input:focus {
    outline: none;
    border-color: maroon;
    }

    .timein-btn {
    padding: 12px 25px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: transform 0.3s, box-shadow 0.3s;
    }

    .timein-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
    }

    .table-container {
    max-height: 400px;
    overflow-y: auto;
    }

    .search-box {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 1rem;
    transition: border-color 0.3s;
    }

    .search-box:focus {
    outline: none;
    border-color: maroon;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    }

    th, td {
    padding: 12px;
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

    .action-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
    margin: 2px;
    transition: transform 0.2s;
    font-weight: 500;
    }

    .action-btn:hover {
    transform: scale(1.05);
    }

    .btn-in {
    background: #28a745;
    color: white;
    }

    .btn-out {
    background: #dc3545;
    color: white;
    }

    .btn-disabled {
    background: #6c757d;
    color: white;
    cursor: not-allowed;
    opacity: 0.6;
    }

    .status-badge {
    padding: 4px 8px;
    border-radius: 8px;
    font-weight: bold;
    color: white;
    }

    .status-inside {
    background-color: #C0C024;
    }

    .status-outside {
    background-color: #1D2994;
    }

    .status-pending {
    background-color: #7A7A7A;
    }

    .status-default {
    background-color: #9e9e9e;
    }

    .notification {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffeaa7;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .visitor-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 15px;
    }

    .visitor-info p {
    margin-bottom: 8px;
    }

    .visitor-info strong {
    color: maroon;
    }

    .notification-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    }

    .notification-buttons button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: transform 0.2s, box-shadow 0.2s;
    }

    .notification-buttons button:hover {
    transform: translateY(-2px);
    }

    .approve-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    .deny-btn {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .empty-message {
    text-align: center;
    padding: 40px 20px;
    color: #888;
    }

    .empty-message i {
    font-size: 3rem;
    color: #ddd;
    margin-bottom: 15px;
    }

    @media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .timein-form {
        grid-template-columns: 1fr;
    }
    
    .visitor-info {
        grid-template-columns: 1fr;
    }
    
    .welcome-header h1 {
        font-size: 2rem;
    }
    
    .welcome-header .guard-name {
        font-size: 1.5rem;
    }
    }
    </style>
    </head>
    <body>

    <nav>
    <div class="nav-left">
        <img src="Assets/bulsulogo.png" alt="BulSU Logo">
        <h1><i class="fas fa-shield-alt"></i> BulSU Guard System</h1>
    </div>
    <div class="nav-right">
        <a href="onetimesched.php"><i class="fas fa-calendar-plus"></i> One-Time Visit</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    </nav>

    <div class="welcome-header">
    <h1>Welcome, <span class="guard-name"><?php echo htmlspecialchars($guard_name); ?></span>!</h1>
    <div class="gate-assignment">
        <i class="fas fa-map-marker-alt"></i>
        <span>Assigned to: <?php echo htmlspecialchars($gate_info); ?></span>
    </div>
    <div class="current-time">
        <i class="fas fa-calendar-day"></i> 
        <span id="currentDateTime"><?php echo date('F j, Y - g:i:s A'); ?></span>
    </div>
    </div>

    <div class="container">
    
    <div class="stats-grid">
        <div class="stat-card">
        <div class="icon"><i class="fas fa-calendar-check"></i></div>
        <div class="number"><?php echo $today_entries; ?></div>
        <div class="label">Today's Entries</div>
        </div>
        <div class="stat-card">
        <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        <div class="number"><?php echo $total_logs; ?></div>
        <div class="label">Total Gate Logs</div>
        </div>
        <div class="stat-card">
        <div class="icon"><i class="fas fa-user-clock"></i></div>
        <div class="number"><?php echo $pending_visitors; ?></div>
        <div class="label">Pending Visitors</div>
        </div>
    </div>

    <div class="timein-section">
        <div class="section-header">
        <h2><i class="fas fa-car-side"></i> Manual Vehicle Time-In</h2>
        </div>
        <div class="section-content">
        <form method="POST" action="recordtimein.php" class="timein-form">
            <div class="form-group">
            <label for="plate_number">Vehicle Plate Number</label>
            <input type="text" id="plate_number" name="plate_number" placeholder="Enter Plate Number" required />
            </div>
            <div class="form-group">
            <label>Gate Assignment</label>
            <input type="text" value="<?php echo htmlspecialchars($gate_info); ?>" disabled />
            </div>
            <input type="hidden" name="gate_id" value="<?php echo htmlspecialchars($gate_id); ?>" />
            <input type="hidden" name="guard_id" value="<?php echo htmlspecialchars($guard_id); ?>" />
            <button type="submit" name="timein_button" class="timein-btn">
            <i class="fas fa-sign-in-alt"></i> Time In
            </button>
        </form>
        </div>
    </div>

    <div class="content-grid">
        
    <!-- Vehicle Logs -->
    <div class="section-card">
    <div class="section-header">
        <h2><i class="fas fa-history"></i> Vehicle Logs</h2>
    </div>
    <div class="section-content">
        <input type="text" id="searchInput" class="search-box" placeholder="Search Vehicle Records..." />
        <div class="table-container">
        <table id="logsTable">
            <thead>
            <tr>
                <th>Log ID</th>
                <th>Vehicle ID</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="logsBody">
            <?php while($log = mysqli_fetch_assoc($log_result_display)): ?>
                <tr>
                <td><?php echo htmlspecialchars($log['LogID']); ?></td>
                <td><?php echo htmlspecialchars($log['VehicleID']); ?></td>
                <td>
                    <?php 
                    $timein_timestamp = strtotime($log['TimeIn']);
                    echo ($timein_timestamp && $timein_timestamp > 0) ? date('M j, g:i A', $timein_timestamp) : '---';
                    ?>
                </td>
                <td>
                    <?php 
                    $timeout_timestamp = strtotime($log['TimeOut']);
                    echo ($timeout_timestamp && $timeout_timestamp > 0) ? date('M j, g:i A', $timeout_timestamp) : '---';
                    ?>
                </td>
                <td>
                    <?php 
                    $status = htmlspecialchars($log['Status']);
                    $statusClass = 'status-default';

                    if (strtolower($status) === 'inside') {
                        $statusClass = 'status-inside';
                    } elseif (strtolower($status) === 'outside') {
                        $statusClass = 'status-outside';
                    } elseif (strtolower($status) === 'pending') {
                        $statusClass = 'status-pending';
                    }
                    ?>
                    <span class="status-badge <?php echo $statusClass; ?>">
                    <?php echo $status; ?>
                    </span>
                </td>
                <td>
                    
                    <form method="POST" action="update_log.php" style="display:inline;">
                    <input type="hidden" name="log_id" value="<?php echo $log['LogID']; ?>">
                    <input type="hidden" name="action" value="in">
                    <button type="submit" class="action-btn btn-in">
                        <i class="fas fa-sign-in-alt"></i> In
                    </button>
                    </form>
                    <form method="POST" action="update_log.php" style="display:inline;">
                    <input type="hidden" name="log_id" value="<?php echo $log['LogID']; ?>">
                    <input type="hidden" name="action" value="out">
                    <button type="submit" class="action-btn btn-out">
                        <i class="fas fa-sign-out-alt"></i> Out
                    </button>
                    </form>
                </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
    </div>



        <div class="section-card">
        <div class="section-header">
            <h2><i class="fas fa-user-friends"></i> Visitor Requests</h2>
        </div>
        <div class="section-content">
            <?php if (mysqli_num_rows($visitor_result) > 0): ?>
            <?php while($visitor = mysqli_fetch_assoc($visitor_result)): ?>
                <div class="notification">
                <div class="visitor-info">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($visitor['FirstName'] . ' ' . $visitor['LastName']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($visitor['ContactNumber']); ?></p>
                    <p><strong>Vehicle:</strong> <?php echo htmlspecialchars($visitor['VehicleModel']); ?></p>
                    <p><strong>Plate:</strong> <?php echo htmlspecialchars($visitor['PlateNumber']); ?></p>
                    <p><strong>Visit Date:</strong> <?php echo date('M j, Y g:i A', strtotime($visitor['ScheduledVisit'])); ?></p>
                    <p><strong>Purpose:</strong> <?php echo htmlspecialchars($visitor['Purpose']); ?></p>
                </div>
                
                <div class="notification-buttons">
                    <button type="button" class="approve-btn" data-visitor-id="<?php echo $visitor['VisitorID']; ?>" data-guard-id="<?php echo $guard_id; ?>">
                    <i class="fas fa-check"></i> Approve
                    </button>
                    <button type="button" class="deny-btn" data-visitor-id="<?php echo $visitor['VisitorID']; ?>">
                    <i class="fas fa-times"></i> Deny
                    </button>
                </div>
                </div>
            <?php endwhile; ?>
            <?php else: ?>
            <div class="empty-message">
                <i class="fas fa-user-friends"></i>
                <h3>No Pending Requests</h3>
                <p>All visitor requests have been processed.</p>
            </div>
            <?php endif; ?>
        </div>
        </div>

    </div>

    </div>

    <script>
    
    function updateCurrentTime() {
        const now = new Date();
        const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: 'numeric', 
        minute: '2-digit', 
        second: '2-digit',
        hour12: true 
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
    }

    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);

    document.getElementById('searchInput').addEventListener('input', function () {
        var input = this.value.toLowerCase();
        var rows = document.querySelectorAll('#logsTable tbody tr');
        rows.forEach(function(row) {
        var cells = row.querySelectorAll('td');
        var match = false;
        cells.forEach(function(cell) {
            if (cell.textContent.toLowerCase().includes(input)) {
            match = true;
            }
        });
        row.style.display = match ? '' : 'none';
        });
    });

    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', function () {
        const visitorId = this.dataset.visitorId;
        const guardId = this.dataset.guardId;
        fetch('approve_visit.php', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `visitor_id=${visitorId}&guard_id=${guardId}`
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === 'success') {
            alert("Visit approved successfully!");
            location.reload();
            } else {
            alert("Failed to approve visit. Please try again.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred. Please try again.");
        });
        });
    });

    document.querySelectorAll('.deny-btn').forEach(button => {
        button.addEventListener('click', function () {
        const visitorId = this.dataset.visitorId;
        fetch('deny_visit.php', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `visitor_id=${visitorId}`
        })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === 'success') {
            alert("Visit request denied.");
            location.reload();
            } else {
            alert("Failed to deny visit. Please try again.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred. Please try again.");
        });
        });
    });
    </script>

    </body>
    </html>