<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JEEPS - Registration</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .registration-container {
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
    <div class="bg-white">
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
        
        <div class="registration-container col-md-6">
            <h2 class="text-center mb-4">Register</h2>

           
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

           
            <form method="POST" action="includes/add-Registration.inc.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="security_question" class="form-label">Security Question:</label>
                    <input type="text" name="security_question" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="security_answer" class="form-label">Security Answer:</label>
                    <input type="text" name="security_answer" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="account_type" class="form-label">Account Type:</label>
                    <select name="account_type" class="form-select">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="jeep moderator">Jeep Moderator</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
           
        </div>
    </div>

   
</body>
</html>
