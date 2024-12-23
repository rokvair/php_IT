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

            $success_message = "Kaina sėkmingai padidinta!";
        } else {
            $error_message = "Jachta nerasta arba neturite teisės atnaujinti kainos.";
        }
    } catch (PDOException $e) {
        $error_message = "Klaida atnaujinant kainą: " . $e->getMessage();
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

        $success_message = "Jachta sėkmingai ištrinta!";
    } catch (PDOException $e) {
        $error_message = "Klaida trinant jachtą: " . $e->getMessage();
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
    $error_message = "Klaida gaunant jachtas: " . $e->getMessage();
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
    <h2>Jūsų Jachtos</h2>
    
    <!-- Button to add a new yacht -->
    <p><a href="add_yacht.php" class="add-yacht-btn">Pridėti naują jachtą</a></p>

    <!-- Check if the owner has yachts -->
    <?php if (empty($yachts)): ?>
        <p>Neturite jokių paskelbtų jachtų. <a href="add_yacht.php">Pridėti naują jachtą</a></p>
    <?php else: ?>
        <div class="yachts-list">
            <?php foreach ($yachts as $yacht): ?>
                <div class="yacht">
                    <h3><?php echo htmlspecialchars($yacht['pavadinimas']); ?></h3>
                    <p><strong>Aprašymas:</strong> <?php echo nl2br(htmlspecialchars($yacht['aprasas'])); ?></p>
                    <p><strong>Kaina:</strong> €<?php echo number_format($yacht['kaina'], 2); ?></p>
                    <p><strong>Nuotraukos:</strong></p>
                    <div class="yacht-photos">
                        <?php 
                        $photos = explode(',', $yacht['foto']); // Split the photo URLs
                        foreach ($photos as $photo): 
                        ?>
                            <img src="<?php echo htmlspecialchars(trim($photo)); ?>" alt="Jachtos nuotrauka" width="100">
                        <?php endforeach; ?>
                    </div>

                    <!-- Button to increase the price by a certain percentage -->
                    <a href="owner_dashboard.php?increase_price=<?php echo $yacht['id']; ?>" class="increase-price-btn">Padidinti kainą 10%</a>
                    <a href="edit_yacht.php?id=<?php echo $yacht['id']; ?>" class="edit-btn">Redaguoti jachtą</a>

                    <!-- Button to delete the yacht -->
                    <a href="owner_dashboard.php?delete_yacht=<?php echo $yacht['id']; ?>" class="delete-btn" onclick="return confirm('Ar tikrai norite ištrinti šią jachtą?');">Ištrinti jachtą</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

</body>
</html>
