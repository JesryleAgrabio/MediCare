<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Plan Your Trip</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .container {
      margin-top: 80px;
    }
    .trip-summary {
      margin-top: 30px;
    }
    #loadingSpinner {
      display: none;
    }
  </style>
</head>
<body>

<header class="fixed-top bg-white shadow-sm">
  <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo" width="30" height="30">
      <span class="nav-item ms-2">JEEPS</span>
    </a>
    <span class="navbar-text ms-auto me-3">Welcome, <?php echo $_SESSION['username']; ?>!</span>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="Dashboard.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Service</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="includes/logout.inc.php">Log Out</a></li>
      </ul>
    </div>
  </nav>
</header>

<div class="container">
  <h2 class="text-center">Plan Your Trip</h2>

  <form id="tripForm" method="POST" action = "includes/trip.inc.php">
    <div class="mb-3">
      <label for="startLocation" class="form-label">Start Location</label>
      <input type="text" class="form-control" id="startLocation" name="startLocation" required>
    </div>
    <div class="mb-3">
      <label for="endLocation" class="form-label">End Location</label>
      <input type="text" class="form-control" id="endLocation" name="endLocation" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Find Available Routes</button>
  </form>

</body>
</html>
