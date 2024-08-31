<?php
include './config/database.php';

// Start the session only if it has not been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $profile_pic = $_FILES['profile_pic'];

    // Handle profile picture upload
    if ($profile_pic['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_pic["name"]);
        move_uploaded_file($profile_pic["tmp_name"], $target_file);
    } else {
        $target_file = $user['profile']; // Keep the old profile pic if not updated
    }

    // Update user details in database
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, email=?, profile=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $target_file, $password_hashed, $user_id);
    } else {
        $sql = "UPDATE users SET username=?, email=?, profile=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $target_file, $user_id);
    }
    $stmt->execute();

    // Redirect with a query parameter
    header("Location: profile.php?updated=true");
    exit();
}

include('head.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include './widgets/sidebar.php'; ?>
  <?php include './widgets/navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Edit Profile</h2>

            <form action="profile.php" method="POST" enctype="multipart/form-data" class="border p-4 shadow-sm rounded">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="profile_pic">Profile Picture:</label>
                    <input type="file" class="form-control-file" name="profile_pic">
                    <img src="<?php echo $user['profile']; ?>" width="100" class="mt-2">
                </div>

                <div class="form-group">
                    <label for="password">Password (leave blank if not changing):</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Show alert if update is successful -->
<script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('updated')) {
            alert('Profile updated successfully!');
        }
    };
</script>
</body>
</html>
