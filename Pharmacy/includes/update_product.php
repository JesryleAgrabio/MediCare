<?php
require_once "dbc.inc.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productId = intval($_POST['productId']);
    $productName = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = floatval($_POST['productPrice']);
    $stock = intval($_POST['productQuantity']);

    $imagePath = null;


    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['productImage']['tmp_name'];
        $imageName = basename($_FILES['productImage']['name']);
        $targetDir = 'uploads/products/';
        $imagePath = $targetDir . $imageName;

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        move_uploaded_file($imageTmpName, $imagePath);
    }

    try {
        if ($imagePath) {
    
            $stmt = $conn->prepare("UPDATE products SET productName = ?, productDescription = ?, productPrice = ?, productQuantity = ?, productImage = ? WHERE productId = ?");
            $stmt->execute([$productName, $description, $price, $stock, $imagePath, $productId]);
        } else {
   
            $stmt = $conn->prepare("UPDATE products SET productName = ?, productDescription = ?, productPrice = ?, productQuantity = ? WHERE productId = ?");
            $stmt->execute([$productName, $description, $price, $stock, $productId]);
        }

        header("Location: ../products.php?updated=1");
        exit();
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: ../products.php");
    exit();
}
