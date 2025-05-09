<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
require_once 'includes/dbc.inc.php';

$Trips = null;
$userId = $_SESSION['userId'];
$stmt = $conn->prepare("SELECT * FROM Trip WHERE userId = :userId");
$stmt->bindParam('userId', $userId);
$stmt->execute();
if($stmt->rowCount() > 0) {
    $Trips = $stmt->fetchall(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
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
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout-button:hover {
            background-color: #ff1a1a;
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
        <span class="nav-item">JEEPS</span>
      </a>
      <a class="navbar-brand d-flex align-items-center" href="#">
              <span class="nav-item">Welcome, <?php echo $_SESSION['username']; ?>!</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link active" href="Dashboard.php">Home</a>
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
          <li class="nav-item">
            <a class="nav-link" href="includes/logout.inc.php">Log Out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <div class="container justify-content-center align-items-center" style="min-height: 200vh; min-width: 80vw;">
  <div class="header">
  <div class="table-responsive">
  <div class="text-end my-3">
  <center><br><br><h1>JEEPS TRIPS</h1></center>
    <form action="includes/generate_report.inc.php" method="post" target="_blank">
        <button type="submit" class="btn btn-success">Generate PDF Report</button>
    </form>
  </div>
      <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
          <tr>
            <th>Trip No.</th>
            <th>Jeep to Destination</th>
          </tr>
        </thead>
        <tbody>
            
          <?php 
          if($Trips != null):
            foreach($Trips as $trip): 
                $stmt = $conn->prepare("SELECT * FROM TripPlanned WHERE tripId = :tripId");
                $stmt->bindParam('tripId', $trip['tripId']);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    $Tripplanned = $stmt->fetchall(PDO::FETCH_ASSOC);
                }
                
                ?>
                <tr>
                <td><?= $trip['tripId'] ?></td>
                <td>
                    <table class="table table-bordered table-sm text-center mx-auto" style="width: auto;">
                      <?php  
                      $total = 0;
                      foreach($Tripplanned as $plan):
                          $stmt = $conn->prepare("SELECT * FROM Route WHERE routeId = :routeId");
                          $stmt->bindParam('routeId', $plan['routeId']);
                          $stmt->execute();
                          if($stmt->rowCount() > 0) {
                              $route = $stmt->fetch(PDO::FETCH_ASSOC);
                          }
                          $total += $route['fare'];
                      ?>
                      <tr>
                          <td><?= $route['routeName'] ?></td>
                          <td>₱<?= number_format($route['fare'], 2) ?></td>
                      </tr>
                      <?php endforeach; ?>
                      <tr>
                          <td colspan="2"><strong>Total Fare: ₱<?= number_format($total, 2) ?></strong></td>
                      </tr>
                    </table>
                  </td>
                </tr>
          <?php        
            endforeach; 
        endif;
        ?>
        </tbody>
      </table>
    </div>


</div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <footer class="sticky-footer minimized">
  <button class="footer-toggle-btn" id="footerToggleBtn">▲</button>
  <div class="footer-content">
    <nav class="d-flex justify-content-around py-3 bg-white w-100">
      <a href="Trip.php">
        <button class="btn btn-outline-primary" onmouseover="changeText('Plan A Trip')" onmouseout="resetText()">
            Plan A Trip
        </button>
      </a>
    </nav>
    <div class="d-flex justify-content-center my-3">
      <div class="text-container col-md-6">
        <div class="d-flex justify-content-center my-3">
          <span class="navbar-brand d-flex align-items-center" id="dynamicText">
            <span class="text-box">PLACEHOLDER</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</footer>


<script>
    const footer = document.querySelector('.sticky-footer');
    const toggleBtn = document.getElementById('footerToggleBtn');
    const footerContent = document.querySelector('.footer-content');

    toggleBtn.addEventListener('click', function() {
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
        document.getElementById('dynamicText').querySelector('.text-box').textContent = newText;
    }

    function resetText() {
        document.getElementById('dynamicText').querySelector('.text-box').textContent = newText;
    }
</script>

</div>
</body>
</html>
