<?php
// Start session and check if user is admin
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect non-admins to login page
    exit;
}

// Include database connection
include 'db_connection.php';

// Fetch all users
$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);

// Fetch all movies
$movies_query = "SELECT * FROM movies";
$movies_result = $conn->query($movies_query);

// Add new movie logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_movie'])) {
    $movie_name = $_POST['movie_name'];
    $lead_actor = $_POST['lead_actor'];
    $lead_actress = $_POST['lead_actress'];
    $director = $_POST['director'];
    $trailer_link = $_POST['trailer_link'];

    $add_movie_sql = "INSERT INTO movies (movie_name, lead_actor, lead_actress, director, trailer_link) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($add_movie_sql);
    $stmt->bind_param('sssss', $movie_name, $lead_actor, $lead_actress, $director, $trailer_link);
    $stmt->execute();

    header('Location: admin_dashboard.php'); // Reload the page after adding movie
}

// Delete movie logic
if (isset($_GET['delete_movie'])) {
    $movie_id = $_GET['delete_movie'];

    $delete_movie_sql = "DELETE FROM movies WHERE movie_id = ?";
    $stmt = $conn->prepare($delete_movie_sql);
    $stmt->bind_param('i', $movie_id);
    $stmt->execute();

    header('Location: admin_dashboard.php'); // Reload the page after deleting movie
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Admin Dashboard - Cinebase</title>
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Admin Dashboard</h1>
        <hr>
        
        <!-- Users Section -->
        <h3>All Users</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['role'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <hr>

        <!-- Movies Section -->
        <h3>Manage Movies</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Movie ID</th>
                    <th>Movie Name</th>
                    <th>Lead Actor</th>
                    <th>Lead Actress</th>
                    <th>Director</th>
                    <th>Trailer Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($movie = $movies_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $movie['movie_id'] ?></td>
                    <td><?= $movie['movie_name'] ?></td>
                    <td><?= $movie['lead_actor'] ?></td>
                    <td><?= $movie['lead_actress'] ?></td>
                    <td><?= $movie['director'] ?></td>
                    <td><a href="<?= $movie['trailer_link'] ?>" target="_blank">Watch Trailer</a></td>
                    <td>
                        <a href="admin_dashboard.php?delete_movie=<?= $movie['movie_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <hr>

        <!-- Add New Movie Section -->
        <h3>Add New Movie</h3>
        <form method="POST" action="admin_dashboard.php">
            <div class="mb-3">
                <label for="movie_name" class="form-label">Movie Name</label>
                <input type="text" class="form-control" id="movie_name" name="movie_name" required>
            </div>
            <div class="mb-3">
                <label for="lead_actor" class="form-label">Lead Actor</label>
                <input type="text" class="form-control" id="lead_actor" name="lead_actor" required>
            </div>
            <div class="mb-3">
                <label for="lead_actress" class="form-label">Lead Actress</label>
                <input type="text" class="form-control" id="lead_actress" name="lead_actress" required>
            </div>
            <div class="mb-3">
                <label for="director" class="form-label">Director</label>
                <input type="text" class="form-control" id="director" name="director" required>
            </div>
            <div class="mb-3">
                <label for="trailer_link" class="form-label">Trailer Link (YouTube URL)</label>
                <input type="url" class="form-control" id="trailer_link" name="trailer_link" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_movie">Add Movie</button>
        </form>
    </div>
</body>
</html>
