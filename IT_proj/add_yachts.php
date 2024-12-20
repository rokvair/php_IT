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

// Handle adding a new yacht
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_yacht'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $photos = $_POST['photos']; // Accept multiple photo URLs

    try {
        // Insert the new yacht into the database
        $sql = "INSERT INTO jachtos (pavadinimas, aprasas, kaina, foto, savId) 
                VALUES (:name, :description, :price, :photos, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':photos', $photos);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $success_message = "Yacht added successfully!";
    } catch (PDOException $e) {
        $error_message = "Error adding yacht: " . $e->getMessage();
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

<!-- Add Yacht Form -->
<section id="add_yacht">
    <h2>Add a New Yacht</h2>
    <form method="POST" action="">
        <label for="name">Yacht Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price (€):</label><br>
        <input type="number" id="price" name="price" required><br><br>

        <label for="photos">Photo URLs (separate with commas):</label><br>
        <input type="text" id="photos" name="photos" required><br><br>

        <input type="submit" name="add_yacht" value="Add Yacht">
    </form>
</section>

</body>
</html>
