<?php

include('connectdb.php'); 


$request_id = $_POST['request_id'];
$action = $_POST['action'];

$status = ($action == 'approve') ? 'Approved' : 'Denied';
$sql = "UPDATE vehicle_log SET Status = '$status' WHERE VehicleID = '$request_id'";

if (mysqli_query($conn, $sql)) {
    echo "Request $status successfully!";
    header("Location: guarddashboard.php");
    exit();
} else {
    echo "Error updating request status: " . mysqli_error($conn);
}
?>
