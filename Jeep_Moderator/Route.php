<?php   
session_start();
require_once "includes/dbc.inc.php";
$query = $conn->query("SELECT * FROM Route");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Routes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
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
            <a class="nav-link" href="Route.php">Route List</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="PUV.php">Jeep List</a>
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
    <div>
  
    <h2>Route Manager</h2>

    <form id="routeForm" action="includes/add_edit_routes.php"class="mb-4" method="POST">
        <div class="row g-2">
            <div class="col-md-3"><input type="text" name="routeName" class="form-control" placeholder="Route Name" required></div>
            <div class="col-md-3"><input type="text" name="startLocation" class="form-control" placeholder="Start" required></div>
            <div class="col-md-3"><input type="text" name="endLocation" class="form-control" placeholder="End" required></div>
            <div class="col-md-3"><input type="text" name="stops" class="form-control" placeholder="Stops (comma-separated)" required></div>
            <div class="col-md-2"><input type="number" name="distanceKm" step="0.1" class="form-control" placeholder="Distance" required></div>
            <div class="col-md-2"><input type="number" name="fare" step="0.1" class="form-control" placeholder="Fare" required></div>
            <div class="col-md-2"><input type="number" name="estimatedTime" class="form-control" placeholder="Est. Time" required></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Save Route</button></div>
        </div>
    </form>

    <h4>Routes Table</h4>
    <div id="routesTable"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <table class='table table-bordered'>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Start</th>
            <th>End</th>
            <th>Stops</th>
            <th>Fare</th>
            <th>Action</th>
        </tr>
        <?php foreach($res as $r):?>
        <tr>   
            <td><?php echo $r['routeId']; ?></td>
            <td><?php echo $r['routeName']; ?></td>
            <td><?php echo $r['startLocation']; ?></td>
            <td><?php echo $r['endLocation']; ?></td>
            <td><?php echo $r['stops']; ?></td>
            <td><?php echo $r['fare']; ?></td>
            <td>  
              <form action="includes/delete_route.php" method="POST" style="display:inline;">
                            <input type="hidden" name="routeId" value="<?= $r['routeId'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
            </td>
        </tr>
        <?php endforeach;?>
    <table>
    </div>
</body>
</html>
