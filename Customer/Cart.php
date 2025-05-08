<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.php'); 
    exit();
}

$userId = $_SESSION['userId'];
$cartItems = [];
$totalAmount = 0;
require_once 'includes/dbc.inc.php';
$stmt = $conn->prepare("SELECT c.cartId, p.productId, p.productName, p.productPrice, c.quantity, p.pharmacyId, c.cart_timestamp 
                       FROM cart c
                       JOIN products p ON c.productId = p.productId
                       WHERE c.customerId = :customerId");

$stmt->bindParam(':customerId', $userId);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cartItems as $item) {
        $totalAmount += $item['productPrice'] * $item['quantity'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove'])) {
        $cartIdToRemove = $_POST['cartId'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE cartId = :cartId AND customerId = :customerId");
        $stmt->bindParam(':cartId', $cartIdToRemove);
        $stmt->bindParam(':customerId', $userId);
        $stmt->execute();
        header("Location: cart.php");
        exit();
    } elseif (isset($_POST['update_quantity'])) {
        $cartIdToUpdate = $_POST['cartId'];
        $newQuantity = $_POST['quantity'];
        if (is_numeric($newQuantity) && $newQuantity > 0) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE cartId = :cartId AND customerId = :customerId");
            $stmt->bindParam(':quantity', $newQuantity);
            $stmt->bindParam(':cartId', $cartIdToUpdate);
            $stmt->bindParam(':customerId', $userId);
            $stmt->execute();
        }

        header("Location: cart.php");
        exit();
    } elseif (isset($_POST['confirm_order'])) {
        try {
            $conn->beginTransaction();
          foreach ($cartItems as $item) {
              $stmt = $conn->prepare("INSERT INTO orders (productId, pharmacyId, customerId, isInTransaction)
                                      VALUES (:productId, :pharmacyId, :customerId, :isInTransaction)");
              $stmt->bindParam(':productId', $item['productId']); 
              $stmt->bindParam(':pharmacyId', $item['pharmacyId']);
              $stmt->bindParam(':customerId', $userId);
              $stmt->bindValue(':isInTransaction', 1);
              $stmt->execute();
          }
            $conn->commit();
            $stmt = $conn->prepare("DELETE FROM cart WHERE customerId = :customerId");
            $stmt->bindParam(':customerId', $userId);
            $stmt->execute();
            echo "<script>window.location.href = 'cart.php';</script>";
            exit();

        } catch (Exception $e) {
            $conn->rollBack();
            echo "Failed to confirm order: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="fixed-top bg-white shadow-sm">
  <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo" width="30" height="30">
      <span class="ms-2">MediCare</span>
    </a>
    <span class="navbar-text ms-3">Welcome, <?= htmlspecialchars($_SESSION['firstname']) ?>!</span>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="Dashboard.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="Shop.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="Cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="Transaction-History.php">Transaction History</a></li>
        <li class="nav-item"><a class="nav-link" href="includes/logout.inc.php">Log Out</a></li>
      </ul>
    </div>
  </nav>
</header>
    <div class="container" style="margin-top: 80px;">
        <h2 class="text-center mb-4">Your Cart</h2>
        
        <?php if (empty($cartItems)): ?>
            <div class="alert alert-warning">Your cart is empty.</div>
        <?php else: ?>
            <form method="POST" action="cart.php">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['productName']) ?></td>
                                <td>₱<?= number_format($item['productPrice'], 2) ?></td>
                                <td>
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 70px;">
                                </td>
                                <td>₱<?= number_format($item['productPrice'] * $item['quantity'], 2) ?></td>
                                <td>
                                    <button type="submit" name="update_quantity" class="btn btn-success">Update</button>
                                    <input type="hidden" name="cartId" value="<?= $item['cartId'] ?>">
                                    <button type="submit" name="remove" class="btn btn-danger" value="Remove">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-end">
                    <h4>Total: ₱<?= number_format($totalAmount, 2) ?></h4>
                    <button type="submit" name="confirm_order" class="btn btn-primary">Confirm Order</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
