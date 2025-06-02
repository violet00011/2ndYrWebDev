

<?php
/*function openCon(): mysqli {
    $server = "localhost:3306"; 
    $user = "root"; 
    $pass = ""; 
    $database = "gatesystem";*/

function openCon(): mysqli {
    $server = "sql100.infinityfree.com"; 
    $user = "if0_38820433"; 
    $pass = "Ivykatrina1211"; 
    $database = "if0_38820433_gatesystem";

    $conn = new mysqli($server, $user, $pass, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function closeCon($conn): void {
    $conn->close();
}
?>