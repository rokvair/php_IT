<?php
// Function to insert a new message
function postMessage() {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
        // Sanitize and store the message
        $message = htmlspecialchars($_POST['message']); // Prevent XSS
        $siuntejo_id = 1; // Example: Replace with the logged-in user's ID
        $gavejo_id = 2;   // Example: Replace with the recipient user's ID

        // Database connection
        $servername = "localhost";
        $username = "root"; // replace with your username
        $password = "";     // replace with your password
        $dbname = "laivai"; // replace with your database name

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert message into the database
        $sql = "INSERT INTO messages (siuntejo_id, gavejo_id, message) VALUES ('$siuntejo_id', '$gavejo_id', '$message')";

        if ($conn->query($sql) === TRUE) {
            // After the message is successfully inserted, redirect to the same page (PRG pattern)
            header("Location: index.php"); // Redirect to the same page
            exit(); // Make sure no further code is executed after the redirect
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
}


// Call the function to post a message when the form is submitted
postMessage();
?>
