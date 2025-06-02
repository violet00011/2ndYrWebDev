    <?php
    include 'connectdb.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $conn = openCon();

        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $college = $_POST['college'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $position = $_POST['position'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $photo_name = basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . time() . "_" . $photo_name;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO vehicle_owner 
                (LastName, FirstName, MiddleName, Department, ContactNumber, Email, Position, Username, Password, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", 
                $lastname, $firstname, $middlename, $college, $contact, $email, $position, $username, $password, $target_file);

            if ($stmt->execute()) {
                header("Location: signupsuccess.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading file.";
        }

        closeCon($conn);
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
        margin: 0; padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        }

        body {
        background: url("Assets/langit.jpg") no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        }

        nav {
        background-color: maroon;
        color: white;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        position: relative;
        height: 70px;
        width: 100%;
        }

        nav::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(128, 0, 32, 0.9), rgba(100, 0, 25, 0.95));
        backdrop-filter: blur(10px);
        z-index: -1;
        }

        .nav-left {
        display: flex;
        align-items: center;
        gap: 15px;
        }

        .nav-left img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        }

        .nav-left img:hover {
        border-color: rgba(255, 255, 255, 0.8);
        transform: scale(1.05);
        }

        nav .nav-left h1 {
        font-size: 22px;
        font-weight: 600;
        letter-spacing: -0.5px;
        }

        nav .nav-right {
        display: flex;
        align-items: center;
        gap: 30px;
        }

        nav a {
        color: white;
        text-decoration: none;
        font-weight: 400;
        padding: 10px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        }

        nav a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
        }

        nav a:hover::before {
        left: 100%;
        }

        nav a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .signup-container {
        background: rgba(255, 255, 255, 0.95);
        margin-top: 40px;
        padding: 40px;
        border-radius: 15px;
        width: 90%;
        max-width: 800px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .signup-container h1 {
        font-family: 'Anton SC', sans-serif;
        font-size: 32px;
        color: maroon;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        }

        .row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        }

        .row input {
        flex: 1;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        }

        .row input:focus {
        outline: none;
        border-color: maroon;
        box-shadow: 0 0 10px rgba(128, 0, 32, 0.2);
        }

        label {
        margin-top: 10px;
        font-weight: bold;
        color: maroon;
        margin-bottom: 10px;
        }

        /* Custom File Upload Styles */
        .file-upload-container {
        position: relative;
        display: inline-block;
        width: 100%;
        }

        .file-upload-box {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 40px 20px;
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        cursor: pointer;
        transition: all 0.3s ease;
        min-height: 120px;
        position: relative;
        overflow: hidden;
        }

        .file-upload-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(128, 0, 32, 0.05), transparent);
        transition: left 0.5s ease;
        }

        .file-upload-box:hover::before {
        left: 100%;
        }

        .file-upload-box:hover {
        border-color: maroon;
        background: linear-gradient(135deg, #fefefe, #f8f9fa);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(128, 0, 32, 0.1);
        }

        .file-upload-box.dragover {
        border-color: maroon;
        background: linear-gradient(135deg, #fff5f5, #ffe5e5);
        transform: scale(1.02);
        }

        .upload-icon {
        font-size: 48px;
        color: maroon;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        }

        .file-upload-box:hover .upload-icon {
        transform: scale(1.1);
        color: #8B0000;
        }

        .upload-plus {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        color: maroon;
        background: rgba(128, 0, 32, 0.1);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        }

        .file-upload-box:hover .upload-plus {
        background: rgba(128, 0, 32, 0.2);
        transform: rotate(90deg);
        }

        .upload-text {
        text-align: center;
        color: #555;
        font-size: 16px;
        font-weight: 500;
        }

        .upload-text strong {
        color: maroon;
        font-weight: 600;
        }

        .upload-hint {
        font-size: 12px;
        color: #888;
        margin-top: 5px;
        }

        .file-input {
        position: absolute;
        left: -9999px;
        opacity: 0;
        }

        .file-preview {
        display: none;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: rgba(128, 0, 32, 0.05);
        border-radius: 8px;
        margin-top: 10px;
        }

        .file-preview.show {
        display: flex;
        }

        .preview-icon {
        font-size: 24px;
        color: maroon;
        }

        .file-info {
        flex: 1;
        }

        .file-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
        }

        .file-size {
        font-size: 12px;
        color: #666;
        }

        .remove-file {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        }

        .remove-file:hover {
        background: #c82333;
        transform: scale(1.1);
        }

        button {
        padding: 15px 30px;
        background: linear-gradient(135deg, maroon, #8B0000);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin-top: 10px;
        }

        button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #8B0000, maroon);
        }

        @media (max-width: 768px) {
        nav {
            padding: 15px 20px;
            flex-direction: column;
            gap: 15px;
            height: auto;
        }

        nav .nav-right {
            gap: 20px;
        }

        nav .nav-left h1 {
            font-size: 18px;
            text-align: center;
        }

        nav a {
            padding: 8px 15px;
            font-size: 14px;
        }

        .row {
            flex-direction: column;
        }

        .signup-container {
            padding: 25px;
            margin: 10px;
        }

        .file-upload-box {
            padding: 30px 15px;
        }

        .upload-icon {
            font-size: 36px;
        }
        }
    </style>
    </head>
    <body>

    <nav>
    <div class="nav-left">
        <img src="Assets/bulsulogo.png" alt="BulSU Logo">
        <h1>BulSU Online Vehicle Gate System</h1>
    </div>
    <div class="nav-right">
        <a href="index.php"><i class="fas fa-home"></i> Homepage</a>
        <a href="#"><i class="fas fa-sign-in-alt"></i> Log In</a>
        <a href="aboutus.php"><i class="fas fa-users"></i> About Us</a>
    </div>
    </nav>

    <div class="signup-container">
    <h1>Vehicle Owner Sign Up</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
        <input type="text" name="lastname" placeholder="Last Name" required>
        <input type="text" name="firstname" placeholder="First Name" required>
        </div>

        <div class="row">
        <input type="text" name="middlename" placeholder="Middle Name" required>
        <input type="text" name="college" placeholder="College / Department" required>
        </div>

        <div class="row">
        <input type="text" name="contact" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="row">
        <input type="text" name="position" placeholder="Position" required>
        <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="row">
        <input type="password" name="password" placeholder="Password" required>
        </div>

        <label for="photo">Upload your photo here:</label>
        <div class="file-upload-container">
        <div class="file-upload-box" onclick="document.getElementById('photo').click()">
            <div class="upload-plus">
            <i class="fas fa-plus"></i>
            </div>
            <div class="upload-icon">
            <i class="fas fa-camera"></i>
            </div>
            <div class="upload-text">
            <strong>Click to upload</strong> or drag and drop<br>
            <span class="upload-hint">PNG, JPG, JPEG up to 10MB</span>
            </div>
        </div>
        <input type="file" name="photo" id="photo" accept="image/*" required class="file-input">
        <div class="file-preview" id="filePreview">
            <div class="preview-icon">
            <i class="fas fa-image"></i>
            </div>
            <div class="file-info">
            <div class="file-name" id="fileName"></div>
            <div class="file-size" id="fileSize"></div>
            </div>
            <button type="button" class="remove-file" onclick="removeFile()">
            <i class="fas fa-times"></i>
            </button>
        </div>
        </div>

        <button type="submit">Proceed</button>
    </form>
    </div>

    <script>
    const fileInput = document.getElementById('photo');
    const fileUploadBox = document.querySelector('.file-upload-box');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        handleFile(e.target.files[0]);
    });

    // Handle drag and drop
    fileUploadBox.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    fileUploadBox.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    fileUploadBox.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
        fileInput.files = files;
        handleFile(files[0]);
        }
    });

    function handleFile(file) {
        if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        filePreview.classList.add('show');
        fileUploadBox.style.display = 'none';
        }
    }

    function removeFile() {
        fileInput.value = '';
        filePreview.classList.remove('show');
        fileUploadBox.style.display = 'flex';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    </script>

    </body>
    </html>