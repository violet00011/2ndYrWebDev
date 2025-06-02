<?php
include 'connectdb.php';

$conn = openCon();

if (!isset($_GET['visitorid'])) {
    header('Location: manage_visitor.php');
    exit();
}

$id = $_GET['visitorid'];

$sql = "SELECT * FROM visitor WHERE VisitorID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$visitor = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $contactNumber = $_POST['ContactNumber'];
    $scheduledVisit = $_POST['ScheduledVisit'];
    $vehicleModel = $_POST['VehicleModel'];
    $plateNumber = $_POST['PlateNumber'];
    $purpose = $_POST['Purpose'];

    $sql = "UPDATE visitor 
            SET LastName=?, FirstName=?, MiddleName=?, ContactNumber=?, ScheduledVisit=?, VehicleModel=?, PlateNumber=?, Purpose=? 
            WHERE VisitorID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $lastName, $firstName, $middleName, $contactNumber, $scheduledVisit, $vehicleModel, $plateNumber, $purpose, $id);

    if ($stmt->execute()) {
        header("Location: manage_visitor.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

closeCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Visitor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: maroon;
            --primary-hover: #b30000;
            --text-color: #333;
            --border-color: #e1e5e9;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            --background-overlay: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            padding: 20px;
        }

        nav {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            padding: 20px 30px;
            margin-bottom: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(128, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        nav .nav-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        nav a i {
            font-size: 16px;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin: 40px 0 50px 0;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container {
            background: var(--background-overlay);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px var(--shadow-medium);
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 8px;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 18px;
            z-index: 1;
        }

        input[type="text"], 
        input[type="number"], 
        input[type="datetime-local"],
        textarea {
            width: 100%;
            padding: 18px 20px 18px 50px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: #fff;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus, textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        input:hover, textarea:hover {
            border-color: var(--primary-hover);
        }

        .form-group label {
            position: absolute;
            left: 50px;
            top: 18px;
            font-size: 16px;
            color: #999;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 8px;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label,
        .form-group.has-value label {
            top: -12px;
            left: 46px;
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Remove placeholder text initially */
        input::placeholder {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        input:focus::placeholder {
            opacity: 0.7;
        }

        input[type="datetime-local"] {
            color: var(--text-color);
        }

        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            background: var(--primary-color);
            border-radius: 4px;
            padding: 4px;
            cursor: pointer;
        }

        .button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        button, .btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        button::before, .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        button:hover::before, .btn:hover::before {
            left: 100%;
        }

        button:hover, .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.4);
        }

        button:active, .btn:active {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #666 0%, #555 100%);
        }

        .btn-secondary:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .form-group.error input {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .form-group.error .error-message {
            display: block;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            nav {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            nav .nav-left,
            nav .nav-right {
                justify-content: center;
            }

            h1 {
                font-size: 2rem;
                margin: 30px 0 40px 0;
            }

            .form-container {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .button-container {
                flex-direction: column;
                gap: 15px;
            }

            button, .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            nav {
                padding: 15px;
            }

            nav a {
                padding: 10px 15px;
                font-size: 12px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .form-container {
                padding: 20px;
            }

            input[type="text"], 
            input[type="number"], 
            input[type="datetime-local"] {
                padding: 16px 18px 16px 45px;
            }

            .input-wrapper i {
                left: 14px;
                font-size: 16px;
            }

            .form-group label {
                left: 45px;
            }

            .form-group input:focus + label,
            .form-group input:not(:placeholder-shown) + label,
            .form-group.has-value label {
                left: 41px;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="manage_visitor.php">
            <i class="fas fa-users"></i>
            Manage Visitors
        </a>
    </div>
    <div class="nav-right">
        <a href="admindashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="adminlogin.php">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</nav>

<h1><i class="fas fa-user-edit"></i> Edit Visitor</h1>

<div class="form-container">
    <form method="POST" id="visitorForm">
        <div class="form-grid">
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           name="LastName" 
                           id="lastName"
                           value="<?= htmlspecialchars($visitor['LastName']) ?>" 
                           required>
                    <label for="lastName">Last Name</label>
                </div>
                <div class="error-message">Last name is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           name="FirstName" 
                           id="firstName"
                           value="<?= htmlspecialchars($visitor['FirstName']) ?>" 
                           required>
                    <label for="firstName">First Name</label>
                </div>
                <div class="error-message">First name is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           name="MiddleName" 
                           id="middleName"
                           value="<?= htmlspecialchars($visitor['MiddleName']) ?>">
                    <label for="middleName">Middle Name</label>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <input type="number" 
                           name="ContactNumber" 
                           id="contactNumber"
                           value="<?= htmlspecialchars($visitor['ContactNumber']) ?>" 
                           required>
                    <label for="contactNumber">Contact Number</label>
                </div>
                <div class="error-message">Contact number is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="datetime-local" 
                           name="ScheduledVisit" 
                           id="scheduledVisit"
                           value="<?= date('Y-m-d\TH:i', strtotime($visitor['ScheduledVisit'])) ?>" 
                           required>
                    <label for="scheduledVisit">Scheduled Visit</label>
                </div>
                <div class="error-message">Scheduled visit is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-car"></i>
                    <input type="text" 
                           name="VehicleModel" 
                           id="vehicleModel"
                           value="<?= htmlspecialchars($visitor['VehicleModel']) ?>" 
                           required>
                    <label for="vehicleModel">Vehicle Model</label>
                </div>
                <div class="error-message">Vehicle model is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-id-card"></i>
                    <input type="text" 
                           name="PlateNumber" 
                           id="plateNumber"
                           value="<?= htmlspecialchars($visitor['PlateNumber']) ?>" 
                           required>
                    <label for="plateNumber">Plate Number</label>
                </div>
                <div class="error-message">Plate number is required</div>
            </div>

            <div class="form-group full-width">
                <div class="input-wrapper">
                    <i class="fas fa-clipboard"></i>
                    <input type="text" 
                           name="Purpose" 
                           id="purpose"
                           value="<?= htmlspecialchars($visitor['Purpose']) ?>" 
                           required>
                    <label for="purpose">Purpose of Visit</label>
                </div>
                <div class="error-message">Purpose is required</div>
            </div>
        </div>

        <div class="button-container">
            <button type="submit" id="saveBtn">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
            <a href="manage_visitor.php" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {

        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.value.trim() !== '') {
                input.closest('.form-group').classList.add('has-value');
            }
        });

        document.getElementById('visitorForm').addEventListener('submit', function(e) {
            const saveBtn = document.getElementById('saveBtn');
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        });

        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', function() {
  
                const formGroup = this.closest('.form-group');
                if (this.value.trim() !== '') {
                    formGroup.classList.add('has-value');
                } else {
                    formGroup.classList.remove('has-value');
                }

                if (this.value.trim() !== '' || !this.required) {
                    formGroup.classList.remove('error');
                }
            });
        });

        function validateField() {
            const formGroup = this.closest('.form-group');
            if (this.required && this.value.trim() === '') {
                formGroup.classList.add('error');
                return false;
            } else {
                formGroup.classList.remove('error');
                return true;
            }
        }

        const phoneInput = document.getElementById('contactNumber');
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });

        const plateInput = document.getElementById('plateNumber');
        plateInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href = 'manage_visitor.php';
            }
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                document.getElementById('visitorForm').submit();
            }
        });
    });
</script>

</body>
</html>