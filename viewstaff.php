<?php
include 'connectdb.php';

$conn = openCon();

if (isset($_GET['staffid'])) {
    $staffID = $_GET['staffid'];

    $stmt = $conn->prepare("SELECT * FROM staff WHERE StaffID = ?");
    $stmt->bind_param("i", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
    $stmt->close();

    if (!$staff) {
        echo "Staff not found.";
        closeCon($conn);
        exit;
    }
} else {
    echo "No StaffID provided.";
    closeCon($conn);
    exit;
}

closeCon($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Staff</title>
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
    <h2>Staff Information</h2>

    <?php
    // You can change the default image path if needed
    $imgPath = (!empty($staff['image'])) ? htmlspecialchars($staff['image']) : 'Assets/defaultpfp.jpg';
    ?>
    <img src="<?= $imgPath ?>" alt="Staff Image" class="profile-img">

    <div class="info"><strong>Full Name:</strong> <?= htmlspecialchars($staff['FirstName'] . ' ' . $staff['MiddleName'] . ' ' . $staff['LastName']) ?></div>
    <div class="info"><strong>Position:</strong> <?= htmlspecialchars($staff['Position']) ?></div>
    <div class="info"><strong>Email:</strong> <?= htmlspecialchars($staff['Email']) ?></div>
    <div class="info"><strong>Username:</strong> <?= htmlspecialchars($staff['Username']) ?></div>

    <a href="manage_staff.php" class="btn">Close</a>
</div>

</body>
</html>
