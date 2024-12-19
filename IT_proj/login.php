<?php
// Include the necessary files
require_once 'config.php';  // Database connection
require_once 'header.php';  // Header (includes session_start)

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Get form inputs and sanitize them
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if the input fields are not empty
    if (empty($email) || empty($password)) {
        echo "<p>Please enter both email and password.</p>";
    } else {
        // Prepare the SQL query to fetch the user by email
        $sql = "SELECT id, varpav, email, password FROM naudotojai WHERE email = ?";

        // Debugging output: Check if the $conn object is valid
        // var_dump($conn); // Uncomment for debugging if needed

        $stmt = $conn->prepare($sql);

        // Check if the statement preparation was successful
        if ($stmt === false) {
            echo "<p>Error preparing the query: " . $conn->error . "</p>";
            exit;
        }

        // Bind parameters to the query
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists and if the password matches
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                // Successful login: Start the session and store user data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['varpav']; // Store the user's name (optional)

                // Redirect to the homepage after successful login
                header("Location: index.php");
                exit;
            } else {
                echo "<p>Invalid password. Please try again.</p>";
            }
        } else {
            echo "<p>User not found. Please check your email and try again.</p>";
        }
    }
}
?>

<!-- Login Form -->
<form method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    
    <button type="submit" name="login">Login</button>
</form>

<?php
// Include the footer
include('footer.php');
?>



