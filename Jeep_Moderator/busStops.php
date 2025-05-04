<?php
session_start();
require_once "includes/dbc.inc.php";

$query = $conn->query("SELECT * FROM bus_stops");
$busStops = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO bus_stops (name, location) VALUES (?, ?)");
    $stmt->execute([$name, $location]);

    header('Location: busStops.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Stop Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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
<div class="container mt-5">
        <h2>Add New Bus Stop</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Bus Stop Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Bus Stop</button>
        </form>
    </div>
    <div class="container mt-5">
        <h2>Bus Stops</h2>
        <a href="create.php" class="btn btn-primary mb-3">Add New Bus Stop</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($busStops as $stop): ?>
                <tr>
                    <td><?php echo $stop['id']; ?></td>
                    <td><?php echo $stop['name']; ?></td>
                    <td><?php echo $stop['location']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $stop['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?php echo $stop['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
                </div>
</body>
</html>
