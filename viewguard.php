<?php
include 'connectdb.php';

$conn = openCon();

if (isset($_GET['guardid'])) {
    $guardID = $_GET['guardid'];

    $stmt = $conn->prepare("SELECT * FROM guard WHERE GuardID = ?");
    $stmt->bind_param("i", $guardID);
    $stmt->execute();
    $result = $stmt->get_result();
    $guard = $result->fetch_assoc();
    $stmt->close();

    if (!$guard) {
        echo "Guard not found.";
        closeCon($conn);
        exit;
    }
} else {
    echo "No GuardID provided.";
    closeCon($conn);
    exit;
}

closeCon($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Guard</title>
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
            padding: 30px;
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
            font-size: 18px;
        }

        .profile-img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 4px solid #6A040F;
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
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #9D0208;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Guard Information</h2>

    <?php
    
    $imgPath = 'Assets/defaultpfp.jpg';
    ?>

    <div class="info"><strong>Full Name:</strong> <?= htmlspecialchars($guard['FirstName'] . ' ' . $guard['MiddleName'] . ' ' . $guard['LastName']) ?></div>
    <div class="info"><strong>Shift Days:</strong> <?= htmlspecialchars($guard['ShiftDays']) ?></div>
    <div class="info"><strong>Shift Start Time:</strong> <?= htmlspecialchars($guard['ShiftHoursStart']) ?></div>
    <div class="info"><strong>Shift End Time:</strong> <?= htmlspecialchars($guard['ShiftHoursEnd']) ?></div>
    <div class="info"><strong>Gate ID:</strong> <?= htmlspecialchars($guard['GateID']) ?></div>
    <div class="info"><strong>Username:</strong> <?= htmlspecialchars($guard['Username']) ?></div>

    <a href="manage_guard.php" class="btn">Close</a>
</div>

</body>
</html>
