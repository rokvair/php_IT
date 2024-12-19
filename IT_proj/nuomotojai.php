<?php
// Include the header
include('header.php');

// Database connection setup
$host = 'localhost'; // Database host
$dbname = 'laivai'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Initialize the PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session
session_start();

// Check if the renter is logged in
if (!isset($_SESSION['renter_id'])) {
    die("Please log in as a yacht owner to view this page.");
}

$renter_id = $_SESSION['renter_id'];

// Fetch renter profile information
try {
    $sql = "SELECT * FROM nuomotojai WHERE id = :renter_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':renter_id', $renter_id);
    $stmt->execute();
    $renter = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$renter) {
        die("Renter not found.");
    }
} catch (PDOException $e) {
    die("Error fetching renter profile: " . $e->getMessage());
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $varpav = $_POST['varpav'];
    $email = $_POST['email'];
    $telnr = $_POST['telnr'];

    try {
        $sql = "UPDATE nuomotojai SET varpav = :varpav, email = :email, telnr = :telnr WHERE id = :renter_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':varpav', $varpav);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telnr', $telnr);
        $stmt->bindParam(':renter_id', $renter_id);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "There was an error updating the profile.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!-- Display any messages -->
<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<!-- Renter's Profile Section -->
<section id="profile">
    <h2>Your Profile</h2>

    <form action="" method="POST">
        <label for="varpav">Name:</label><br>
        <input type="text" id="varpav" name="varpav" value="<?php echo htmlspecialchars($renter['varpav']); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($renter['email']); ?>" required><br><br>

        <label for="telnr">Phone Number:</label><br>
        <input type="text" id="telnr" name="telnr" value="<?php echo htmlspecialchars($renter['telnr']); ?>" required><br><br>

        <input type="submit" name="update_profile" value="Update Profile">
    </form>

    <br>

    <!-- Logout Button -->
    <form action="" method="POST">
        <input type="submit" name="logout" value="Logout">
    </form>
</section>

<?php
// Include footer if necessary
// include('footer.php');
?>
</body>
</html>



