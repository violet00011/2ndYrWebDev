<?php
include('connectdb.php');
$conn = openCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $logID = $_POST['log_id'];
    $action = $_POST['action'];
    
    if ($action == 'in') {
        $stmt = $conn->prepare("UPDATE vehicle_log SET TimeIn = NOW(), Status = 'Inside' WHERE LogID = ?");
    } elseif ($action == 'out') {
        $stmt = $conn->prepare("UPDATE vehicle_log SET TimeOut = NOW(), Status = 'Outside' WHERE LogID = ?");
    }
    
    if ($stmt) {
        $stmt->bind_param("i", $logID);
        if ($stmt->execute()) {
            header("Location: guarddashboard.php");
            exit;
        } else {
            echo "Error executing update: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement: " . $conn->error;
    }
}

closeCon($conn);
?>