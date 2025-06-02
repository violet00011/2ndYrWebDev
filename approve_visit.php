<?php
session_start();
include('connectdb.php');
$conn = openCon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitor_id = $_POST['visitor_id'];
    $guard_id = $_POST['guard_id'];

    $visitor_query = "SELECT * FROM visitor WHERE VisitorID = '$visitor_id'";
    $visitor_result = mysqli_query($conn, $visitor_query);

    if ($visitor_row = mysqli_fetch_assoc($visitor_result)) {
        $plate_number = $visitor_row['PlateNumber'];
        $temp_vehicle_id = "TEMP_" . $plate_number;

        
        $gate_id = $visitor_row['GateID']; 
        $status = "Approved";

        $insert_log_query = "INSERT INTO vehicle_log (VehicleID, GateID, GuardID, Status) 
                             VALUES ('$temp_vehicle_id', '$gate_id', '$guard_id', '$status')";
        
        $update_visitor_query = "UPDATE visitor SET Status = 'Approved' WHERE VisitorID = '$visitor_id'";

        if (mysqli_query($conn, $insert_log_query) && mysqli_query($conn, $update_visitor_query)) {
            echo "success";
        } else {
            echo "fail";
        }

    } else {
        echo "fail";
    }
    
}

closeCon($conn);
