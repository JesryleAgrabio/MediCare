<?php
require_once "dbc.inc.php";


session_start();

$routeName = $_POST['routeName'];
$startLocation = $_POST['startLocation'];  
$endLocation = $_POST['endLocation'];
$stops = $_POST['stops'];
$distanceKm = $_POST['distanceKm']; 
$fare = $_POST['fare'];
$estimatedTime = $_POST['estimatedTime'];

if (isset($_POST['routeId'])) {
   
    $routeId = $_POST['routeId'];
    $stmt = $conn->prepare("UPDATE route SET routeName=:routeName, startLocation=:startLocation, endLocation=:endLocation, stops=:stops, distanceKm=:distanceKm, fare=:fare, estimatedTime=:estimatedTime WHERE routeId=:routeId");
    
    $stmt->bindParam(':routeName', $routeName);
    $stmt->bindParam(':startLocation', $startLocation);
    $stmt->bindParam(':endLocation', $endLocation);
    $stmt->bindParam(':stops', $stops);
    $stmt->bindParam(':distanceKm', $distanceKm);
    $stmt->bindParam(':fare', $fare);
    $stmt->bindParam(':estimatedTime', $estimatedTime);
    $stmt->bindParam(':routeId', $routeId);

   
    if ($stmt->execute()) {
        $_SESSION['message'] = "Route UPDATED SUCCESSFULLY!";
        header("Location: ../Route.php");
        exit();
    } else {
        echo "Error updating route.";
    }
   
} else {
 
    $stmt = $conn->prepare("INSERT INTO route (routeName, startLocation, endLocation, stops, distanceKm, fare, estimatedTime) VALUES (:routeName, :startLocation, :endLocation, :stops, :distanceKm, :fare, :estimatedTime)");

    
    $stmt->bindParam(':routeName', $routeName);
    $stmt->bindParam(':startLocation', $startLocation);
    $stmt->bindParam(':endLocation', $endLocation);
    $stmt->bindParam(':stops', $stops);
    $stmt->bindParam(':distanceKm', $distanceKm);
    $stmt->bindParam(':fare', $fare);
    $stmt->bindParam(':estimatedTime', $estimatedTime);

  
    if ($stmt->execute()) {
        $_SESSION['message'] = "Route ADDED SUCCESSFULLY!";
        header("Location: ../Route.php");
        exit();
    } else {
        echo "Error adding route.";
    }
}
?>
