<?php
// Include the database connection and header
require_once 'config.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Get form input and sanitize it
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if the input fields are not empty
    if (empty($name) || empty($email) || empty($password)) {
        $error_message = "Please fill in all fields.";
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
                $success_message = "Registracija pavyko!";
            } else {
                $error_message = "Error: Could not register the user.";
            }
        } catch (PDOException $e) {
            // Handle any errors
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!-- Include the header -->
<?php include('header.php'); ?>

<!-- Registration Page HTML -->
<main>
    <!-- Registration Form -->
    <h2>Registracija</h2>

    <!-- Display success or error message -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Vardas:</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Slaptažodis:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit" name="register">Register</button>
    </form>
</main>

<!-- Include the footer -->
<?php include('footer.php'); ?>
