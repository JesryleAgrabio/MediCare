<?php
session_start();
require_once "includes/dbc.inc.php";
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

$query = $conn->query("
    SELECT 
        o.orderId,
        p.productName,
        u1.firstname AS pharmacyFirstName,
        u1.lastname AS pharmacyLastName,
        u2.firstname AS customerFirstName,
        u2.lastname AS customerLastName,
        o.isInTransaction,
        o.order_timestamp
    FROM orders o
    LEFT JOIN products p ON o.productId = p.productId
    LEFT JOIN users u1 ON o.pharmacyId = u1.id
    LEFT JOIN users u2 ON o.customerId = u2.id
    WHERE o.isInTransaction = 1
    ORDER BY o.order_timestamp DESC
");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JEEP Moderator Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 80px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .text-container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin: 20px 0;
        }
        .user-info {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
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
            overflow: visible;
        }
        .sticky-footer.expanded {
            height: 300px;
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
<div class="bg-white">
 <header class="fixed-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo" width="30" height="30">
        <span class="ms-2">Medicare</span>
      </a>
      <span class="navbar-text ms-3">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?> (Pharmacy)</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="Dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Products.php">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Orders.php">Orders</a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="Transaction-History.php">Transaction History</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="includes/logout.inc.php">Log Out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="text-center">
      <h1 class="mb-4">Pharmacy Dashboard</h1>
    <h2 class="mb-4">Order Manager</h2>

    <table class='table table-bordered'>
        <thead class="table-light">
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Customer</th>
                <th>Ordered At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($res as $order): ?>
            <tr>
                <td><?php echo $order['orderId']; ?></td>
                <td><?php echo htmlspecialchars($order['productName']); ?></td>
                <td><?php echo htmlspecialchars($order['customerFirstName'] . ' ' . $order['customerLastName']); ?></td>
                <td><?php echo $order['order_timestamp']; ?></td>
                <td>
                    <form action="includes/handle_order_action.php" method="POST" onsubmit="return confirm('Are you sure you want to take this action?');">
                        <input type="hidden" name="orderId" value="<?php echo $order['orderId']; ?>">
                        <button type="submit" name="action" value="confirm" class="btn btn-success btn-sm">Confirm</button>
                        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
  </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const footer = document.querySelector('.sticky-footer');
    const toggleBtn = document.getElementById('footerToggleBtn');
    const footerContent = document.querySelector('.footer-content');
    const dynamicText = document.getElementById('dynamicText');

    toggleBtn.addEventListener('click', function () {
        if (footer.classList.contains('minimized')) {
            footer.classList.remove('minimized');
            footer.classList.add('expanded');
            toggleBtn.innerText = '▼';
            footerContent.classList.add('show');
        } else {
            footer.classList.remove('expanded');
            footer.classList.add('minimized');
            toggleBtn.innerText = '▲';
            footerContent.classList.remove('show');
        }
    });

    function changeText(newText) {
        dynamicText.textContent = newText;
    }

    function resetText() {
        dynamicText.textContent = "PLACEHOLDER";
    }
  </script>
</div>
</body>
</html>
