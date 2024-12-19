<?php
// Include the PHP script for message reading and writing
include('read.php');
include('write.php');

// Handle message posting (if form is submitted)
postMessage(); // This function will handle the POST and redirect after submission

// Handle filtering by sender and recipient
$filter_sender = isset($_GET['sender_id']) ? $_GET['sender_id'] : '';
$filter_recipient = isset($_GET['recipient_id']) ? $_GET['recipient_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Board</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<header>
        <h1>Welcome to Yacht Rental</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="yachts.html">Yachts</a></li>
                <li><a href="reviews.html">Reviews</a></li>
                <li><a href="reservations.html">Reservations</a></li>
                <li><a href="messages.php">Messages</a></li>
                <li><a href="admin.html">Admin Panel</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Message Board</h1>

        <!-- Filter Messages -->
        <div class="filter-container">
            <form method="get" action="messages.php">
                <label for="sender_id">Filter by Sender ID:</label>
                <input type="number" name="sender_id" id="sender_id" value="<?= htmlspecialchars($filter_sender) ?>" placeholder="Enter Sender ID">
                <label for="recipient_id">Filter by Recipient ID:</label>
                <input type="number" name="recipient_id" id="recipient_id" value="<?= htmlspecialchars($filter_recipient) ?>" placeholder="Enter Recipient ID">
                <input type="submit" value="Filter Messages">
            </form>
        </div>

        <!-- Messages -->
        <div class="message-board">
            <?php
            // Display messages with filtering
            displayMessages($filter_sender, $filter_recipient);
            ?>
        </div>

        <!-- Post a new message -->
        <div class="form-container">
            <h3>Post a New Message</h3>
            <form action="messages.php" method="post">
                <textarea name="message" placeholder="Write your message here..." required></textarea><br>
                <input type="submit" value="Post Message">
            </form>
        </div>
    </div>
</body>
</html>
