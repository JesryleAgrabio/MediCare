<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $account_type = $_POST['account_type'];


    
    
    try{
        require_once "dbc.inc.php";
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email Already Exist!";
            header("Location: ../Registration.php");
            exit();
        } 

        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])(?=.*[a-zA-Z0-9]).{12,}$/';
        if (!preg_match($pattern, $password)) {
            $_SESSION['error'] = "Password must be at least 12 characters long, contain at least one uppercase letter, one lowercase letter, one special character, and one alphanumeric character.";
                header("Location: ../Registration.php");
            exit();
        } 
        // Password matching check
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Password doesn't match!";
            header("Location: ../Registration.php");
            exit();
        }

            // Hashing the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $hashedAnswer = password_hash($security_answer, PASSWORD_BCRYPT);

            // Insert the user into the database
            $sql = "INSERT INTO users (username, email, security_question, security_answer, password, account_type) VALUES (:username, :email ,:security_question, :security_answer, :password, :account_type)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':security_question', $security_question);
            $stmt->bindParam(':security_answer', $hashedAnswer);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':account_type', $account_type);
    
            if ($stmt->execute()) {
                $_SESSION['message'] = "Registered Successfully!";
                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Registration is Unsucessful!";
                header("Location: ../Registration.php");
                exit();
            }
        
    } catch (PDOException $e) {
        header("Location: ../Registration.php?error=Query Failed: " . $e->getMessage());
        exit();
    }

} else {
    header("Location: ../Registration.php");
}
