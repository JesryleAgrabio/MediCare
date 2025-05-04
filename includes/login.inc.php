<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        require_once "dbc.inc.php";

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $storedHashedPassword = $user['password'];
    
            if (password_verify($password, $storedHashedPassword)) {
               
                $_SESSION['username'] = $user['username'];
                $_SESSION['userId'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['account_type'] = $user['account_type'];
                $_SESSION['message'] = "Login successful!";  
                $_SESSION['message_type'] = "success"; 

                if($_SESSION['account_type'] == "admin"){ 
                    header("Location: ../Admin/Dashboard.php");
                    exit();
                }
                if($_SESSION['account_type'] == "user"){ 
                    header("Location: ../User/Dashboard.php");
                    exit();
                }
                if($_SESSION['account_type'] == "jeep moderator"){ 
                    header("Location: ../Jeep_Moderator/Dashboard.php");
                    exit();
                }
            } else {
                
                $_SESSION['message'] = "Incorrect password.";
                $_SESSION['message_type'] = "error"; 
                header("Location: ../index.php");
                exit();
            }
        } else {
            
            $_SESSION['message'] = "No account found with that email.";
            $_SESSION['message_type'] = "error"; 
            header("Location: ../index.php");
            exit();
        }     
    } catch(PDOException $e) {
        $_SESSION['message'] = "Query Failed: " . $e->getMessage();
        $_SESSION['message_type'] = "error"; 
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
