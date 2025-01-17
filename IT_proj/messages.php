<?php
// Include the header and config.php for database connection
include('header.php');
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$is_owner = $user_id < 1000;  // Check if the user is an owner (id < 1000)
$is_owner_42 = $user_id == 42; // Check if the user is the owner with ID 42

// Fetch all messages where the user is either the sender or the receiver, or all messages for owner 42
try {
    if ($is_owner_42) {
        // Owner 42 should see all messages
        $sql = "SELECT m.id, m.siuntejo_id, m.gavejo_id, m.created, m.message
                FROM messages m
                ORDER BY m.created ASC";  // Show all messages for owner 42
    } else {
        // For others (owners or regular users), show only messages where the user is the sender or receiver
        $sql = "SELECT m.id, m.siuntejo_id, m.gavejo_id, m.created, m.message
                FROM messages m
                WHERE (m.siuntejo_id = :user_id OR m.gavejo_id = :user_id)
                ORDER BY m.created ASC";  // Show messages for the logged-in user
    }

    $stmt = $pdo->prepare($sql);
    if (!$is_owner_42) {
        $stmt->bindParam(':user_id', $user_id);
    }
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Klaida gaunant pranešimus: " . $e->getMessage();
}

// Handle message sending form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && isset($_POST['receiver_id'])) {
    if ($is_owner_42) {
        // Owner 42 cannot send messages, so we just skip the message sending
        $error_message = "Jūs neturite teisės siųsti pranešimų.";
    } else {
        $message = trim($_POST['message']);
        $receiver_id = (int) $_POST['receiver_id'];

        // Ensure the receiver_id is valid
        if ($receiver_id <= 0 || $receiver_id == $user_id) {
            $error_message = "Netinkamas gavėjas.";
        } else {
            // Validate the recipient based on the user type
            if ($is_owner) {
                // Owners can only message users who have sent them a message
                $valid_recipient = false;
                foreach ($messages as $msg) {
                    if ($msg['siuntejo_id'] == $receiver_id) {
                        $valid_recipient = true;
                        break;
                    }
                }
                if (!$valid_recipient) {
                    $error_message = "Jūs galite susisiekti tik su vartotojais, kurie jums parašė.";
                }
            } else {
                // Users can send a message to any owner
                if ($receiver_id >= 1000) {
                    $error_message = "Jūs galite susisiekti tik su savininkais.";
                }
            }

            // If the recipient is valid, insert the message into the database
            if (!isset($error_message)) {
                try {
                    $sql = "INSERT INTO messages (siuntejo_id, gavejo_id, message) VALUES (:user_id, :receiver_id, :message)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':receiver_id', $receiver_id);
                    $stmt->bindParam(':message', $message);
                    $stmt->execute();

                    $success_message = "Pranešimas sėkmingai išsiųstas!";
                } catch (PDOException $e) {
                    $error_message = "Klaida siunčiant pranešimą: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch users and owners for message recipients (modify logic for owner 42)
try {
    if ($is_owner_42) {
        // Owner 42 can't send messages, so don't provide recipients
        $recipients = [];
    } elseif ($is_owner) {
        // Owners can only message users who have contacted them
        $sql = "SELECT DISTINCT u.id, u.varpav 
                FROM naudotojai u
                INNER JOIN messages m ON m.siuntejo_id = u.id 
                WHERE m.gavejo_id = :user_id";
        
        // For owners, bind the user ID as the owner receiving the message
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Users can message any owner
        $sql = "SELECT id, varpav FROM nuomotojai WHERE id < 1000"; // Owners only
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error_message = "Klaida gaunant naudotojus ir savininkus: " . $e->getMessage();
}

?>

<!-- Display any error or success messages -->
<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<!-- Display Messages -->
<section id="messages">
    <h2>Pranešimai</h2>

    <?php if (empty($messages)): ?>
        <p>Pranešimų nėra.</p>
    <?php else: ?>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <p><strong>Nuo:</strong> 
                <?php
                // If the user is owner 42, display the ID instead of the name
                if ($is_owner_42) {
                    echo htmlspecialchars($message['siuntejo_id']);
                } else {
                    echo htmlspecialchars(getUserName($message['siuntejo_id'], $pdo)); // Function to get the sender's name
                }
                ?> 
                <strong>Kam:</strong> 
                <?php
                // If the user is owner 42, display the ID instead of the name
                if ($is_owner_42) {
                    echo htmlspecialchars($message['gavejo_id']);
                } else {
                    echo htmlspecialchars(getUserName($message['gavejo_id'], $pdo)); // Function to get the receiver's name
                }
                ?>
                </p>
                <p><strong>Data:</strong> <?php echo htmlspecialchars($message['created']); ?></p>
                <p><strong>Pranešimas:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<!-- Message Sending Form (Hidden for owner 42) -->
<?php if (!$is_owner_42): ?>
    <section id="send_message">
        <h2>Siųsti pranešimą</h2>
        <form action="" method="POST">
            <label for="receiver_id">Pasirinkti gavėją:</label><br>
            <select id="receiver_id" name="receiver_id" required>
                <option value="">Pasirinkti gavėją</option>
                <!-- Display valid recipients based on the user's role -->
                <?php foreach ($recipients as $recipient): ?>
                    <option value="<?php echo $recipient['id']; ?>"><?php echo htmlspecialchars($recipient['varpav']); ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="message">Pranešimas:</label><br>
            <textarea id="message" name="message" rows="4" required></textarea><br><br>

            <input type="submit" value="Siųsti pranešimą">
        </form>
    </section>
<?php endif; ?>

<?php
// Include footer if necessary
// include('footer.php');

// Helper function to get user names
function getUserName($user_id, $pdo) {
    $sql = "SELECT varpav FROM naudotojai WHERE id = :user_id UNION SELECT varpav FROM nuomotojai WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['varpav'] : 'Nežinomas';
}
?>
</body>
</html>
