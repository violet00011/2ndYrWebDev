<?php
include 'connectdb.php';

if (!isset($_GET['visitorid'])) {
    header('Location: manage_visitor.php');
    exit();
}

$conn = openCon();

$visitorid = $_GET['visitorid'];

$sql = "DELETE FROM visitor WHERE VisitorID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $visitorid);

if ($stmt->execute()) {
    header('Location: manage_visitor.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
