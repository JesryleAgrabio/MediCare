<?php
require_once "dbc.inc.php";

try {
    if (!isset($_POST['routeId']) || empty($_POST['routeId'])) {
        echo "Route ID is required for deletion.";
        exit;
    }

    $routeId = $_POST['routeId'];

    $stmt = $conn->prepare("DELETE FROM route WHERE routeId = :routeId");
    $stmt->bindParam(':routeId', $routeId);

    if ($stmt->execute()) {
        echo "Route deleted successfully.";
        header("Location: ../Route.php");
        exit();
    } else {
        echo "Failed to delete route.";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
