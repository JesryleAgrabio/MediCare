<?php
require_once "dbc.inc.php";

try {
    $puvId = isset($_POST['puvId']) ? trim($_POST['puvId']) : null;
    $plateNumber = isset($_POST['plateNumber']) ? trim($_POST['plateNumber']) : '';
    $routeId = isset($_POST['routeId']) ? intval($_POST['routeId']) : 0;

    if (empty($plateNumber) || $routeId <= 0) {
        echo "Missing required fields.";
        exit;
    }

    if (!empty($puvId)) {
        $stmt = $conn->prepare("UPDATE puv SET plateNumber = :plateNumber, routeId = :routeId WHERE puvId = :puvId");
        $stmt->execute([
            ':plateNumber' => $plateNumber,
            ':routeId' => $routeId,
            ':puvId' => $puvId
        ]);
        echo "PUV updated successfully.";
    } else {
        $stmt = $conn->prepare("INSERT INTO puv (plateNumber, routeId) VALUES (:plateNumber, :routeId)");
        $stmt->execute([
            ':plateNumber' => $plateNumber,
            ':routeId' => $routeId
        ]);
        header("Location: ../PUV.php");
        echo "PUV added successfully.";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
