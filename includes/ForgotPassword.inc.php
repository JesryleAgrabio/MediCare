<?php
session_start();
require_once "dbc.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_otp'])) {
    $email = $_POST['email'];
    $recovery_method = $_POST['recovery_method'];
    $_SESSION['recovery_method'] = $recovery_method; 

    if ($recovery_method == "otp"){
    $_SESSION['otp'] = rand(100000, 999999); 
    $_SESSION['email'] = $email;
    $_SESSION['otp_expiry'] = time() + 120; 
    $_SESSION['message'] = "OTP has been sent to your email!";
    header("Location: ../ForgotPassword.php");
    }
    elseif($recovery_method == "security_question"){


        $stmt = $conn->prepare("SELECT security_question, security_answer FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $security = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['securityQuestion'] = $security["security_question"]; 
            $_SESSION['answer'] = $security["security_answer"]; 
            $_SESSION['email'] = $email;
            $_SESSION['message'] = "Security Question Revealed!";
            header("Location: ../ForgotPassword.php");
        }
    }   
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    if (time() > $_SESSION['otp_expiry']) {
       
        unset($_SESSION['otp']); 
        unset($_SESSION['otp_expiry']); 
        $_SESSION['error'] = "OTP has expired. Please request a new one.";
        header("Location: ../ForgotPassword.php");
        exit();
    }

    if ($_POST['otp'] == $_SESSION['otp']) {
        $_SESSION['otp_verified'] = true;
        $_SESSION['message'] = "OTP Verified! You can now change your password.";
        header("Location: ../ForgotPassword.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid OTP. Please try again.";
        header("Location: ../ForgotPassword.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_answer'])) {
    
   
    if (password_verify($_POST['answer'],$_SESSION['answer'])) {
        $_SESSION['answer_verified'] = true;
        $_SESSION['message'] = "Answer Verified! You can now change your password.";
        header("Location: ../ForgotPassword.php");
        exit();
    } else {
        $_SESSION['error'] = "Incorrect Answer!. Please try again.";
        header("Location: ../ForgotPassword.php");
        exit();
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $email = $_SESSION['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])(?=.*[a-zA-Z0-9]).{12,}$/';

    if (!preg_match($pattern, $password)) {
        $_SESSION['error'] = "Password must be at least 12 characters long, contain at least one uppercase letter, one lowercase letter, one special character, and one alphanumeric character.";
        header("Location: ../ForgotPassword.php");
        exit();
    }

   
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../ForgotPassword.php");
        exit();
    }

    if ($_SESSION['otp_verified'] || $_SESSION['answer_verified'])  {
        $new_password = $_POST['password'];
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

       
        unset($_SESSION['otp']);
        unset($_SESSION['otp_verified']);
        unset($_SESSION['email']);
        unset($_SESSION['otp_expiry']);
        unset($_SESSION['recovery_method']);
        unset($_SESSION['securityQuestion']);
        unset($_SESSION['answer']);
        unset($_SESSION['answer_verified']);
        $_SESSION['message'] = "Password successfully changed!";
        header("Location: ../index.php");
        exit();  
    } else {
        $_SESSION['error'] = "You need to verify first!";
        header("Location: ../ForgotPassword.php");
        exit();
    }
}
