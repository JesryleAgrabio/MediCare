<?php
require_once 'includes/dbc.inc.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: ../Admin/Dashboard.php?success=User deleted successfully");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    header("Location: ../Admin/Dashboard.php");
}
?>
