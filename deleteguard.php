<?php
include 'connectdb.php';

if (!isset($_GET['id'])) {
    header('Location: manage_guard.php');
    exit();
}

$conn = openCon();

$id = $_GET['id'];

$sql = "DELETE FROM guard WHERE GuardID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: manage_guard.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
