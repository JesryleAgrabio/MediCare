<?php
require_once "includes/dbc.inc.php";
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}
$pharmacyQuery = $conn->query("SELECT id, firstname, lastname FROM users WHERE account_type = 'pharmacy'");
$pharmacies = $pharmacyQuery->fetchAll(PDO::FETCH_ASSOC);
$userId = $_SESSION['userId'];
$productQuery = $conn->prepare("SELECT p.*, u.firstname, u.lastname FROM products p LEFT JOIN users u ON p.pharmacyId = u.id WHERE p.pharmacyId = ?");
$productQuery->execute([$userId]);
$products = $productQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
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
    <h2>Product Manager</h2>

   <form id="productForm" method="POST" action="includes/add_product.php" enctype="multipart/form-data" class="mb-4">

        <input type="hidden" name="productId" id="productId">
        <input type="hidden" name="userId" value="<?= $_SESSION['userId']; ?>">

        <div class="row g-2">
            <div class="col-md-3"><input type="text" name="productName" class="form-control" placeholder="Product Name" required></div>
            <div class="col-md-3"><input type="text" name="productDescription" class="form-control" placeholder="Description" required></div>
            <div class="col-md-2"><input type="number" name="productPrice" class="form-control" step="0.01" placeholder="Price" required></div>
            <div class="col-md-2"><input type="number" name="productQuantity" class="form-control" placeholder="Stock" required></div>
            <div class="col-md-2"><input type="file" name="productImage" class="form-control"></div>
        </div>

        <div class="col-md-2"><button type="submit" class="btn btn-success w-100">Save Product</button></div>
    </form>

    <h4>Product Table</h4>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Pharmacy</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td><?= $product['productId']; ?></td>
                <td>
                  <?php if (!empty($product['productImage'])): ?>
                      <img src="includes/<?= htmlspecialchars($product['productImage']); ?>" alt="Product Image"  style="height: 200px; object-fit: cover;">
                  <?php else: ?>
                      No image
                  <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($product['productName']); ?></td>
                <td><?= htmlspecialchars($product['productDescription']); ?></td>
                <td><?= htmlspecialchars($product['firstname'] . ' ' . $product['lastname']); ?></td>
                <td>₱<?= number_format($product['productPrice'], 2); ?></td>
                <td><?= $product['productQuantity']; ?></td>
                <td>
                    <form action="includes/delete_product.php" method="POST" style="display:inline;">
                        <input type="hidden" name="productId" value="<?= $product['productId'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <a href="edit_product.php?productId=<?= $product['productId'] ?>" class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
</div>
</body>
</html>
