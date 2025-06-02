<?php
include 'connectdb.php';

if (!isset($_GET['ownerid'])) {
    header('Location: manage_vehicleowner.php');
    exit();
}

$conn = openCon();

$ownerid = $_GET['ownerid'];

$sql = "DELETE FROM vehicle_owner WHERE OwnerID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ownerid);

if ($stmt->execute()) {
    header('Location: manage_vehicleowner.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
