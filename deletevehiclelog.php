<?php
include 'connectdb.php';

if (!isset($_GET['logid'])) {
    header('Location: manage_vehiclelog.php');
    exit();
}

$conn = openCon();

$logid = $_GET['logid'];

$sql = "DELETE FROM vehicle_log WHERE LogID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logid);

if ($stmt->execute()) {
    header('Location: manage_vehiclelog.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
