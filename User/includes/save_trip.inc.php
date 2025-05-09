<?php
session_start();
require 'dbc.inc.php'; 
$allTrips = $_SESSION['puvs']; 
$confirmedTrips = [];
$userId = $_SESSION['userId'];
$totalfare = 0;

foreach ($allTrips as $trip) {
    $totalfare += number_format($trip['fare'],2); 
}



$sql = "INSERT INTO Trip (userId, farePaid) VALUES (:userId, :farePaid)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userId', $userId);
$stmt->bindParam(':farePaid', $totalfare);
$stmt->execute();


$tripId = $conn->lastInsertId();


foreach ($allTrips as $trip) {
    $sql = "INSERT INTO TripPlanned (routeId, tripId) VALUES (:routeId, :tripId)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':routeId', $trip['routeId']);
    $stmt->bindParam(':tripId', $tripId);
    $stmt->execute();
}

header("Location: ../Dashboard.php");
exit();
?>
