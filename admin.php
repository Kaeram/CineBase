<?php
// Start session and check if admin is logged in (you can implement this check as per your login system)
session_start();

// Assuming you have a function to check if the user is an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Redirect non-admins to login page
    exit;
}

// Database connection
include 'db_connection.php'; // Modify with the actual path of your DB connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $release_year = $_POST['release_year'];
    $genre = $_POST['genre'];
    $lead_actor = $_POST['lead_actor'];
    $lead_actress = $_POST['lead_actress'];
    $director = $_POST['director'];
    $trailer_link = $_POST['trailer_link'];

    // SQL query to insert movie
    $sql = "INSERT INTO movies (title, release_year, genre, lead_actor, lead_actress, director, trailer_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sisssss', $title, $release_year, $genre, $lead_actor, $lead_actress, $director, $trailer_link);

        if ($stmt->execute()) {
            $success_message = "Movie added successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Add Movie - Admin</title>
    <style>
        .container {
            margin-top: 50px;
            width: 600px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Add a New Movie</h1>
        
        <?php
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>

        <form method="POST" action="admin.php">
            <div class="mb-3">
                <label for="title" class="form-label">Movie Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="release_year" class="form-label">Release Year</label>
                <input type="number" class="form-control" id="release_year" name="release_year" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" required>
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
                <label for="trailer_link" class="form-label">Trailer YouTube Link</label>
                <input type="url" class="form-control" id="trailer_link" name="trailer_link" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Movie</button>
        </form>
    </div>
</body>
</html>
