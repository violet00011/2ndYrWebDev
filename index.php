    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Villanueva Vehicle Gate System</title>
    <style>
            * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            }
            body {
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
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
            height:70px;
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
            main {
            text-align: center;
            padding: 60px 20px 20px;
            position: relative;
            z-index: 1;
            color: white;
            text-shadow: 1px 1px 5px #000;
            flex-grow: 1;
            }
            main h1 {
            font-size: 90px;
            margin-bottom: 10px;
            margin-top: -30px;
            font-family: 'Anton SC', sans-serif;
            line-height: 1.2;
            }
            main p {
            font-size: 20px;
            margin-bottom: 40px;
            font-family: 'Anton SC', sans-serif;
            }
            .btn-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            max-width: 300px;
            margin: 0 auto 30px;
            }
            .btn-container button {
            width: 100%;
            padding: 25px 20px;
            font-size: 16px;
            background-color: rgb(116, 68, 68);
            color: white;
            border: none;
            border-radius: 5px;
            opacity: 0.8;
            cursor: pointer;
            transition: opacity 0.3s;
            }
            .btn-container button:hover {
            opacity: 1;
            }
            .btn-container a {
            text-decoration: none;
            }
            html, body {
            overflow-x: hidden;
            overflow-y: hidden;
            }
            .manual-position {
            position: absolute;
            left: 55%;
            top: 250px;
            transform: translateX(-50%);
            width: 1500px;
            z-index: 1;
            pointer-events: none;
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
            main h1 {
            font-size: 50px;
            }
            main p {
            font-size: 16px;
            }
            .gate-decoration {
            display: none;
            }
            }
    </style>
    </head>
    <body>
    <img src="Assets/bulsugate.png" class="manual-position" alt="BulSU Gate">
    <nav>
    <div class="nav-left">
        <img src="Assets/bulsulogo.png" alt="BulSU Logo" />
        <h1>BulSU Online Vehicle Gate System</h1>
    </div>
    <div class="nav-right">
        <a href="index.php"><i class="fas fa-home"></i> Homepage</a>
        <a href="aboutus.php"><i class="fas fa-users"></i> About Us</a>
        <a href="contactusmain.php"><i class="fas fa-envelope"></i> Contact Us</a>
    </div>
    </nav>
    <main>
    <h1>Welcome to BulSU Online<br>Vehicle Gate System</h1>
    <p>A project developed for IT211 - Web Systems and Technologies</p>
    <div class="btn-container">
    <a href="login.php">
    <button>Log in</button>
    </a>
    <a href="onetimesched.php">
    <button>Schedule a One-Time Visit</button>
    </a>
    <a href="signupvehiclepass.php">
    <button>Sign Up For A Vehicle Owner Pass</button>
    </a>
    </div>
    </main>
    </body>
    </html>