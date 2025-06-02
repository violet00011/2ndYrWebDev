<?php
include 'connectdb.php'; 
require('fpdf/fpdf.php');

// Handle exports first before any HTML output
if (isset($_GET['export_all']) || isset($_GET['export_today']) || isset($_GET['export_week'])) {
    $conn = openCon();
    
    if (isset($_GET['export_all'])) {
        $query = "SELECT * FROM vehicle_log ORDER BY TimeIn DESC";
        $result = $conn->query($query);
        exportToPDF($conn, $result, 'all_vehicle_logs.pdf', 'All Vehicle Log Records');
    }
    
    if (isset($_GET['export_today'])) {
        $dateToday = date("Y-m-d");
        $query = "SELECT * FROM vehicle_log WHERE DATE(TimeIn) = '$dateToday' ORDER BY TimeIn DESC";
        $result = $conn->query($query);
        $filename = $dateToday . '_vehicle_logs.pdf';
        exportToPDF($conn, $result, $filename, 'Daily Vehicle Log Records - ' . date("F j, Y"));
    }
    
    if (isset($_GET['export_week'])) {
        $weekStart = date("Y-m-d", strtotime("monday this week"));
        $weekEnd = date("Y-m-d", strtotime("sunday this week"));
        $query = "SELECT * FROM vehicle_log WHERE DATE(TimeIn) BETWEEN '$weekStart' AND '$weekEnd' ORDER BY TimeIn DESC";
        $result = $conn->query($query);
        $filename = 'week_' . $weekStart . '_to_' . $weekEnd . '_vehicle_logs.pdf';
        exportToPDF($conn, $result, $filename, 'Weekly Vehicle Log Records - ' . date("F j", strtotime($weekStart)) . ' to ' . date("F j, Y", strtotime($weekEnd)));
    }
    
    closeCon($conn);
}

// Now get data for display
$conn = openCon();
$vehicleLogQuery = "SELECT * FROM vehicle_log ORDER BY TimeIn DESC";
$vehicleLogResult = $conn->query($vehicleLogQuery);
closeCon($conn);

function exportToPDF($conn, $result, $filename, $title) {
    if (!$result || $result->num_rows == 0) {
        echo "<script>alert('No records found to export.'); window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
        return;
    }
    
    $pdf = new FPDF('L', 'mm', 'A4'); // Landscape orientation for better table fit
    $pdf->AddPage();
    
    // Header
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(277, 15, $title, 0, 1, 'C');
    
    // Export information
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(277, 8, 'Exported on: ' . date('F j, Y g:i A'), 0, 1, 'C');
    $pdf->Cell(277, 8, 'Exported by: Administrator', 0, 1, 'C'); // You can modify this to get actual user
    $pdf->Ln(5);
    
    // Table headers with adjusted widths for landscape
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(128, 0, 0); // Maroon background
    $pdf->SetTextColor(255, 255, 255); // White text
    
    $pdf->Cell(25, 10, 'Log ID', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Vehicle ID', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Gate ID', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Guard ID', 1, 0, 'C', true);
    $pdf->Cell(45, 10, 'Time In', 1, 0, 'C', true);
    $pdf->Cell(45, 10, 'Time Out', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Status', 1, 0, 'C', true);
    $pdf->Cell(47, 10, 'Duration', 1, 1, 'C', true);
    
    // Reset text color for data
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 8);
    
    $rowCount = 0;
    while ($row = $result->fetch_assoc()) {
        // Alternate row colors
        if ($rowCount % 2 == 0) {
            $pdf->SetFillColor(248, 249, 250);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        
        // Calculate duration if both TimeIn and TimeOut exist
        $duration = '';
        if (!empty($row['TimeIn']) && !empty($row['TimeOut']) && $row['TimeOut'] != '0000-00-00 00:00:00') {
            $timeIn = new DateTime($row['TimeIn']);
            $timeOut = new DateTime($row['TimeOut']);
            $interval = $timeIn->diff($timeOut);
            $duration = $interval->format('%h:%i:%s');
        }
        
        // Handle NULL or empty TimeOut
        $timeOut = (!empty($row['TimeOut']) && $row['TimeOut'] != '0000-00-00 00:00:00') ? 
                   date('M j, Y g:i A', strtotime($row['TimeOut'])) : 'Still Inside';
        
        $pdf->Cell(25, 8, $row['LogID'], 1, 0, 'C', true);
        $pdf->Cell(35, 8, $row['VehicleID'], 1, 0, 'C', true);
        $pdf->Cell(25, 8, $row['GateID'], 1, 0, 'C', true);
        $pdf->Cell(30, 8, $row['GuardID'], 1, 0, 'C', true);
        $pdf->Cell(45, 8, date('M j, Y g:i A', strtotime($row['TimeIn'])), 1, 0, 'C', true);
        $pdf->Cell(45, 8, $timeOut, 1, 0, 'C', true);
        $pdf->Cell(25, 8, $row['Status'], 1, 0, 'C', true);
        $pdf->Cell(47, 8, $duration, 1, 1, 'C', true);
        
        $rowCount++;
        
        // Add new page if needed
        if ($pdf->GetY() > 180) {
            $pdf->AddPage();
            
            // Repeat headers on new page
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(128, 0, 0);
            $pdf->SetTextColor(255, 255, 255);
            
            $pdf->Cell(25, 10, 'Log ID', 1, 0, 'C', true);
            $pdf->Cell(35, 10, 'Vehicle ID', 1, 0, 'C', true);
            $pdf->Cell(25, 10, 'Gate ID', 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'Guard ID', 1, 0, 'C', true);
            $pdf->Cell(45, 10, 'Time In', 1, 0, 'C', true);
            $pdf->Cell(45, 10, 'Time Out', 1, 0, 'C', true);
            $pdf->Cell(25, 10, 'Status', 1, 0, 'C', true);
            $pdf->Cell(47, 10, 'Duration', 1, 1, 'C', true);
            
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 8);
        }
    }
    
    // Footer with total records
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(277, 10, 'Total Records: ' . $rowCount, 0, 1, 'C');
    
    $pdf->Output('D', $filename); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Vehicle Log</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      background-color: #f8fafc;
    }

    .main {
      margin-left: 220px;
      padding: 24px;
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
      backdrop-filter: blur(2px);
      z-index: -1;
    }

    nav {
      background: linear-gradient(135deg, #800000 0%, #a00000 100%);
      color: white;
      padding: 20px 24px;
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(128, 0, 0, 0.2);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .nav-title {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .nav-actions {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      background: linear-gradient(135deg, #800000 0%, #a00000 100%);
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(128, 0, 0, 0.2);
    }

    .btn:hover {
      background: linear-gradient(135deg, #5c0000 0%, #800000 100%);
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(128, 0, 0, 0.3);
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-2px);
    }

    .btn-danger {
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    }

    .btn-danger:hover {
      background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
    }

    .table-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .table-header {
      background: linear-gradient(135deg, #800000 0%, #a00000 100%);
      color: white;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 600;
      font-size: 18px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th, table td {
      padding: 16px 20px;
      text-align: left;
      border-bottom: 1px solid rgba(0, 0, 0, 0.06);
      font-size: 14px;
    }

    table th {
      background: linear-gradient(135deg, #800000 0%, #a00000 100%);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.5px;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    table th:first-child {
      border-top-left-radius: 0;
    }

    table th:last-child {
      border-top-right-radius: 0;
    }

    table tbody tr {
      transition: all 0.2s ease;
    }

    table tbody tr:hover {
      background: rgba(128, 0, 0, 0.03);
      transform: scale(1.001);
    }

    table tbody tr:nth-child(even) {
      background: rgba(248, 250, 252, 0.5);
    }

    .action-btns {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-active {
      background: rgba(34, 197, 94, 0.1);
      color: #15803d;
    }

    .status-inactive {
      background: rgba(239, 68, 68, 0.1);
      color: #dc2626;
    }

    .status-pending {
      background: rgba(245, 158, 11, 0.1);
      color: #d97706;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #64748b;
    }

    .empty-state svg {
      width: 64px;
      height: 64px;
      margin-bottom: 16px;
      opacity: 0.5;
    }

    .data-cell {
      font-weight: 500;
      color: #334155;
    }

    .id-cell {
      font-family: 'Courier New', monospace;
      background: rgba(128, 0, 0, 0.05);
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
    }

    .time-cell {
      font-size: 13px;
      color: #64748b;
    }

    @media (max-width: 768px) {
      .main {
        margin-left: 0;
        padding: 16px;
      }
      
      .nav-actions {
        flex-wrap: wrap;
        gap: 8px;
      }
      
      .btn {
        padding: 8px 12px;
        font-size: 12px;
      }
      
      table {
        font-size: 12px;
      }
      
      table th, table td {
        padding: 12px 8px;
      }
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="main">
  <nav>
    <div class="nav-title">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
        <path d="M15 18H9"></path>
        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
        <circle cx="17" cy="18" r="2"></circle>
        <circle cx="7" cy="18" r="2"></circle>
      </svg>
      Manage Vehicle Log
    </div>
    <div class="nav-actions">
      <a href="?export_today=true" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7,10 12,15 17,10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        Export Daily
      </a>
      <a href="?export_week=true" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7,10 12,15 17,10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        Export Weekly
      </a>
      <a href="?export_all=true" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7,10 12,15 17,10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        Export All
      </a>
      <a href="adminlogin.php" class="btn btn-secondary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16,17 21,12 16,7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        Logout
      </a>
    </div>
  </nav>

  <div class="table-container">
    <div class="table-header">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 6h18"></path>
        <path d="M3 12h18"></path>
        <path d="M3 18h18"></path>
      </svg>
      Vehicle Log Records
    </div>
    
    <?php if ($vehicleLogResult && $vehicleLogResult->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                  <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                Log ID
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
                  <path d="M15 18H9"></path>
                  <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
                  <circle cx="17" cy="18" r="2"></circle>
                  <circle cx="7" cy="18" r="2"></circle>
                </svg>
                Vehicle ID
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <circle cx="12" cy="16" r="1"></circle>
                  <path d="m7 11 0-3a5 5 0 0 1 10 0v3"></path>
                </svg>
                Gate ID
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Guard ID
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 2v20m8-10H4"></path>
                </svg>
                Time In
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 2v20m8-10H4"></path>
                </svg>
                Time Out
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M12 6v6l4 2"></path>
                </svg>
                Status
              </div>
            </th>
            <th>
              <div style="display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="3"></circle>
                  <path d="M12 1v6m0 6v6"></path>
                  <path d="m21 12-6 0m-6 0-6 0"></path>
                </svg>
                Actions
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php while($log = $vehicleLogResult->fetch_assoc()): ?>
            <tr>
              <td><span class="id-cell"><?= htmlspecialchars($log['LogID']) ?></span></td>
              <td><span class="data-cell"><?= htmlspecialchars($log['VehicleID']) ?></span></td>
              <td><span class="data-cell"><?= htmlspecialchars($log['GateID']) ?></span></td>
              <td><span class="data-cell"><?= htmlspecialchars($log['GuardID']) ?></span></td>
              <td><span class="time-cell"><?= htmlspecialchars($log['TimeIn']) ?></span></td>
              <td><span class="time-cell"><?= htmlspecialchars($log['TimeOut']) ?></span></td>
              <td>
                <span class="status-badge <?= 
                  strtolower($log['Status']) === 'active' ? 'status-active' : 
                  (strtolower($log['Status']) === 'inactive' ? 'status-inactive' : 'status-pending') 
                ?>">
                  <?php if(strtolower($log['Status']) === 'active'): ?>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                      <polyline points="22,4 12,14.01 9,11.01"></polyline>
                    </svg>
                  <?php elseif(strtolower($log['Status']) === 'inactive'): ?>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="15" y1="9" x2="9" y2="15"></line>
                      <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                  <?php else: ?>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path d="M12 6v6l4 2"></path>
                    </svg>
                  <?php endif; ?>
                  <?= htmlspecialchars($log['Status']) ?>
                </span>
              </td>
              <td class="action-btns">
                <a href="deletevehiclelog.php?logid=<?= $log['LogID'] ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Are you sure you want to delete this log?')"
                   style="padding: 8px 12px; font-size: 12px;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3,6 5,6 21,6"></polyline>
                    <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                  Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-state">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path>
          <path d="M15 18H9"></path>
          <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path>
          <circle cx="17" cy="18" r="2"></circle>
          <circle cx="7" cy="18" r="2"></circle>
        </svg>
        <h3 style="margin-bottom: 8px; font-weight: 600;">No vehicle logs found</h3>
        <p>There are currently no vehicle log records to display.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>