<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];
    $id = $_POST['id']; 

    require_once "dbc.inc.php";

    $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, account_type = :account_type WHERE id = :id"; 
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':account_type', $account_type);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "User updated successfully!";
        header("Location: ../Dashboard.php"); 
        exit();
    } else {
        echo "Error updating user.";
    }
} else {
    echo "Invalid request method.";
}
?>
