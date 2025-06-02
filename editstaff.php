<?php
include 'connectdb.php';
$conn = openCon();

if (!isset($_GET['staffid'])) {
    header('Location: manage_staff.php');
    exit();
}

$id = $_GET['staffid'];

$sql = "SELECT * FROM staff WHERE StaffID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $position = $_POST['Position'];
    $email = $_POST['Email'];
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    
    $sql = "UPDATE staff SET LastName=?, FirstName=?, MiddleName=?, Position=?, Email=?, Username=?, Password=? WHERE StaffID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $lastName, $firstName, $middleName, $position, $email, $username, $password, $id);
    
    if ($stmt->execute()) {
        header("Location: manage_staff.php");
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
    <title>Edit Staff</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: maroon;
            --primary-hover: #b30000;
            --text-color: #333;
            --border-color: #e1e5e9;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            --background-color: #f5f5f5;
            --success-color: #28a745;
            --error-color: #dc3545;
            --warning-color: #ffc107;
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
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px var(--shadow-medium);
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: scaleIn 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
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
        input[type="email"], 
        input[type="password"] {
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

        input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        input:hover {
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

        input::placeholder {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        input:focus::placeholder {
            opacity: 0.7;
        }

        /* Password field special styling */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary-hover);
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
            border-color: var(--error-color);
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .form-group.error .error-message {
            display: block;
        }

        .form-group.success input {
            border-color: var(--success-color);
            box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.1);
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: var(--error-color); width: 25%; }
        .strength-fair { background: var(--warning-color); width: 50%; }
        .strength-good { background: #28a745; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }

        select {
            width: 100%;
            padding: 18px 20px 18px 50px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: #fff;
            transition: all 0.3s ease;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 16px center;
            background-repeat: no-repeat;
            background-size: 16px;
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
            input[type="email"], 
            input[type="password"] {
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
        <a href="manage_staff.php">
            <i class="fas fa-users-cog"></i>
            Manage Staff
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

<h1><i class="fas fa-user-edit"></i> Edit Staff</h1>

<div class="form-container">
    <form method="POST" id="staffForm">
        <div class="form-grid">
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           name="LastName" 
                           id="lastName"
                           value="<?= htmlspecialchars($staff['LastName']) ?>" 
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
                           value="<?= htmlspecialchars($staff['FirstName']) ?>" 
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
                           value="<?= htmlspecialchars($staff['MiddleName']) ?>">
                    <label for="middleName">Middle Name</label>
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-briefcase"></i>
                    <input type="text" 
                           name="Position" 
                           id="position"
                           value="<?= htmlspecialchars($staff['Position']) ?>" 
                           required>
                    <label for="position">Position</label>
                </div>
                <div class="error-message">Position is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" 
                           name="Email" 
                           id="email"
                           value="<?= htmlspecialchars($staff['Email']) ?>" 
                           required>
                    <label for="email">Email Address</label>
                </div>
                <div class="error-message">Valid email is required</div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" 
                           name="Username" 
                           id="username"
                           value="<?= htmlspecialchars($staff['Username']) ?>" 
                           required>
                    <label for="username">Username</label>
                </div>
                <div class="error-message">Username is required</div>
            </div>

            <div class="form-group full-width">
                <div class="input-wrapper password-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" 
                           name="Password" 
                           id="password"
                           value="<?= htmlspecialchars($staff['Password']) ?>" 
                           required>
                    <label for="password">Password</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <div class="error-message">Password is required</div>
            </div>
        </div>

        <div class="button-container">
            <button type="submit" id="saveBtn">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
            <a href="manage_staff.php" class="btn btn-secondary">
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

        document.getElementById('staffForm').addEventListener('submit', function(e) {
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

                if (this.type === 'email') {
                    validateEmail(this);
                }

                if (this.id === 'password') {
                    checkPasswordStrength(this.value);
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

        function validateEmail(input) {
            const formGroup = input.closest('.form-group');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (input.value && !emailRegex.test(input.value)) {
                formGroup.classList.add('error');
                formGroup.querySelector('.error-message').textContent = 'Please enter a valid email address';
            } else {
                formGroup.classList.remove('error');
                formGroup.querySelector('.error-message').textContent = 'Valid email is required';
            }
        }

        function checkPasswordStrength(password) {
            const strengthIndicator = document.getElementById('passwordStrength');
            const strengthBar = document.getElementById('strengthBar');
            
            if (password.length === 0) {
                strengthIndicator.classList.remove('show');
                return;
            }
            
            strengthIndicator.classList.add('show');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength === 3) {
                strengthBar.classList.add('strength-fair');
            } else if (strength === 4) {
                strengthBar.classList.add('strength-good');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        }

        const usernameInput = document.getElementById('username');
        usernameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href = 'manage_staff.php';
            }
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                document.getElementById('staffForm').submit();
            }
        });
    });

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>