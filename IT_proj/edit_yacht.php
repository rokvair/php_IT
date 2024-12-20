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

// Check if the yacht ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid yacht ID.";
    exit;
}

$yacht_id = $_GET['id'];

// Fetch the yacht details based on the ID
try {
    $sql = "SELECT * FROM jachtos WHERE id = :yacht_id AND savId = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':yacht_id', $yacht_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $yacht = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching yacht: " . $e->getMessage();
}

if (!$yacht) {
    echo "Yacht not found or you do not have permission to edit this yacht.";
    exit;
}

// Handle form submission for updating the yacht
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pavadinimas = $_POST['pavadinimas'];
    $aprasas = $_POST['aprasas'];
    $kaina = $_POST['kaina'];
    $foto = $_POST['foto']; // You can add more handling for the photo upload if necessary

    // Update the yacht details in the database
    try {
        $sql = "UPDATE jachtos SET pavadinimas = :pavadinimas, aprasas = :aprasas, kaina = :kaina, foto = :foto WHERE id = :yacht_id AND savId = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':pavadinimas', $pavadinimas);
        $stmt->bindParam(':aprasas', $aprasas);
        $stmt->bindParam(':kaina', $kaina);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':yacht_id', $yacht_id);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            $success_message = "Yacht updated successfully!";
        } else {
            $error_message = "Error updating yacht.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!-- Display any messages -->
<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<!-- Edit Yacht Form -->
<section id="edit_yacht">
    <h2>Edit Yacht</h2>

    <form action="" method="POST">
        <label for="pavadinimas">Yacht Name:</label><br>
        <input type="text" id="pavadinimas" name="pavadinimas" value="<?php echo htmlspecialchars($yacht['pavadinimas']); ?>" required><br><br>

        <label for="aprasas">Description:</label><br>
        <textarea id="aprasas" name="aprasas" rows="4" required><?php echo htmlspecialchars($yacht['aprasas']); ?></textarea><br><br>

        <label for="kaina">Price (EUR):</label><br>
        <input type="number" id="kaina" name="kaina" value="<?php echo htmlspecialchars($yacht['kaina']); ?>" min="0" step="0.01" required><br><br>

        <label for="foto">Photo URL (leave empty to keep the current one):</label><br>
        <input type="text" id="foto" name="foto" value="<?php echo htmlspecialchars($yacht['foto']); ?>"><br><br>

        <input type="submit" value="Update Yacht">
    </form>
</section>

<?php
// Include footer if necessary
// include('footer.php');
?>
</body>
</html>