<?php
include 'connectdb.php';

$conn = openCon();

if (!isset($_GET['vehicleid'])) {
    header('Location: manage_vehicle.php');
    exit();
}

$vehicleID = $_GET['vehicleid'];

$sql = "SELECT * FROM vehicle WHERE VehicleID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicleID);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plateNumber = $_POST['PlateNumber'];
    $type = $_POST['Type'];
    $model = $_POST['Model'];
    $ownerID = $_POST['OwnerID'];
    $status = $_POST['Status'];

    if (isset($_FILES['PlateNumberImage']) && $_FILES['PlateNumberImage']['error'] == 0) {
        $uploadDir = 'uploads/'; 
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($_FILES['PlateNumberImage']['name'], PATHINFO_EXTENSION);
        $imageName = 'plate_' . $vehicleID . '_' . time() . '.' . $fileExtension;
        $targetFile = $uploadDir . $imageName;
        
        if (move_uploaded_file($_FILES['PlateNumberImage']['tmp_name'], $targetFile)) {
            $plateNumberImage = $targetFile;
            
            if (!empty($vehicle['PlateNumberImage']) && file_exists($vehicle['PlateNumberImage'])) {
                unlink($vehicle['PlateNumberImage']);
            }
        } else {
            $plateNumberImage = $vehicle['PlateNumberImage'];
            echo "<script>alert('Error uploading file. Please try again.');</script>";
        }
    } else {
        $plateNumberImage = $vehicle['PlateNumberImage']; 
    }

    $sql = "UPDATE vehicle SET PlateNumber=?, Type=?, Model=?, OwnerID=?, Status=?, PlateNumberImage=? WHERE VehicleID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $plateNumber, $type, $model, $ownerID, $status, $plateNumberImage, $vehicleID);

    if ($stmt->execute()) {
        header("Location: registeredvehicle.php");
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
  <title>Edit Vehicle</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url("Assets/langit.jpg") no-repeat center center fixed;
      background-size: cover;
      padding: 20px;
    }
    nav {
      background-color: maroon;
      padding: 15px;
      margin-bottom: 20px;
    }
    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }
    h1 {
      color: maroon;
      text-align: center;
      margin-top: 50px;
      margin-bottom: 50px;
    }
    form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: auto;
    }
    input[type="text"], input[type="number"], input[type="email"] {
      width: 95%;
      padding: 12px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    
    /* Enhanced File Upload Styles */
    .file-upload-container {
      position: relative;
      display: inline-block;
      width: 100%;
      margin: 15px 0;
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
      background-color: maroon;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    button:hover {
      background-color: #a00000;
      transform: translateY(-1px);
    }
    
    p {
      margin-top: 2px;
      margin-bottom: 2px;
      font-weight: 500;
      color: #333;
    }
    
    .plate-img {
      width: 200px;
      height: auto;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin: 10px 0;
    }
    
    .current-image-container {
      margin: 15px 0;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 8px;
      border: 1px solid #e9ecef;
    }
  </style>
</head>
<body>

<nav>
  <a href="adminlogin.php" style="float: right; color: white; font-weight: bold;">Logout</a>
  <a href="admindashboard.php" style="float: right; color: white; font-weight: bold;">Admin Dashboard</a>
  <a href="manage_vehicle.php" style="color: white; font-weight: bold;">Manage Vehicles</a>
</nav>

<h1>Edit Vehicle</h1>

<form method="POST" action="" enctype="multipart/form-data">
    <input type="text" name="PlateNumber" placeholder="Plate Number" value="<?= htmlspecialchars($vehicle['PlateNumber']) ?>" required>

    <input type="text" name="Type" placeholder="Vehicle Type" value="<?= htmlspecialchars($vehicle['Type']) ?>" required>

    <input type="text" name="Model" placeholder="Vehicle Model" value="<?= htmlspecialchars($vehicle['Model']) ?>" required>

    <input type="text" name="OwnerID" placeholder="Owner ID" value="<?= htmlspecialchars($vehicle['OwnerID']) ?>" required>

    <input type="text" name="Status" placeholder="Status" value="<?= htmlspecialchars($vehicle['Status']) ?>" required>

    <div class="current-image-container">
        <p>Current Plate Number Image:</p>
        <?php if (!empty($vehicle['PlateNumberImage'])): ?>
            
            <img src="<?= htmlspecialchars($vehicle['PlateNumberImage']) ?>" 
                 alt="Plate Image" 
                 class="plate-img"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <div style="display: none; color: #dc3545; font-style: italic; padding: 10px; background: #f8d7da; border-radius: 4px;">
                ⚠️ Image could not be loaded. 
            </div>
        <?php else: ?>
            <p style="color: #888; font-style: italic;">No Image</p>
        <?php endif; ?>
    </div>

    <div class="file-upload-container">
        <p>Change Plate Number Image:</p>
        
        <input type="file" name="PlateNumberImage" accept="image/*" class="file-input" id="plateImageInput">
        
        <label for="plateImageInput" class="file-upload-box" id="fileUploadBox">
            <div class="upload-plus">
                <i class="fas fa-plus"></i>
            </div>
            <div class="upload-icon">
                <i class="fas fa-camera"></i>
            </div>
            <div class="upload-text">
                <strong>Choose Image</strong> or drag and drop
            </div>
            <div class="upload-hint">
                PNG, JPG, GIF up to 10MB
            </div>
        </label>

        <div class="file-preview" id="filePreview">
            <div class="preview-icon">
                <i class="fas fa-image"></i>
            </div>
            <div class="file-info">
                <div class="file-name" id="fileName"></div>
                <div class="file-size" id="fileSize"></div>
            </div>
            <button type="button" class="remove-file" id="removeFile">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <button type="submit">
        <i class="fas fa-save"></i> Save Changes
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('plateImageInput');
    const fileUploadBox = document.getElementById('fileUploadBox');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeButton = document.getElementById('removeFile');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelection(file);
        }
    });

    // Handle drag and drop
    fileUploadBox.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadBox.classList.add('dragover');
    });

    fileUploadBox.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadBox.classList.remove('dragover');
    });

    fileUploadBox.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadBox.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                fileInput.files = files;
                handleFileSelection(file);
            }
        }
    });

    // Handle remove file
    removeButton.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.remove('show');
        resetUploadBox();
    });

    function handleFileSelection(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        filePreview.classList.add('show');
    }

    function resetUploadBox() {
        // Reset to original state if needed
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>

</body>
</html>