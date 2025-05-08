<?php
session_start();
require_once 'dbc.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['productId'], $_POST['quantity']) || !is_numeric($_POST['productId']) || !is_numeric($_POST['quantity'])) {
    header("Location: Shop.php?error=invalid_input");
    exit();
}

$productId = (int) $_POST['productId'];
$quantity = (int) $_POST['quantity'];

if ($quantity <= 0) {
    header("Location: Shop.php?error=invalid_quantity");
    exit();
}
$stmt = $conn->prepare("SELECT * FROM products WHERE productId = :productId");
$stmt->bindParam(':productId', $productId);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    header("Location: Shop.php?error=product_not_found");
    exit();
}

$product = $stmt->fetch(PDO::FETCH_ASSOC);

$userId = $_SESSION['userId'];
$stmt = $conn->prepare("SELECT * FROM cart WHERE productId = :productId AND customerId = :userId");
$stmt->bindParam(':productId', $productId);
$stmt->bindParam(':userId', $userId);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $stmt = $conn->prepare("UPDATE cart SET cart_timestamp = NOW(), quantity = quantity + :quantity WHERE productId = :productId AND customerId = :userId");
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':productId', $productId);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
} else {
    $stmt = $conn->prepare("INSERT INTO cart (productId, customerId, cart_timestamp, quantity) VALUES (:productId, :userId, NOW(), :quantity)");
    $stmt->bindParam(':productId', $productId);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
}

header("Location: ../Shop.php?success=added_to_cart");
exit();
?>
