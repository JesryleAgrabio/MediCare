<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
       
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .forgot-container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004a99;
        }
    </style>
</head>
<body> 
    <header class="fixed-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
     
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo" width="30" height="30">
        <span class="nav-item">JEEPS</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
     
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Service</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
    
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="forgot-container col-md-6">
            <div class="container">
                <h2 class="text-center">Forgot Password</h2>

                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

            
                <?php if (!isset($_SESSION['otp']) && !isset($_SESSION['securityQuestion'])): ?>
                    <form method="POST" action="includes/ForgotPassword.inc.php" class="mt-4">
                        <div class="form-group">
                            <label for="email">Enter your email:</label>
                            <input type="email" name="email" class="form-control" required>
                            <label>Select a recovery method:</label><br>
                            <input type="radio" id="otp" name="recovery_method" value="otp" required>
                            <label for="otp">Send OTP to Email</label><br>
                            <input type="radio" id="security_question" name="recovery_method" value="security_question" required>
                            <label for="security_question">Answer Security Question</label>
                        </div><br>
                        <button type="submit" name="request_otp" class="btn btn-primary w-100">Send OTP</button>
                    </form>
                    <?php endif; ?>

            <?php if (isset($_SESSION['recovery_method'])): ?>            
                <?php if ($_SESSION['recovery_method'] == "otp"): ?>
                    <?php if (isset($_SESSION['otp']) && !isset($_SESSION['otp_verified'])): ?>
                        <div class="alert alert-info mt-4" role="alert">
                        Your OTP is: <strong><?php echo $_SESSION['otp']; ?></strong> 
                        </div>
                    <form method="POST" action="includes/ForgotPassword.inc.php" class="mt-4">
                            <div class="form-group">
                                <label for="otp">Enter OTP:</label>
                                <input type="number" name="otp" class="form-control" required>
                            </div><br>
                            <button type="submit" name="verify_otp" class="btn btn-primary w-100">Verify OTP</button>
                        </form>
                    <?php endif; ?>

                
                
            

            
                <?php if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']): ?>
                    <form method="POST" action="includes/ForgotPassword.inc.php" class="mt-4">
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div><br>
                        <button type="submit" name="change_password" class="btn btn-primary w-100">Change Password</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($_SESSION['recovery_method'] == "security_question"): ?>
                <?php if (isset($_SESSION['securityQuestion']) && !isset($_SESSION['answer_verified'])): ?>
                        <div class="alert alert-info mt-4" role="alert">
                        Your Security Question is: <strong><?php echo $_SESSION['securityQuestion']; ?></strong> 
                        </div>
                    <form method="POST" action="includes/ForgotPassword.inc.php" class="mt-4">
                            <div class="form-group">
                                <label for="otp"><?php echo $_SESSION['securityQuestion']; ?></label>
                                <input type="text" name="answer" class="form-control" required>
                            </div><br>
                            <button type="submit" name="verify_answer" class="btn btn-primary w-100">Verify OTP</button>
                        </form>
                    <?php endif; ?>

                
                
            

            
                <?php if (isset($_SESSION['answer_verified']) && $_SESSION['answer_verified']): ?>
                    <form method="POST" action="includes/ForgotPassword.inc.php" class="mt-4">
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div><br>
                        <button type="submit" name="change_password" class="btn btn-primary w-100">Change Password</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php endif; ?>
        <?php endif; ?>            
        </div>
        </div>
    </div>
   </body>
</html>
