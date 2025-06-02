<?php
session_start();
include('connectdb.php');
$conn = openCon();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['timein_button'])) {
    $plate_number = trim($_POST['plate_number']);
    $gate_id = $_POST['gate_id'];
    $guard_id = $_POST['guard_id'];

    // 1. Check if vehicle exists
    $vehicle_query = "SELECT VehicleID FROM vehicle WHERE PlateNumber = ?";
    $stmt = $conn->prepare($vehicle_query);
    $stmt->bind_param("s", $plate_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $vehicle_id = $row['VehicleID'];

        // 2. Insert into vehicle_log
        $insert_query = "INSERT INTO vehicle_log (VehicleID, GateID, GuardID, TimeIn, Status) VALUES (?, ?, ?, NOW(6), 'Inside')";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("isi", $vehicle_id, $gate_id, $guard_id);

        if ($insert_stmt->execute()) {
            header("Location: guarddashboard.php?success=timein"); // or show a message
            exit();
        } else {
            echo "Error inserting log: " . $conn->error;
        }

    } else {
        echo "Vehicle with plate number '$plate_number' not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
