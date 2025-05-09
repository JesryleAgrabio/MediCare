<?php
require_once "dbc.inc.php";

try {
    if (!isset($_POST['puvId']) || empty($_POST['puvId'])) {
        echo "PUV ID is required for deletion.";
        exit;
    }

    $puvId = $_POST['puvId'];

    $stmt = $conn->prepare("DELETE FROM puv WHERE puvId = :puvId");
    $stmt->bindParam(':puvId', $puvId);

    if ($stmt->execute()) {
        echo "PUV deleted successfully.";
        header("Location: ../PUV.php");
    } else {
        echo "Failed to delete PUV.";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
