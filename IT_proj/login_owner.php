<?php
// login_owner.php

// Include the config.php file which contains the database connection
include('config.php');

// Start the session
session_start();

// Initialize error message variable
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the 'nuomotojai' table (owners)
    try {
        $sql = "SELECT * FROM nuomotojai WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $owner = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $owner['password'])) {
                $_SESSION['user_id'] = $owner['id'];
                $_SESSION['user_email'] = $owner['email']; 
                $_SESSION['user_role'] = 'owner';
                // Store email in session

                header('Location: owner_dashboard.php');
                exit;
            } else {
                $error_message = 'Invalid password.';
            }
        } else {
            $error_message = 'Owner not found.';
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

    <!-- Owner Login Form -->
    <form method="POST" action="">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</main>

</body>
</html>
