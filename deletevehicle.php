<?php
include 'connectdb.php';

if (!isset($_GET['vehicleid'])) {
    header('Location: manage_vehicle.php');
    exit();
}

$conn = openCon();

$vehicleid = $_GET['vehicleid'];

$sql = "DELETE FROM vehicle WHERE VehicleID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicleid);

if ($stmt->execute()) {
    header('Location: manage_vehicle.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
