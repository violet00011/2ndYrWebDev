<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - BulSU Campuses</title>
  <link href="https://fonts.googleapis.com/css2?family=Anton+SC&family=Poppins&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url("Assets/langit.jpg") no-repeat center center fixed;
            background-size: cover;
            color: white;
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
            color: black;
            font-family: 'Anton SC', sans-serif;
            text-align: center;
            margin-top: 0;
        }
        .nav-tabs .nav-link {
            background-color: color: rgba(255, 255, 255, 0.1);
            color: black;
            font-weight: bold;
        }
        .nav-tabs .nav-link.active {
            background-color: maroon;
            color: white;
        }
        .tab-content {
            margin-top: 20px;
        }
        .tab-pane {
            background-color: maroon;
            color: whitw;
            padding: 20px;
            border-radius: 8px;
        }
        .map-container {
            height: 400px;
            margin-top: 20px;
        }
</style>

</head>
<body>

<nav>
    <a href="contactusmain.php" style="color: white; font-weight: bold;">Contact Us</a>
   <a href="aboutus.php" style="float: right; color: white; font-weight: bold;">About Us</a>
  <a href="contactusmain.php" style="float: right; color: white; font-weight: bold;">Contact Us</a>
  <a href="index.php" style="float: right; color: white; font-weight: bold;">Home</a>
</nav>

<h1>Contact Us - Our Campuses</h1>

<!-- Bootstrap Tab Navigation -->
<ul class="nav nav-tabs" id="campusTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="tab-main" data-bs-toggle="tab" href="#campus-main" role="tab" aria-controls="campus-main" aria-selected="true">Main Campus</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab-bustos" data-bs-toggle="tab" href="#campus-bustos" role="tab" aria-controls="campus-bustos" aria-selected="false">Bustos Campus</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab-meneses" data-bs-toggle="tab" href="#campus-meneses" role="tab" aria-controls="campus-meneses" aria-selected="false">Meneses Campus</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab-sarmiento" data-bs-toggle="tab" href="#campus-sarmiento" role="tab" aria-controls="campus-sarmiento" aria-selected="false">Sarmiento Campus</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab-hagonoy" data-bs-toggle="tab" href="#campus-hagonoy" role="tab" aria-controls="campus-hagonoy" aria-selected="false">Hagonoy Campus</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="tab-sanrafael" data-bs-toggle="tab" href="#campus-sanrafael" role="tab" aria-controls="campus-sanrafael" aria-selected="false">San Rafael Campus</a>
  </li>
</ul>

<div class="tab-content" id="campusTabContent">
  <div class="tab-pane fade show active" id="campus-main" role="tabpanel" aria-labelledby="tab-main">
     <h3>Main Campus</h3>
    <p>MacArthur Hwy, Malolos, Bulacan, Philippines.</p>
    <p>officeofthepresident@bulsu.edu.ph</p>
  </div>
  <div class="tab-pane fade" id="campus-bustos" role="tabpanel" aria-labelledby="tab-bustos">
    <h3>Bustos Campus</h3>
    <p>L. Mercado St. Corner C.L. Hilario St. Bustos, Bulacan – 3007, Philippines</p>
    <p>officeofthedean.bc@bulsu.edu.ph</p>
  </div>
  <div class="tab-pane fade" id="campus-meneses" role="tabpanel" aria-labelledby="tab-meneses">
    <h3>Meneses Campus</h3>
    <p>TJS Matungao, Bulakan Bulacan – 3017, Philippines</p>
    <p>odmc@bulsu.edu.ph</p>
  </div>
  <div class="tab-pane fade" id="campus-sarmiento" role="tabpanel" aria-labelledby="tab-sarmiento">
    <h3>Sarmiento Campus</h3>
    <p>University Heights, Brgy. Kaypian, City of San Jose del Monte Bulacan, 3023, Philippines</p>
    <p>sarmiento@bulsu.edu.ph</p>
  </div>
  <div class="tab-pane fade" id="campus-hagonoy" role="tabpanel" aria-labelledby="tab-hagonoy">
    <h3>Hagonoy Campus</h3>
    <p>Iba-Carillo, Hagonoy Bulacan – 3002, Philippines</p>
    <p>officeofthedean.hc@bulsu.edu.ph</p>
  </div>
  <div class="tab-pane fade" id="campus-sanrafael" role="tabpanel" aria-labelledby="tab-sanrafael">
    <h3>San Rafael Campus</h3>
    <p>Bypass Road, Baranggay San Roque, San Rafael Bulacan</p>
    <p>officeofthedean.src@bulsu.edu.ph</p>
  </div>
</div>

<div class="map-container" id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>

  var map = L.map('map').setView([14.7357, 121.0750], 13);  

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  function changeCampusLocation(lat, lon, name, address) {
    map.setView([lat, lon], 15);  
    L.marker([lat, lon]).addTo(map)
      .bindPopup("<b>" + name + " Campus</strong>" +"</b><br>Address: <strong>" + address )
      .openPopup();
  }

document.getElementById('tab-main').addEventListener('click', function() {
  changeCampusLocation(14.858270, 120.815091, 'Main', 'MacArthur Hwy, Malolos, Bulacan, Philippines');
});

document.getElementById('tab-bustos').addEventListener('click', function() {
  changeCampusLocation(14.937102, 120.928576, 'Bustos', 'L. Mercado St. Corner C.L. Hilario St. Bustos, Bulacan – 3007, Philippines');
});

document.getElementById('tab-meneses').addEventListener('click', function() {
  changeCampusLocation(14.793250, 120.881345, 'Meneses', 'TJS Matungao, Bulakan Bulacan – 3017, Philippines');
});

document.getElementById('tab-sarmiento').addEventListener('click', function() {
  changeCampusLocation(14.813487, 121.066413, 'Sarmiento', 'University Heights, Brgy. Kaypian, San Jose del Monte Bulacan – 3023, Philippines');
});

document.getElementById('tab-hagonoy').addEventListener('click', function() {
  changeCampusLocation(14.866000, 120.765063, 'Hagonoy', 'Iba-Carillo, Hagonoy Bulacan – 3002, Philippines');
});

document.getElementById('tab-sanrafael').addEventListener('click', function() {
  changeCampusLocation(15.007326, 120.941754, 'San Rafael', 'Bypass Road, Brgy. San Roque, San Rafael Bulacan');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
