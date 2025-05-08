<?php   
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}
require_once "includes/dbc.inc.php";

$query = $conn->prepare("
    SELECT 
        th.transactionId,
        o.orderId,
        p.productName,
        u1.firstname AS pharmacyFirstName,
        u1.lastname AS pharmacyLastName,
        u2.firstname AS customerFirstName,
        u2.lastname AS customerLastName,
        th.status,
        th.action_timestamp
    FROM transactionHistory th
    LEFT JOIN orders o ON th.orderId = o.orderId
    LEFT JOIN products p ON o.productId = p.productId
    LEFT JOIN users u1 ON o.pharmacyId = u1.id
    LEFT JOIN users u2 ON o.customerId = u2.id
    WHERE o.pharmacyId = :userId
    ORDER BY th.action_timestamp DESC
");
$query->bindParam(':userId', $_SESSION['userId']);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
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

  <div class="container" style="margin-top: 100px;">
    <h2 class="mb-4">Transaction History</h2>

    
    <a href="includes/generate_report.php" class="btn btn-primary mb-4">Generate Report (PDF)</a>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Transaction ID</th>
                <th>Order ID</th>
                <th>Product</th>
                <th>Pharmacy</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Action Timestamp</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($res) > 0): ?>
            <?php foreach ($res as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['transactionId']; ?></td>
                    <td><?php echo $transaction['orderId']; ?></td>
                    <td><?php echo htmlspecialchars($transaction['productName']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['pharmacyFirstName'] . ' ' . $transaction['pharmacyLastName']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['customerFirstName'] . ' ' . $transaction['customerLastName']); ?></td>
                    <td>
                        <?php 
                            if ($transaction['status'] == 'success') {
                                echo '<span class="badge bg-success">Success</span>';
                            } elseif ($transaction['status'] == 'rejected') {
                                echo '<span class="badge bg-danger">Rejected</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Pending</span>';
                            }
                        ?>
                    </td>
                    <td><?php echo $transaction['action_timestamp']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No transactions found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
  </div>
</div>
</body>
</html>
