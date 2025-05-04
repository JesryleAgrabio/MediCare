<?php session_start(); 
    $puv = $_SESSION['puvs'];
    $total = 0;
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
  <h2 class="text-center mb-4">Trip Found!</h2>
  
  <form method="POST" action="includes/save_trip.inc.php">
    <div class="table-responsive">
      <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
          <tr>
            <th>Route Destination</th>
            <th>Fare</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($puv as $index => $trip): 
            $total += number_format($trip['fare'], 2);
            ?>
          
            <tr>
              <td><?= htmlspecialchars($trip['routeName']) ?></td>
              <td>₱<?= number_format($trip['fare'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="text-center my-3">
      <div class="total-fare">Total Fare: ₱<?=number_format($total,2)?></div>
    </div>

    <div class="text-center mt-2">
      <button type="submit" class="btn btn-primary">Add to MyJeeps</button>
    </div>
  </form>
</div>

<script>
  const checkboxes = document.querySelectorAll('.fare-checkbox');
  const totalFareDisplay = document.getElementById('totalFare');

  function updateTotalFare() {
    let total = 0;
    checkboxes.forEach(cb => {
      if (cb.checked) {
        $selectedtrips[] = 
        total += parseFloat(cb.dataset.fare);
      }
    });
    totalFareDisplay.textContent = total.toFixed(2);
  }

  checkboxes.forEach(cb => {
    cb.addEventListener('change', updateTotalFare);
  });


  updateTotalFare();
</script>

</body>
</html>
