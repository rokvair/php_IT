<?php
// Include the database connection and header
require_once 'config.php';
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Get form input and sanitize it
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if the input fields are not empty
    if (empty($name) || empty($email) || empty($password)) {
        echo "<p>Please fill in all fields.</p>";
    } else {
        try {
            // Prepare the SQL query to insert the data into the database
            $sql = "INSERT INTO naudotojai (varpav, email, password) VALUES (:name, :email, :password)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);

            // Hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Execute the query
            if ($stmt->execute()) {
                echo "<p>Registration successful!</p>";
            } else {
                echo "<p>Error: Could not register the user.</p>";
            }
        } catch (PDOException $e) {
            // Handle any errors
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!-- Registration Form -->
<form method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit" name="register">Register</button>
</form>

<?php
// Include the footer
include('footer.php');
?>



