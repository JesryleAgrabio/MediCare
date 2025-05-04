<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JEEPS - Login</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }
        .login-container {
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
            <a class="nav-link active" href="#">Home</a>
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

  <div class="container-fluid text-center px-4 py-5">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="login-container col-md-6 text-center">
        <h2>LOGIN</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

       
<form method="POST" action="includes/login.inc.php">
  <div class="mb-3">
    <div class="row align-items-center">
      <div class="col-3 text-start">
        <label for="email" class="form-label">Email:</label>
      </div>
      <div class="col-9">
        <input type="email" name="email" class="form-control" required>
      </div>
    </div>
  </div>

  <div class="mb-3">
    <div class="row align-items-center">
      <div class="col-3 text-start">
        <label for="password" class="form-label">Password:</label>
      </div>
      <div class="col-9">
        <input type="password" name="password" class="form-control" required>
      </div>
    </div>
  </div>

 
  <div class="d-flex justify-content-between">
    <div class="col me-2">
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </div>
    <div class="col ms-2">
      <button type="button" onclick="window.location.href='Registration.php'" class="btn btn-secondary w-100">Register</button>
    </div>
  </div>

  <br>

  
  <div>
    <button type="button" onclick="window.location.href='ForgotPassword.php'" class="btn w-100 text-white" style="background-color: orange;">Forgot Password?</button>
  </div>
</form>

      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="mb-3">
          <h1 class="display-4 fw-bold">Efficient Journeys, One Click Away</h1>
          <p class="lead my-4 text-muted">With JEEPS, you can find and monitor PUV routes on the go, ensuring you reach your destination faster and more efficiently. Plan ahead, skip the wait, and save time.</p>
        </div>
      </div>
    </div>
  </div>

 
  <div class="position-relative overflow-hidden">
    <div class="position-absolute top-0 start-50 translate-middle-x" style="width: 100%; height: 300px; background: linear-gradient(to right, #ff80b5, #9089fc); opacity: 0.3; clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
