<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JEEP Moderator Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 80px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .text-container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin: 20px 0;
        }
        .user-info {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .sticky-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px;
            border-top: 1px solid #ddd;
            z-index: 100;
            transition: height 0.3s ease;
        }
        .sticky-footer.minimized {
            height: 50px;
            overflow: visible;
        }
        .sticky-footer.expanded {
            height: 300px;
        }
        .footer-toggle-btn {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -25px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .footer-content {
            display: none;
        }
        .footer-content.show {
            display: block;
        }
    </style>
</head>
<body>
<div class="bg-white">
  <header class="fixed-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Logo" width="30" height="30">
        <span class="ms-2">JEEPS</span>
      </a>
      <span class="navbar-text ms-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Moderator)</span>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="Dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="routes.php">Route List</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="jeeps.php">Jeep List</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="busStops.php">Bus Stops</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="includes/logout.inc.php">Log Out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="text-center">
      <h1 class="mb-4">Moderator Dashboard</h1>
      <div class="user-info">
        <p>Use the navigation bar above to manage:</p>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><a href="Route.php">Manage Jeep Routes</a></li>
          <li class="list-group-item"><a href="PUV.php">Manage Public Utility Vehicles</a></li>
          <li class="list-group-item"><a href="busStops.php">Manage Bus Stops </a></li>
        </ul>
      </div>
    </div>
  </div>

  <footer class="sticky-footer minimized">
    <button class="footer-toggle-btn" id="footerToggleBtn">▲</button>
    <div class="footer-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white container-fluid py-3">
            <div class="d-flex justify-content-around w-100">
                <button class="btn btn-outline-primary" onmouseover="changeText('Search Routes')" onmouseout="resetText()">
                    <img src="https://via.placeholder.com/30" alt="Search Routes">
                </button>
                <button class="btn btn-outline-success" onmouseover="changeText('Fare Chart')" onmouseout="resetText()">
                    <img src="https://via.placeholder.com/30" alt="Fare Chart">
                </button>
                <button class="btn btn-outline-warning" onmouseover="changeText('Schedule Chart')" onmouseout="resetText()">
                    <img src="https://via.placeholder.com/30" alt="Schedule Chart">
                </button>
                <button class="btn btn-outline-danger" onmouseover="changeText('Search History')" onmouseout="resetText()">
                    <img src="https://via.placeholder.com/30" alt="Search History">
                </button>
                <button class="btn btn-outline-danger" onmouseover="changeText('Report PUV')" onmouseout="resetText()">
                    <img src="https://via.placeholder.com/30" alt="Report PUV">
                </button>
            </div>
        </nav>
        <div class="d-flex justify-content-center my-3">
            <div class="text-container col-md-6 text-center py-3">
                <span class="text-box" id="dynamicText">PLACEHOLDER</span>
            </div>
        </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const footer = document.querySelector('.sticky-footer');
    const toggleBtn = document.getElementById('footerToggleBtn');
    const footerContent = document.querySelector('.footer-content');
    const dynamicText = document.getElementById('dynamicText');

    toggleBtn.addEventListener('click', function () {
        if (footer.classList.contains('minimized')) {
            footer.classList.remove('minimized');
            footer.classList.add('expanded');
            toggleBtn.innerText = '▼';
            footerContent.classList.add('show');
        } else {
            footer.classList.remove('expanded');
            footer.classList.add('minimized');
            toggleBtn.innerText = '▲';
            footerContent.classList.remove('show');
        }
    });

    function changeText(newText) {
        dynamicText.textContent = newText;
    }

    function resetText() {
        dynamicText.textContent = "PLACEHOLDER";
    }
  </script>
</div>
</body>
</html>
