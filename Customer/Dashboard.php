<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'includes/dbc.inc.php';

$userId = $_SESSION['userId'];

$stmt = $conn->prepare("
    SELECT 
        o.orderId, o.order_timestamp, o.productId,
        p.productName, p.productPrice,
        1 AS quantity,
        (
            SELECT t.status 
            FROM transactionHistory t 
            WHERE t.orderId = o.orderId 
            ORDER BY t.action_timestamp DESC 
            LIMIT 1
        ) AS latestStatus
    FROM orders o
    LEFT JOIN products p ON o.productId = p.productId
    WHERE o.customerId = :userId
");

$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Dashboard - MediCare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        font-family: Arial, sans-serif;
        padding-top: 80px;
    }
    .container {
        max-width: 90%;
        margin: 0 auto;
    }
    .header {
        text-align: center;
        margin: 30px 0;
    }
    .sticky-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #f8f9fa;
        padding: 10px;
        border-top: 1px solid #ddd;
        z-index: 100;
        transition: height 0.3s ease;
    }
    .sticky-footer.minimized {
        height: 50px;
    }
    .sticky-footer.expanded {
        height: 200px;
    }
    .footer-toggle-btn {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: -25px;
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }
    .footer-content {
        display: none;
    }
    .footer-content.show {
        display: block;
    }
  </style>
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

<div class="container mt-5">
  <div class="header">
    <h3>Your Orders</h3>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>Order ID</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Status</th>
          <th>Order Date</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= htmlspecialchars($order['orderId']) ?></td>
              <td><?= htmlspecialchars($order['productName'] ?? 'Product not found') ?></td>
              <td><?= htmlspecialchars($order['quantity']) ?></td>
              <td><?= htmlspecialchars($order['latestStatus'] ?? 'Pending') ?></td>
              <td><?= date('Y-m-d H:i', strtotime($order['order_timestamp'])) ?></td>
              <td>₱<?= number_format(($order['productPrice'] ?? 0) * $order['quantity'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
