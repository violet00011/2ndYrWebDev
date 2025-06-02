<?php
include 'connectdb.php';

if (!isset($_GET['gateid'])) {
    header('Location: manage_gate.php');
    exit();
}

$conn = openCon();

$gateid = $_GET['gateid'];

$sql = "DELETE FROM gate WHERE GateID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $gateid);

if ($stmt->execute()) {
    header('Location: manage_gate.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
