<?php
// Start the session
session_start();

// Include the database connection
include 'db_test.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the user data from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Handle form submission for updating user data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? $user['name'];
    $email = $_POST['email'] ?? $user['email'];

    // Update the user's profile
    $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $name, $email, $user_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
        // Optionally, redirect after success
        // header("Location: profile.php");
        // exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update profile. Please try again.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Custom CSS -->
</head>
<body>
    <!-- Include header -->
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
