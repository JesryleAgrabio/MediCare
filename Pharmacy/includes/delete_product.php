<?php
require_once "dbc.inc.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['productId'])) {
    $productId = intval($_POST['productId']);

    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE productId = ?");
        $stmt->execute([$productId]);
        header("Location: ../products.php?deleted=1");
        exit();
    } catch (PDOException $e) {
        echo "Error deleting product: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: ../products.php");
    exit();
}
