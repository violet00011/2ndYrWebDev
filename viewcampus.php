<?php
include 'connectdb.php';

$conn = openCon();

if (isset($_GET['gateid'])) {
    $gateID = $_GET['gateid'];

    $stmt = $conn->prepare("SELECT Campus, Address FROM gate WHERE GateID = ?");
    $stmt->bind_param("s", $gateID);
    $stmt->execute();
    $result = $stmt->get_result();
    $campus = $result->fetch_assoc();
    $stmt->close();

    if (!$campus) {
        echo "Campus not found.";
        closeCon($conn);
        exit;
    }
} else {
    echo "No GateID provided.";
    closeCon($conn);
    exit;
}

closeCon($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Campus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            padding: 30px;
        }
        .container {
            max-width: 500px;
            background: white;
            margin: auto;
            margin-top: 150px;
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
            margin: 15px 0;
            font-size: 18px;
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
    <h2>Campus Profile</h2>
    <div class="info"><strong>Campus:</strong> <?php echo htmlspecialchars($campus['Campus']); ?></div>
    <div class="info"><strong>Address:</strong> <?php echo htmlspecialchars($campus['Address']); ?></div>
    <br><br>
    <a href="campusprofile.php" class="btn">Close</a>
</div>

</body>
</html>
