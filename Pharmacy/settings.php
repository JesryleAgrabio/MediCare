<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

?>

<div class="user-info">
            <h3>Your Information</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Account Type:</strong> <?php echo htmlspecialchars($_SESSION['account_type']); ?></p>
        </div>