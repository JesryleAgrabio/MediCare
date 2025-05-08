<?php
session_start();
require_once 'includes/dbc.inc.php';
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}
$searchTerm = $_GET['search'] ?? '';
$products = [];

if (!empty($searchTerm)) {
    $stmt = $conn->prepare("SELECT * FROM Products WHERE name LIKE :search");
    $stmt->bindValue(':search', '%' . $searchTerm . '%');
} else {
    $stmt = $conn->prepare("SELECT * FROM Products");
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .container {
      margin-top: 80px;
    }
    .card {
      margin-bottom: 20px;
    }
    .search-bar {
      max-width: 500px;
      margin: 0 auto 30px auto;
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

<div class="container">
  <h2 class="text-center mb-4">Browse Products</h2>

  <form method="GET" class="search-bar d-flex">
    <input class="form-control me-2" type="search" name="search" placeholder="Search products..." value="<?= htmlspecialchars($searchTerm) ?>">
    <button class="btn btn-outline-primary" type="submit">Search</button>
  </form>

  <div class="row">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($product['productImage'])): ?>
                        <img src="../Pharmacy/includes/<?= htmlspecialchars($product['productImage']); ?>" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/200x200?text=No+Image" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['productName']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($product['productDescription']) ?></p>
                        <p class="card-text fw-bold">₱<?= number_format($product['productPrice'], 2) ?></p>

            
                        <form action="includes/add_to_cart.php" method="POST" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="productId" value="<?= $product['productId'] ?>">
                            <input type="number" name="quantity" min="1" value="1" class="form-control" style="width: 80px;" required>
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center">
            <p>No products found for "<?= htmlspecialchars($searchTerm) ?>".</p>
        </div>
    <?php endif; ?>
</div>


</body>
</html>
