<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'dbc.inc.php';


if (isset($_POST['orderId']) && isset($_POST['action'])) {
    $orderId = $_POST['orderId'];
    $action = $_POST['action'];

    try {
  
        $conn->beginTransaction();

    
        $stmt = $conn->prepare("SELECT * FROM orders WHERE orderId = :orderId");
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
    
            $stmt = $conn->prepare("INSERT INTO transactionHistory (orderId, productId, customerId, pharmacyId, status, action_timestamp) 
                                    VALUES (:orderId, :productId, :customerId, :pharmacyId, :status, NOW())");

            $stmt->bindParam(':orderId', $order['orderId']);
            $stmt->bindParam(':productId', $order['productId']);
            $stmt->bindParam(':customerId', $order['customerId']);
            $stmt->bindParam(':pharmacyId', $order['pharmacyId']);

        
            if ($action == 'confirm') {
                $status = 'success';
            } elseif ($action == 'delete') {
                $status = 'rejected';
            } else {
                throw new Exception('Invalid action');
            }

            $stmt->bindParam(':status', $status);
            $stmt->execute();

       
            $stmt = $conn->prepare("UPDATE orders SET isInTransaction = 0 WHERE orderId = :orderId");
            $stmt->bindParam(':orderId', $orderId);
            $stmt->execute();

     
            $conn->commit();

    
            header("Location: ../Orders.php");
            exit();
        } else {
            throw new Exception('Order not found.');
        }
    } catch (Exception $e) {
   
        $conn->rollBack();
        echo "Failed to process action: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
