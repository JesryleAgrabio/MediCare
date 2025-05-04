<?php
require_once 'includes/dbc.inc.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

   
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
} else {
    header("Location: admin_dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        .edit-container {
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
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    
        <div class="edit-container col-md-6">
    <div class="container">
        <h2>Edit User</h2>

        <form method="POST" action="includes/edit-user.inc.php">
            <div class="mb-3">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="mb-3">
                    <label for="account_type" class="form-label">Account Type:</label>
                    <select name="account_type" class="form-select">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </div>

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</body>
</html>
