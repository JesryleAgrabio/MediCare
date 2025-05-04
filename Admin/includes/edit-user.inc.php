<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $account_type = $_POST['account_type'];

    require_once "dbc.inc.php";
    $sql = "UPDATE users SET username = :username, email = :email, account_type = :account_type WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':account_type', $account_type);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "USER UPDATED SUCCCESSFULLY!";
        header("Location: ../Dashboard.php");
        exit();
    } else {
        echo "Error updating user.";
    }
}


?>