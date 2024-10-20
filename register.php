<?php
// Include the database connection
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $is_admin = isset($_POST['is_admin']) ? 'admin' : 'user'; // Set role based on checkbox

    // Check if email already exists
    $check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($check_sql);
    $stmt->bindValue('email', $email);
    $stmt->execute([$email, $password, $is_admin]);
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, redirect to login
        header('Location: login.php?error=email_exists');
        exit;
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (email, pwd, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('sss', $email, $password, $is_admin);
        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header('Location: login.php?success=registered');
        } else {
            $error_message = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Register - Cinebase</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
            width: 400px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Register on Cinebase</h1>
    
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php } ?>
    
    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <!-- Checkbox to register as Admin -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin">
            <label class="form-check-label" for="is_admin">
                Register as Admin
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>

</body>
</html>
