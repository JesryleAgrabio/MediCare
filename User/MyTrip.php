<?php session_start(); 
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
    .total-fare {
      font-size: 1.2rem;
      font-weight: bold;
      color: #0d6efd;
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
    <span class="navbar-text ms-auto me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
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
  <h2 class="text-center mb-4">MyTrip</h2>
  <div class="table-responsive">
      <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
          <tr>
            <th>Trip No.</th>
            <th>Jeep to Destination</th>
            <th>Trip Status</th>
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
                  <div class="text-center">
                    <table class="table table-centered">

                    <?php foreach($Tripplanned as $plan): 
                        $stmt = $conn->prepare("SELECT * FROM Route WHERE routeId = :routeId");
                        $stmt->bindParam('routeId', $plan['routeId']);
                        $stmt->execute();
                        if($stmt->rowCount() > 0) {
                            $route = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                        
                        ?>
                        <tr>
                            <td><?=$route['routeName']?></td>
                        <td>₱<?=number_format($route['fare'],2)?></td>
                        </tr>
            
                    <?php endforeach; ?>
                    </table>
                    </center>
                </td>
                <td>
                    <button>Done</button>
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
</body>
</html>