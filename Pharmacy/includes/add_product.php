<?php
require_once "dbc.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['productId'] ?? null;
    $userId = $_POST['userId'];
    $productName = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $stock = $_POST['productQuantity'];

    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['productImage']['tmp_name'];
        $imageName = basename($_FILES['productImage']['name']);
        $imagePath = 'uploads/products/' . $imageName;
        if (!file_exists('uploads/products')) {
            mkdir('uploads/products', 0777, true);
        }

        move_uploaded_file($imageTmpName, $imagePath);
    } else {
        $imagePath = null;
    }

   if ($productId) {
    if ($imagePath) {
        $stmt = $conn->prepare("UPDATE products SET productName = ?, productDescription = ?, productPrice = ?, productQuantity = ?, productImage = ? WHERE productId = ?");
        $stmt->execute([$productName, $description, $price, $stock, $imagePath, $productId]);
    } else {
        $stmt = $conn->prepare("UPDATE products SET productName = ?, productDescription = ?, productPrice = ?, productQuantity = ? WHERE productId = ?");
        $stmt->execute([$productName, $description, $price, $stock, $productId]);
    }
} else {
    $stmt = $conn->prepare("INSERT INTO products (productName, productDescription, productPrice, productQuantity, pharmacyId, productImage) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$productName, $description, $price, $stock, $userId, $imagePath]);
}



    header("Location: ../Products.php");
    exit();
}
?>
