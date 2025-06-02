<?php
include 'connectdb.php';

if (!isset($_GET['staffid'])) {
    header('Location: manage_staff.php');
    exit();
}

$conn = openCon();

$id = $_GET['staffid'];

$sql = "DELETE FROM staff WHERE StaffID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: manage_staff.php');
    exit();
} else {
    echo "Error deleting record: " . $stmt->error;
}

closeCon($conn);
?>
