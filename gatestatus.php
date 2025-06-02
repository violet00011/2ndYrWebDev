<?php
include 'connectdb.php';
$conn = openCon();

$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
}

$gates = [
    'Malolos_1', 'Malolos_2', 'Malolos_3', 'Malolos_4',
    'Hagonoy_1', 'Meneses_1', 'Bustos_1',
    'SanRafael_1', 'Sarmiento_1'
];

$gateLogs = [];
foreach ($gates as $gate) {
    $gateLogs[$gate] = [];
}

$sql = "SELECT * FROM vehicle_log";
if (!empty($searchTerm)) {
    $searchTermEscaped = $conn->real_escape_string($searchTerm);
    $sql .= " WHERE VehicleID LIKE '%$searchTermEscaped%' OR LogID LIKE '%$searchTermEscaped%'";
}
$sql .= " ORDER BY TimeIn DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $gateID = $row['GateID'];
    if (isset($gateLogs[$gateID])) {
        $gateLogs[$gateID][] = $row;
    }
}

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Status</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: row;
            height: 100vh;
            overflow: hidden;
        }

        .content {
            flex: 1;
            margin-left: 250px;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .content::before {
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
            background: linear-gradient(135deg, maroon, #a52a2a);
            color: white;
            padding: 20px 30px;
            margin: 20px 20px 0 20px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(128, 0, 0, 0.3);
            text-align: center;
            flex-shrink: 0;
        }

        .header h2 {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .header-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            font-size: 14px;
            opacity: 0.9;
        }

        .header-stat {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .search-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            margin: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            flex-shrink: 0;
        }

        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border-radius: 25px;
            border: 2px solid #ddd;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: maroon;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
        }

        .search-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(135deg, maroon, #a52a2a);
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(128, 0, 0, 0.4);
        }

        .gates-container {
            flex: 1;
            overflow: hidden;
            padding: 0 20px 20px 20px;
            min-height: 0; 
        }

        .scroll-container {
            width: 100%;
            height: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 15px;
            cursor: grab;
        }

        .scroll-container:active {
            cursor: grabbing;
        }

        .horizontal-scroll {
            display: flex;
            gap: 20px;
            height: calc(100% - 15px);
            min-width: fit-content;
            padding-right: 20px;
        }

        .gate-column {
            width: 350px;
            min-width: 350px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            flex-shrink: 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .gate-header {
            background: linear-gradient(135deg, maroon, #a52a2a);
            color: white;
            padding: 15px 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .gate-header h3 {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .gate-status {
            font-size: 12px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4CAF50;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .gate-content {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            min-height: 0;
        }

        .log-entry {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .log-entry::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, maroon, #a52a2a);
        }

        .log-entry:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: maroon;
        }

        .log-entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }

        .log-id {
            background: linear-gradient(135deg, maroon, #a52a2a);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .log-time {
            color: #666;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .log-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .log-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #555;
        }

        .log-detail i {
            color: maroon;
            width: 16px;
            text-align: center;
        }

        .log-detail strong {
            color: #333;
            min-width: 60px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .no-logs {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .no-logs i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        .no-logs p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .scroll-container::-webkit-scrollbar {
            height: 12px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin: 0 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, maroon, #a52a2a);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #8B0000, #DC143C);
            background-clip: content-box;
        }

        .gate-content::-webkit-scrollbar {
            width: 6px;
        }

        .gate-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .gate-content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, maroon, #a52a2a);
            border-radius: 10px;
        }

        .scroll-indicator {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(128, 0, 0, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-50%) translateY(10px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            
            .content {
                margin-left: 200px;
            }
            
            .gate-column {
                width: 300px;
                min-width: 300px;
            }

            .header h2 {
                font-size: 20px;
            }

            .header-stats {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>

<div class="content">
    <div class="header">
        <h2>
            <i class="fas fa-shield-alt"></i>
            Gate Status – Vehicle Logs by Gate
        </h2>
        <div class="header-stats">
            <div class="header-stat">
                <i class="fas fa-door-open"></i>
                <span><?= count($gates) ?> Gates Active</span>
            </div>
            <div class="header-stat">
                <i class="fas fa-car"></i>
                <span><?= $result->num_rows ?> Total Logs</span>
            </div>
            <div class="header-stat">
                <i class="fas fa-clock"></i>
                <span>Real-time Monitoring</span>
            </div>
        </div>
    </div>

    <div class="search-container">
        <form method="get" action="" class="search-form">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search by Log ID or Vehicle ID..." value="<?= htmlspecialchars($searchTerm) ?>">
            </div>
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
                Search
            </button>
            <?php if (!empty($searchTerm)): ?>
                <a href="?" class="search-btn" style="background: #6c757d; text-decoration: none;">
                    <i class="fas fa-times"></i>
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="gates-container">
        <div class="scroll-container" id="horizontalScroll">
            <div class="horizontal-scroll">
                <?php foreach ($gates as $gate): ?>
                    <div class="gate-column">
                        <div class="gate-header">
                            <h3>
                                <i class="fas fa-door-open"></i>
                                <?= htmlspecialchars(str_replace('_', ' Gate ', $gate)) ?>
                            </h3>
                            <div class="gate-status">
                                <div class="status-indicator"></div>
                                <span><?= count($gateLogs[$gate]) ?> Logs | Online</span>
                            </div>
                        </div>
                        <div class="gate-content">
                            <?php if (!empty($gateLogs[$gate])): ?>
                                <?php foreach ($gateLogs[$gate] as $log): ?>
                                    <div class="log-entry">
                                        <div class="log-entry-header">
                                            <div class="log-id">
                                                <i class="fas fa-barcode"></i>
                                                <?= htmlspecialchars($log['LogID']) ?>
                                            </div>
                                            <div class="log-time">
                                                <i class="fas fa-clock"></i>
                                                <?= date('H:i', strtotime($log['TimeIn'])) ?>
                                            </div>
                                        </div>
                                        <div class="log-details">
                                            <div class="log-detail">
                                                <i class="fas fa-car"></i>
                                                <strong>Vehicle:</strong>
                                                <span><?= htmlspecialchars($log['VehicleID']) ?></span>
                                            </div>
                                            <div class="log-detail">
                                                <i class="fas fa-user-shield"></i>
                                                <strong>Guard:</strong>
                                                <span><?= htmlspecialchars($log['GuardID']) ?></span>
                                            </div>
                                            <div class="log-detail">
                                                <i class="fas fa-sign-in-alt"></i>
                                                <strong>In:</strong>
                                                <span><?= date('M j, H:i', strtotime($log['TimeIn'])) ?></span>
                                            </div>
                                            <div class="log-detail">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <strong>Out:</strong>
                                                <span><?= $log['TimeOut'] ? date('M j, H:i', strtotime($log['TimeOut'])) : 'Pending' ?></span>
                                            </div>
                                        </div>
                                        <div style="margin-top: 10px; text-align: center;">
                                            <span class="status-badge <?= $log['Status'] == 'Active' ? 'status-active' : 'status-inactive' ?>">
                                                <i class="fas fa-<?= $log['Status'] == 'Active' ? 'check-circle' : 'times-circle' ?>"></i>
                                                <?= htmlspecialchars($log['Status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-logs">
                                    <i class="fas fa-inbox"></i>
                                    <p><strong>No logs found</strong></p>
                                    <p>This gate has no recent activity</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="scroll-indicator" id="scrollIndicator">
    <i class="fas fa-arrows-alt-h"></i>
    Scroll horizontally to view more gates
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.getElementById('horizontalScroll');
    const scrollIndicator = document.getElementById('scrollIndicator');

    setTimeout(() => {
        if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
            scrollIndicator.style.display = 'block';
            setTimeout(() => {
                scrollIndicator.style.display = 'none';
            }, 3000);
        }
    }, 500);

    setInterval(function() {
        if (!document.querySelector('input[name="search"]').value) {
            location.reload();
        }
    }, 60000);

    let isDown = false;
    let startX;
    let scrollLeft;
    let velocity = 0;
    let rafId = null;

    scrollContainer.addEventListener('mousedown', (e) => {
        isDown = true;
        scrollContainer.classList.add('active');
        startX = e.pageX - scrollContainer.offsetLeft;
        scrollLeft = scrollContainer.scrollLeft;
        velocity = 0;
        if (rafId) {
            cancelAnimationFrame(rafId);
        }
    });

    scrollContainer.addEventListener('mouseleave', () => {
        isDown = false;
        scrollContainer.classList.remove('active');
        applyMomentum();
    });

    scrollContainer.addEventListener('mouseup', () => {
        isDown = false;
        scrollContainer.classList.remove('active');
        applyMomentum();
    });

    scrollContainer.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - scrollContainer.offsetLeft;
        const walk = (x - startX) * 2;
        const newScrollLeft = scrollLeft - walk;
        velocity = newScrollLeft - scrollContainer.scrollLeft;
        scrollContainer.scrollLeft = newScrollLeft;
    });

    function applyMomentum() {
        if (Math.abs(velocity) > 0.5) {
            scrollContainer.scrollLeft += velocity;
            velocity *= 0.95; // Friction
            rafId = requestAnimationFrame(applyMomentum);
        }
    }

    let touchStartX = 0;
    let touchScrollLeft = 0;

    scrollContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        touchScrollLeft = scrollContainer.scrollLeft;
    });

    scrollContainer.addEventListener('touchmove', (e) => {
        if (!touchStartX) return;
        const touchX = e.touches[0].clientX;
        const diff = touchStartX - touchX;
        scrollContainer.scrollLeft = touchScrollLeft + diff;
    });

    scrollContainer.addEventListener('touchend', () => {
        touchStartX = 0;
    });

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName.toLowerCase() === 'input') return;
        
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                scrollContainer.scrollLeft -= 200;
                break;
            case 'ArrowRight':
                e.preventDefault();
                scrollContainer.scrollLeft += 200;
                break;
            case 'Home':
                e.preventDefault();
                scrollContainer.scrollLeft = 0;
                break;
            case 'End':
                e.preventDefault();
                scrollContainer.scrollLeft = scrollContainer.scrollWidth;
                break;
        }
    });

    scrollContainer.addEventListener('wheel', (e) => {
        if (e.deltaY !== 0) {
            e.preventDefault();
            scrollContainer.scrollLeft += e.deltaY;
        }
    });
});
</script>

</body>
</html>