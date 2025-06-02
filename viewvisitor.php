<?php
include 'connectdb.php';

$conn = openCon();

// Check if VisitorID is passed
if (!isset($_GET['visitorid']) || empty($_GET['visitorid'])) {
    echo "No VisitorID provided.";
    closeCon($conn);
    exit;
}

$visitorID = $_GET['visitorid'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM visitor WHERE VisitorID = ?");
$stmt->bind_param("i", $visitorID);
$stmt->execute();
$result = $stmt->get_result();
$visitor = $result->fetch_assoc();

$stmt->close();
closeCon($conn);

// Check if record exists
if (!$visitor) {
    echo "Visitor not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Visitor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            margin-top: 100px;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }
        h2 {
            color: #370617;
            margin-bottom: 20px;
        }
        .info {
            margin: 12px 0;
            font-size: 17px;
        }
        .btn {
            background-color: #6A040F;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #9D0208;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Visitor Information</h2>
    <div class="info"><strong>Full Name:</strong> <?= htmlspecialchars($visitor['LastName'] . ', ' . $visitor['FirstName'] . ' ' . $visitor['MiddleName']) ?></div>
    <div class="info"><strong>Contact Number:</strong> <?= htmlspecialchars($visitor['ContactNumber']) ?></div>
    <div class="info"><strong>Scheduled Visit:</strong> <?= htmlspecialchars($visitor['ScheduledVisit']) ?></div>
    <div class="info"><strong>Vehicle Model:</strong> <?= htmlspecialchars($visitor['VehicleModel']) ?></div>
    <div class="info"><strong>Plate Number:</strong> <?= htmlspecialchars($visitor['PlateNumber']) ?></div>
    <div class="info"><strong>Gate ID:</strong> <?= htmlspecialchars($visitor['GateID']) ?></div>
    <div class="info"><strong>Purpose:</strong> <?= htmlspecialchars($visitor['Purpose']) ?></div>
    <div class="info"><strong>Status:</strong> <?= htmlspecialchars($visitor['Status']) ?></div>
    <br><br>
    <a href="manage_visitor.php" class="btn">Back</a>
</div>

</body>
</html>
