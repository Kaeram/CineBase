<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['pwd'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: home.php");
        exit();
    } else {
        echo "Invalid credentials";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Cinebase</title>
    <link rel="icon" type="image/x-icon" href="img.jpg">
    <style>
        .card {
            width: 350px; /* Adjust the width to your desired value */
            margin: 50px auto; /* Center the card horizontally */
        }
        body {
            background-color: #f4f4f4; /* Optional: set a background color */
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header text-center">
        Welcome Back to Cinebase
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    <div class="card-footer text-center">
        <small>made by Kaushike</small>
    </div>
</div>

</body>
</html>



