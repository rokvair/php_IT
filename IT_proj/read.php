<?php
// Function to display messages with filtering
function displayMessages($filter_sender, $filter_recipient) {
    $servername = "localhost";
    $username = "root"; // replace with your username
    $password = "";     // replace with your password
    $dbname = "laivai"; // replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Build the SQL query with conditions if filtering is applied
    $sql = "SELECT m.*, s.name AS sender_name, r.name AS recipient_name  // Assuming 'name' is the correct column for names
            FROM messages m
            JOIN naudotojai s ON m.siuntejo_id = s.id
            JOIN naudotojai r ON m.gavejo_id = r.id";  // Join with 'naudotojai' table to get the names

    if ($filter_sender || $filter_recipient) {
        $sql .= " WHERE";
        if ($filter_sender) {
            $sql .= " siuntejo_id = '$filter_sender'";
        }
        if ($filter_recipient) {
            if ($filter_sender) $sql .= " AND"; // Use AND if both sender and recipient are filtered
            $sql .= " gavejo_id = '$filter_recipient'";
        }
    }

    $sql .= " ORDER BY m.created ASC"; // Order by creation time (ascending)

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='message'>";
            echo "<strong>{$row['sender_name']} to {$row['recipient_name']}</strong><br>";
            echo "<p>{$row['message']}</p>";
            echo "<small>Posted on {$row['created']}</small>";
            echo "</div>";
        }
    } else {
        echo "No messages found.";
    }

    $conn->close();
}
?>
