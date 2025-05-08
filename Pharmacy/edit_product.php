<?php
require_once "includes/dbc.inc.php";
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}
if (!isset($_GET['productId'])) {
    header("Location: products.php");
    exit();
}

$productId = intval($_GET['productId']);
$stmt = $conn->prepare("SELECT * FROM products WHERE productId = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">Pharmacy/Dashboard.php
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
    <h2>Edit Product</h2>

   <form action="includes/update_product.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="productId" value="<?= $product['productId'] ?>">

    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="productName" class="form-control" value="<?= htmlspecialchars($product['productName']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="productDescription" class="form-control" required><?= htmlspecialchars($product['productDescription']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" name="productPrice" step="0.01" class="form-control" value="<?= $product['productPrice'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Stock</label>
        <input type="number" name="productQuantity" class="form-control" value="<?= $product['productQuantity'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Product Image (optional)</label>
        <input type="file" name="productImage" class="form-control">
        <?php if (!empty($product['productImage'])): ?>
            <img src="<?= htmlspecialchars($product['productImage']) ?>" alt="Current Image" style="width: 100px; height: auto; margin-top: 10px;">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="products.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
