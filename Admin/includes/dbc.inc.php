<?php

$host = "localhost";
$dbname = "jeeps";
$dbusername = "root";
$dbpassword = "";

try {
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
