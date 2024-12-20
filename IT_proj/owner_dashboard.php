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

// Handle price increase
if (isset($_GET['increase_price']) && is_numeric($_GET['increase_price'])) {
    $yacht_id = $_GET['increase_price'];
    $percentage = 10; // Percentage by which to increase the price (e.g., 10%)

    try {
        // Get the current price of the yacht
        $sql = "SELECT kaina FROM jachtos WHERE id = :yacht_id AND savId = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':yacht_id', $yacht_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $yacht = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($yacht) {
            $current_price = $yacht['kaina'];
            $new_price = $current_price + ($current_price * ($percentage / 100));

            // Update the price in the database
            $sql = "UPDATE jachtos SET kaina = :new_price WHERE id = :yacht_id AND savId = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':new_price', $new_price);
            $stmt->bindParam(':yacht_id', $yacht_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $success_message = "Price increased successfully!";
        } else {
            $error_message = "Yacht not found or you do not have permission to update the price.";
        }
    } catch (PDOException $e) {
        $error_message = "Error updating price: " . $e->getMessage();
    }
}

// Handle deleting a yacht
if (isset($_GET['delete_yacht']) && is_numeric($_GET['delete_yacht'])) {
    $yacht_id = $_GET['delete_yacht'];

    try {
        // Delete the yacht from the database
        $sql = "DELETE FROM jachtos WHERE id = :yacht_id AND savId = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':yacht_id', $yacht_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $success_message = "Yacht deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error deleting yacht: " . $e->getMessage();
    }
}

// Fetch yachts for the logged-in user
try {
    $sql = "SELECT * FROM jachtos WHERE savId = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $yachts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching yachts: " . $e->getMessage();
}

?>

<!-- Display any messages -->
<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<!-- Owner Dashboard - Display yachts -->
<section id="owner_dashboard">
    <h2>Your Yachts</h2>
    
    
    <!-- Button to add a new yacht -->
    <div class="add-yacht-btn-container">
        <a href="add_yachts.php" class="add-yacht-btn">Add a New Yacht</a>
    </div>
    
    <!-- Check if the owner has yachts -->
    <?php if (empty($yachts)): ?>
        <p>You have no yachts posted. <a href="add_yachts.php">Add a New Yacht</a></p>
    <?php else: ?>
        <div class="yachts-list">
            <?php foreach ($yachts as $yacht): ?>
                <div class="yacht">
                    <h3><?php echo htmlspecialchars($yacht['pavadinimas']); ?></h3>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($yacht['aprasas'])); ?></p>
                    <p><strong>Price:</strong> €<?php echo number_format($yacht['kaina'], 2); ?></p>
                    <p><strong>Photo:</strong> <img src="<?php echo htmlspecialchars($yacht['foto']); ?>" alt="Yacht photo" width="100"></p>

                    <!-- Button to increase the price by a certain percentage -->
                    <a href="owner_dashboard.php?increase_price=<?php echo $yacht['id']; ?>" class="increase-price-btn">Pakelti kainą per 10% sezonui</a>
                    <a href="edit_yacht.php?id=<?php echo $yacht['id']; ?>" class="edit-btn">Edit Yacht</a>

                    <!-- Button to delete the yacht -->
                    <a href="owner_dashboard.php?delete_yacht=<?php echo $yacht['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this yacht?');">Delete Yacht</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

</body>
</html>
