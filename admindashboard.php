<?php   
session_start();
include 'connectdb.php';

$conn = openCon();

if (!isset($_SESSION['staff_id']) && isset($_SESSION['StaffID'])) {
    $_SESSION['staff_id'] = $_SESSION['StaffID'];
}

if (isset($_SESSION['staff_id'])) {
    $staff_id = $_SESSION['staff_id'];
    $query = "SELECT * FROM staff WHERE StaffID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
} else {
    header("Location: login.php");
    exit();
}

$stats = [];

$vehicleQuery = "SELECT COUNT(*) as total FROM vehicle";
$vehicleResult = $conn->query($vehicleQuery);
$stats['totalVehicles'] = $vehicleResult->fetch_assoc()['total'];

$guardsQuery = "SELECT COUNT(*) as total FROM guard";
$guardsResult = $conn->query($guardsQuery);
$stats['totalGuards'] = $guardsResult->fetch_assoc()['total'];

$guardsOnDutyQuery = "SELECT COUNT(*) as total FROM guard WHERE ShiftDays != '' AND ShiftDays IS NOT NULL";
$guardsOnDutyResult = $conn->query($guardsOnDutyQuery);
$stats['guardsOnDuty'] = $guardsOnDutyResult->fetch_assoc()['total'];

$visitorsQuery = "SELECT COUNT(*) as total FROM visitor";
$visitorsResult = $conn->query($visitorsQuery);
$stats['totalVisitors'] = $visitorsResult->fetch_assoc()['total'];

$gatesQuery = "SELECT COUNT(*) as total FROM gate";
$gatesResult = $conn->query($gatesQuery);
$stats['totalGates'] = $gatesResult->fetch_assoc()['total'];

$vehicleOwnersQuery = "SELECT COUNT(*) as total FROM vehicle_owner";
$vehicleOwnersResult = $conn->query($vehicleOwnersQuery);
$stats['totalVehicleOwners'] = $vehicleOwnersResult->fetch_assoc()['total'];

$vehicleTrendQuery = "
    SELECT DATE_FORMAT(DateRegistered, '%Y-%m-%d') as day, 
           DATE_FORMAT(DateRegistered, '%b %d') as display_day,
           COUNT(*) as count
    FROM vehicle
    WHERE DateRegistered >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    AND DateRegistered IS NOT NULL
    GROUP BY DATE_FORMAT(DateRegistered, '%Y-%m-%d')
    ORDER BY day ASC
    LIMIT 7";

$vehicleTrendResult = $conn->query($vehicleTrendQuery);
$vehicleTrend = [];
while ($row = $vehicleTrendResult->fetch_assoc()) {
    $vehicleTrend[] = $row;
}

echo "<!-- Vehicle Trend Debug: " . json_encode($vehicleTrend) . " -->";

$vehicleTypesQuery = "
    SELECT Type, COUNT(*) as count
    FROM vehicle
    GROUP BY Type
    ORDER BY count DESC";
$vehicleTypesResult = $conn->query($vehicleTypesQuery);
$vehicleTypes = [];
while ($row = $vehicleTypesResult->fetch_assoc()) {
    $vehicleTypes[] = $row;
}

$vehicleStatusQuery = "
    SELECT Status, COUNT(*) as count
    FROM vehicle
    GROUP BY Status";
$vehicleStatusResult = $conn->query($vehicleStatusQuery);
$vehicleStatus = [];
while ($row = $vehicleStatusResult->fetch_assoc()) {
    $vehicleStatus[] = $row;
}

$gateActivityQuery = "
    SELECT g.Campus, g.GateNumber, COUNT(vl.LogID) as activity_count
    FROM gate g
    LEFT JOIN vehicle_log vl ON g.GateID = vl.GateID
    GROUP BY g.GateID
    ORDER BY activity_count DESC";
$gateActivityResult = $conn->query($gateActivityQuery);
$gateActivity = [];
while ($row = $gateActivityResult->fetch_assoc()) {
    $gateActivity[] = $row;
}

$recentLogsQuery = "
    SELECT vl.TimeIn, vl.TimeOut, v.PlateNumber, v.Type, g.Campus, g.GateNumber
    FROM vehicle_log vl
    LEFT JOIN vehicle v ON vl.VehicleID = v.VehicleID
    LEFT JOIN gate g ON vl.GateID = g.GateID
    ORDER BY vl.TimeIn DESC
    LIMIT 10";
$recentLogsResult = $conn->query($recentLogsQuery);
$recentLogs = [];
while ($row = $recentLogsResult->fetch_assoc()) {
    $recentLogs[] = $row;
}

$visitorTrendQuery = "
    SELECT DATE_FORMAT(ScheduledVisit, '%Y-%m-%d') as day, COUNT(*) as count
    FROM visitor
    WHERE ScheduledVisit >= CURDATE() - INTERVAL 6 DAY
    GROUP BY day
    ORDER BY day ASC";
$visitorTrendResult = $conn->query($visitorTrendQuery);
$visitorTrend = [];
while ($row = $visitorTrendResult->fetch_assoc()) {
    $visitorTrend[] = $row;
}

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate System - Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
        }

        .sidebar {
            width: 220px;
            background-color: maroon;
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-header h2 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .sidebar-nav {
            padding: 10px 0;
        }

        .nav-item {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }

        .nav-item:hover {
            background-color: #5c0000;
            border-left-color: #ff6b6b;
        }

        .nav-item i {
            width: 20px;
            margin-right: 15px;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        .header {
            background-color: maroon;
            color: white;
            padding: 15px;
            font-size: 18px;
            margin-bottom: 20px;
            border-radius: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: none;
        }

        .header h1 {
            color: white;
            font-size: 18px;
            font-weight: normal;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            color: white;
            font-weight: bold;
        }

        .logout-btn {
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 0;
            background: none;
            border: none;
        }

        .logout-btn:hover {
            text-decoration: underline;
            transform: none;
            box-shadow: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); 
            gap: 25px;
            margin-bottom: 40px;
            justify-content: center; 
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width:400px;
            height:120px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: maroon;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.vehicles { background: #1D8F52; }
        .stat-icon.guards { background: #22277C; }
        .stat-icon.visitors { background: #BC4E5E; }
        .stat-icon.gates { background: #124A1B; }
        .stat-icon.owners { background: #AC1A1A; }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .chart-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .table-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        th {
            background-color: maroon;
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #666;
            font-size: 14px;
        }

        tr:hover {
            background: rgba(128, 0, 0, 0.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(67, 233, 123, 0.2);
            color: #43e97b;
        }

        .status-inactive {
            background: rgba(255, 107, 107, 0.2);
            color: #ff6b6b;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Visitors</h1>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($staff['Username']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['totalVehicles']); ?></div>
                        <div class="stat-label">Total Vehicles</div>
                    </div>
                    <div class="stat-icon vehicles">
                        <i class="fas fa-car"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['totalGuards']); ?></div>
                        <div class="stat-label">Total Guards</div>
                    </div>
                    <div class="stat-icon guards">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['guardsOnDuty']); ?></div>
                        <div class="stat-label">Guards On Duty</div>
                    </div>
                    <div class="stat-icon guards">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['totalVisitors']); ?></div>
                        <div class="stat-label">Total Visitors</div>
                    </div>
                    <div class="stat-icon visitors">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['totalGates']); ?></div>
                        <div class="stat-label">Total Gates</div>
                    </div>
                    <div class="stat-icon gates">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo number_format($stats['totalVehicleOwners']); ?></div>
                        <div class="stat-label">Vehicle Owners</div>
                    </div>
                    <div class="stat-icon owners">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    Vehicle Registration Trend
                </div>
                <div class="chart-container">
                    <canvas id="vehicleTrendChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Vehicle Types Distribution
                </div>
                <div class="chart-container">
                    <canvas id="vehicleTypesChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-bar"></i>
                    Gate Activity
                </div>
                <div class="chart-container">
                    <canvas id="gateActivityChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-area"></i>
                    Visitor Trends
                </div>
                <div class="chart-container">
                    <canvas id="visitorTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h3><i class="fas fa-clock"></i> Recent Vehicle Activity</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Plate Number</th>
                            <th>Vehicle Type</th>
                            <th>Gate Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td><?php echo date('M d, Y H:i', strtotime($log['TimeIn'])); ?></td>
                            <td><?php echo $log['TimeOut'] ? date('M d, Y H:i', strtotime($log['TimeOut'])) : '-'; ?></td>
                            <td><strong><?php echo htmlspecialchars($log['PlateNumber']); ?></strong></td>
                            <td><?php echo htmlspecialchars($log['Type']); ?></td>
                            <td><?php echo htmlspecialchars($log['Campus']) . ' - Gate ' . htmlspecialchars($log['GateNumber']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $log['TimeOut'] ? 'status-inactive' : 'status-active'; ?>">
                                    <?php echo $log['TimeOut'] ? 'Exited' : 'Inside'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        Chart.defaults.font.family = "'Poppins', sans-serif";
        Chart.defaults.color = '#666';

        const vehicleTrendCtx = document.getElementById('vehicleTrendChart').getContext('2d');
        new Chart(vehicleTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($vehicleTrend, 'day')); ?>,
                datasets: [{
                    label: 'Vehicles Registered',
                    data: <?php echo json_encode(array_column($vehicleTrend, 'count')); ?>,
                    borderColor: 'maroon',
                    backgroundColor: 'rgba(128, 0, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'maroon',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { precision: 0 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        const vehicleTypesCtx = document.getElementById('vehicleTypesChart').getContext('2d');
        new Chart(vehicleTypesCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($vehicleTypes, 'Type')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($vehicleTypes, 'count')); ?>,
                    backgroundColor: [
                        '#B73327',
                        '#2757B7',
                        '#79B727',
                        '#B79827',
                        '#27B783',
                        '#6327B7',
                        '#B727B7',
                        '#B7273F'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        const gateActivityCtx = document.getElementById('gateActivityChart').getContext('2d');
        new Chart(gateActivityCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_map(function($gate) { 
                    return $gate['Campus'] . ' - Gate ' . $gate['GateNumber']; 
                }, $gateActivity)); ?>,
                datasets: [{
                    label: 'Activity Count',
                    data: <?php echo json_encode(array_column($gateActivity, 'activity_count')); ?>,
                    backgroundColor: 'rgba(128, 0, 0, 0.8)',
                    borderColor: 'maroon',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { precision: 0 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            maxRotation: 45
                        }
                    }
                }
            }
        });

        const visitorTrendCtx = document.getElementById('visitorTrendChart').getContext('2d');
        new Chart(visitorTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($visitorTrend, 'day')); ?>,
                datasets: [{
                    label: 'Scheduled Visitors',
                    data: <?php echo json_encode(array_column($visitorTrend, 'count')); ?>,
                    borderColor: 'maroon',
                    backgroundColor: 'rgba(128, 0, 0, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'maroon',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { precision: 0 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>