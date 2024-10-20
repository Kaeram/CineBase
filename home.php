<?php
session_start(); // Start session to access user info
include 'config.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Fetch user information from the database
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>CineBase Home</title>
    <link rel="icon" type="image/x-icon" href="img.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 15px;
        }
        .header .search-bar {
            width: 300px;
            display: inline-block;
        }
        .header .user-info {
            float: right;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .content {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>CineBase</h1>

    <!-- Search Bar -->
    <form action="search.php" method="GET" class="d-inline-block">
        <input type="text" name="query" class="form-control search-bar" placeholder="Search movies or TV shows..." required>
        <button type="submit" class="btn btn-outline-light">Search</button>
    </form>

    <!-- User Info and Watchlist Link -->
    <div class="user-info">
        <?php if (isset($user)): ?>
            <span>Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
            <a href="watchlist.php" class="btn btn-outline-light">My Watchlist</a>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-light">Login</a>
            <a href="register.php" class="btn btn-outline-light">Register</a>
        <?php endif; ?>
    </div>
    <div style="clear: both;"></div>
</div>

<div class="content">
    <h2>Explore the World of Movies and TV Shows</h2>
    <p>Search for your favorite movies and shows, read reviews, and manage your watchlist.</p>
</div>

</body>
</html>
