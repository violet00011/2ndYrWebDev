<?php
require_once 'connectdb.php';

if (isset($_POST['visitor_id'])) {
    $visitorId = $_POST['visitor_id'];
    $conn = openCon();

    // Instead of deleting, update the status to "Denied"
    $stmt = $conn->prepare("UPDATE visitor SET Status = 'Denied' WHERE VisitorID = ?");
    $stmt->bind_param("s", $visitorId);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    closeCon($conn);
} else {
    echo "invalid";
}
?>
