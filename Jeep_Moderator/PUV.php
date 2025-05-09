<?php require_once "includes/dbc.inc.php";
    session_start();
    $query = "
    SELECT * FROM Route ";
$stmt = $conn->prepare($query);
$stmt->execute();
$routes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = $conn->query("SELECT * FROM PUV");
$puvs = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Manage PUVs</title>
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
      <span class="navbar-text ms-3">Welcome, <?php echo $_SESSION['username']; ?> (Moderator)</span>
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

  <div class="container d-flex justify-content-center align-items-center" style="min-height: 120vh;">
    <div>
    <h2>PUV Manager</h2>

    <form id="puvForm" method="POST" action="includes/add_puvs.php" class="mb-4">
        <input type="hidden" name="puvId" id="puvId">
        <div class="row g-2">
            <div class="col-md-4"><input type="text" name="plateNumber" class="form-control" placeholder="Plate Number" required></div>
            <div class="col-md-4">
                <select name="routeId" id="routeSelect" class="form-select" required>
                  <?php foreach($routes as $route) :?>
                    <option value="<?=$route['routeId']?>"><?=$route['routeName']?></option>
                    <?php endforeach?>
                </select>
            </div>

            <div class="col-md-4"><button type="submit" class="btn btn-success w-100">Save PUV</button></div>
        </div>
    </form>

    <h4>PUV Table</h4>
    <div id="puvsTable"></div>
    <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plate No.</th>
                    <th>Route</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($puvs as $puv): ?>
                <tr>
                    <td><?=$puv['puvId']; ?></td>
                    <td><?=$puv['plateNumber']; ?></td>
                    <td><?=$puv['routeId']; ?></td>
                    <td>
                        <form action="includes/delete_puvs.php" method="POST" style="display:inline;">
                            <input type="hidden" name="puvId" value="<?= $puv['puvId'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                  

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    </div>
</body>
</html>
