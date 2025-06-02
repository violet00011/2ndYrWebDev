<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Admin Sidebar</title>
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        .sidebar {
            width: 220px;
            background: linear-gradient(135deg, maroon 0%, #8B0000 100%);
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            font-family: sans-serif;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .sidebar a,
        .dropdown-btn {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            font-size: 16px;
            position: relative;
            z-index: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
        }

        .sidebar a::before,
        .dropdown-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .sidebar a:hover,
        .dropdown-btn:hover {
            background-color: rgba(92, 0, 0, 0.8);
            border-left: 3px solid rgba(255, 255, 255, 0.5);
            transform: translateX(2px);
        }

        .sidebar a:hover::before,
        .dropdown-btn:hover::before {
            opacity: 1;
        }

        .dropdown-container {
            display: none;
            background: rgba(169, 68, 66, 0.9);
            padding-left: 10px;
            backdrop-filter: blur(5px);
            border-left: 2px solid rgba(255, 255, 255, 0.2);
            margin-left: 10px;
            margin-right: 10px;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .dropdown-container.show {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-container a {
            padding: 10px 20px;
            font-size: 16px;
            border-left: none;
            transition: all 0.3s ease;
        }

        .dropdown-container a:hover {
            background-color: rgba(145, 47, 47, 0.9);
            transform: translateX(4px);
            border-left: 2px solid rgba(255, 255, 255, 0.4);
        }

        .dropdown-icon {
            font-size: 14px;
            margin-left: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-btn.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .sidebar a:hover,
        .dropdown-btn:hover {
            box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }


    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Menu</h2>
        <a href="admindashboard.php">Dashboard</a>
        <button class="dropdown-btn">
            Profiling <span class="dropdown-icon">▼</span>
        </button>
        <div class="dropdown-container">
            <a href="campusprofile.php">Campus Profile</a>
            <a href="manage_gate.php">Gate Profile</a>
        </div>
        <button class="dropdown-btn">
            Vehicle Registration <span class="dropdown-icon">▼</span>
        </button>
        <div class="dropdown-container">
            <a href="pending_vehicle.php">Application</a>
            <a href="registeredvehicle.php">Registered</a>
        </div>
        <button class="dropdown-btn">
            Monitoring <span class="dropdown-icon">▼</span>
        </button>
        <div class="dropdown-container">
            <a href="gatestatus.php">Gate Status</a>
            <a href="manage_visitor.php">Visitor</a>
            <a href="manage_vehiclelog.php">Logs</a>
        </div>
        <button class="dropdown-btn">
            User Accounts <span class="dropdown-icon">▼</span>
        </button>
        <div class="dropdown-container">
            <a href="manage_staff.php">Staff</a>
            <a href="manage_guard.php">Guard</a>
            <a href="manage_vehicleowner.php">Vehicle Owner</a>
        </div>
        <a href="aboutussidebar.php">About Us</a>
        <a href="contactus.php">Contact Us</a>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var dropdowns = document.getElementsByClassName("dropdown-btn");
            for (var i = 0; i < dropdowns.length; i++) {
                dropdowns[i].addEventListener("click", function () {
                    this.classList.toggle("active");
                    var dropdownContent = this.nextElementSibling;
                    var icon = this.querySelector(".dropdown-icon");
                    
                    if (dropdownContent.style.display === "block") {
                        dropdownContent.style.display = "none";
                        dropdownContent.classList.remove("show");
                        icon.textContent = "▼";
                    } else {
                        dropdownContent.style.display = "block";
                        dropdownContent.classList.add("show");
                        icon.textContent = "▲";
                    }
                });
            }
        });
    </script>
</body>
</html>