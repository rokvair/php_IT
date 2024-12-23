<?php
// Include the config.php file which contains the database connection
include('config.php');

// Start the session
session_start();

// Initialize error message variables
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    try {
        // Prepare the SQL query to check if the email exists in the 'naudotojai' table
        $sql = "SELECT * FROM naudotojai WHERE email = :email";  // Use 'email' column to find the user
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if a user with that email exists
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user ID and email in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];  // Store email in session instead of 'vardas'

                // Redirect to index.php after successful login
                header('Location: index.php');
                exit;
            } else {
                $error_message = 'Invalid password.';
            }
        } else {
            $error_message = 'User not found.';
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!-- Include the header -->
<?php include('header.php'); ?>

<!-- HTML for the login form -->
<main>
<h2>Prisijungti</h2>
    <!-- Display error message if there's any -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Slaptažodis:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Prisijungti">
    </form>
</main>

</body>
</html>
