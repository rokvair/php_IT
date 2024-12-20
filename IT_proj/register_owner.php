<?php
// register_owner.php

// Include the config.php file which contains the database connection
include('config.php');

// Start the session
session_start();

// Initialize error message and success message
$error_message = '';
$success_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $varpav = trim($_POST['varpav']);  // Owner's name
    $email = trim($_POST['email']);     // Email address
    $password = $_POST['password'];    // Password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);  // Hashed password

    try {
        // Check if the email already exists in the 'nuomotojai' table
        $sql = "SELECT * FROM nuomotojai WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error_message = 'Email is already registered.';
        } else {
            // Insert new owner into the 'nuomotojai' table with name, email, and password
            $sql = "INSERT INTO nuomotojai (varpav, email, password) VALUES (:varpav, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':varpav', $varpav);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->execute();

            $success_message = 'Owner registered successfully. You can now log in.';
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!-- Include the header -->
<?php include('header.php'); ?>

<main>
    <!-- Display error message if there's any -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Display success message -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <!-- Owner Registration Form -->
    <form method="POST" action="">
        <label for="varpav">Full Name:</label><br>
        <input type="text" id="varpav" name="varpav" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>
</main>

</body>
</html>
