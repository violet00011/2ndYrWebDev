<?php
include 'connectdb.php';
$conn = openCon();

if (isset($_GET['gateid'])) {
    $gateID = $_GET['gateid'];
    $stmt = $conn->prepare("SELECT * FROM gate WHERE GateID = ?");
    $stmt->bind_param("s", $gateID);
    $stmt->execute();
    $result = $stmt->get_result();
    $gate = $result->fetch_assoc();
    $stmt->close();
    
    if (!$gate) {
        echo "Gate not found.";
        closeCon($conn);
        exit;
    }
} else {
    echo "No GateID provided.";
    closeCon($conn);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campus = $_POST['Campus'];
    $gateNumber = $_POST['GateNumber'];
    $status = $_POST['Status'];
    
    $stmt = $conn->prepare("UPDATE gate SET Campus=?, GateNumber=?, Status=? WHERE GateID=?");
    $stmt->bind_param("ssss", $campus, $gateNumber, $status, $gateID);
    
    if ($stmt->execute()) {
        echo "<script>alert('Gate updated successfully!'); window.location.href='manage_gate.php';</script>";
    } else {
        echo "Error updating gate: " . $stmt->error;
    }
    
    $stmt->close();
    closeCon($conn);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gate</title>
    <style>
        :root {
            --primary-color: #6A040F;
            --primary-hover: #9D0208;
            --secondary-color: #370617;
            --cancel-color: #555;
            --cancel-hover: #333;
            --border-color: #e1e5e9;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --text-color: #333;
            --success-color: #28a745;
            --error-color: #dc3545;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 
                        0 8px 16px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select {
            width: 100%;
            padding: 16px 20px;
            font-size: 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: #fff;
            transition: all 0.3s ease;
            outline: none;
            font-family: inherit;
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(106, 4, 15, 0.1);
            transform: translateY(-1px);
        }

        input:hover, select:hover {
            border-color: var(--primary-hover);
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 16px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 48px;
        }

        .btn-container {
            display: flex;
            gap: 16px;
            margin-top: 32px;
        }

        .btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            box-shadow: 0 4px 15px rgba(106, 4, 15, 0.3);
        }

        .btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(106, 4, 15, 0.4);
        }

        .btn.primary:active {
            transform: translateY(0);
        }

        .btn.cancel {
            background: linear-gradient(135deg, var(--cancel-color), var(--cancel-hover));
            color: white;
            box-shadow: 0 4px 15px rgba(85, 85, 85, 0.3);
        }

        .btn.cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(85, 85, 85, 0.4);
        }

        .btn.cancel:active {
            transform: translateY(0);
        }

        /* Loading state */
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            body {
                padding: 16px;
            }
            
            .form-container {
                padding: 24px;
                border-radius: 12px;
            }
            
            h2 {
                font-size: 24px;
                margin-bottom: 24px;
            }
            
            .btn-container {
                flex-direction: column;
                gap: 12px;
            }
            
            input, select, .btn {
                padding: 14px 16px;
            }
        }

        /* Form validation styles */
        .form-group.error input,
        .form-group.error select {
            border-color: var(--error-color);
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
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

        /* Success animation */
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .success {
            animation: successPulse 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Gate</h2>
        <form method="POST" id="editGateForm">
            <div class="form-group">
                <label for="campus">Campus</label>
                <input type="text" id="campus" name="Campus" value="<?= htmlspecialchars($gate['Campus']) ?>" required>
                <div class="error-message">Campus is required</div>
            </div>
            
            <div class="form-group">
                <label for="gateNumber">Gate Number</label>
                <input type="text" id="gateNumber" name="GateNumber" value="<?= htmlspecialchars($gate['GateNumber']) ?>" required>
                <div class="error-message">Gate Number is required</div>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="Status" required>
                    <option value="Entry" <?= $gate['Status'] == 'Entry' ? 'selected' : '' ?>>Entry</option>
                    <option value="Exit" <?= $gate['Status'] == 'Exit' ? 'selected' : '' ?>>Exit</option>
                    <option value="Entry/Exit" <?= $gate['Status'] == 'Entry/Exit' ? 'selected' : '' ?>>Entry/Exit</option>
                </select>
                <div class="error-message">Status is required</div>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn primary" id="submitBtn">
                    Update Gate
                </button>
                <a href="manage_gate.php" class="btn cancel">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Form validation and enhancement
        document.getElementById('editGateForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Updating...';
            
            // Re-enable button after 3 seconds in case of issues
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Gate';
            }, 3000);
        });

        // Real-time validation
        const inputs = document.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                const formGroup = this.closest('.form-group');
                if (this.value.trim() === '') {
                    formGroup.classList.add('error');
                } else {
                    formGroup.classList.remove('error');
                }
            });

            input.addEventListener('input', function() {
                const formGroup = this.closest('.form-group');
                if (this.value.trim() !== '') {
                    formGroup.classList.remove('error');
                }
            });
        });

        // Add keyboard navigation enhancement
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href = 'manage_gate.php';
            }
        });
    </script>
</body>
</html>