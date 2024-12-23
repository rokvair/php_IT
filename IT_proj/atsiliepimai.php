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

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_id < 1000) {
        // Restrict review submission to users only
        $error_message = "Only users can submit reviews.";
    } else {
        // Get the necessary data from the form
        $yacht_owner_name = $_POST['yacht_owner_name'];
        $review_text = $_POST['review_text'];
        $rating = $_POST['rating'];

        // Get the corresponding yacht owner ID based on the 'varpav' (owner name)
        try {
            $sql = "SELECT id FROM nuomotojai WHERE varpav = :yacht_owner_name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':yacht_owner_name', $yacht_owner_name);
            $stmt->execute();
            $yacht_owner = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($yacht_owner) {
                $yacht_owner_id = $yacht_owner['id'];

                // Insert the review into the 'atsiliepimai' table
                $sql = "INSERT INTO atsiliepimai (nuomotojas, naudotojas, tekstas, ivertinimas) 
                        VALUES (:yacht_owner_id, :user_id, :review_text, :rating)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':yacht_owner_id', $yacht_owner_id);
                $stmt->bindParam(':user_id', $user_id); // Use the logged-in user's ID
                $stmt->bindParam(':review_text', $review_text);
                $stmt->bindParam(':rating', $rating);

                if ($stmt->execute()) {
                    $success_message = "Your review has been submitted successfully!";
                } else {
                    $error_message = "There was an error submitting your review.";
                }
            } else {
                $error_message = "Yacht owner not found.";
            }
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Fetch yacht owner names for the dropdown (from the 'nuomotojai' table)
try {
    $sql = "SELECT varpav FROM nuomotojai";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $yacht_owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching yacht owners: " . $e->getMessage();
}

// Fetch reviews from the database
try {
    $sql = "SELECT atsiliepimai.id, atsiliepimai.tekstas, atsiliepimai.ivertinimas, naudotojai.varpav AS user_name, 
            nuomotojai.varpav AS yacht_owner_name
            FROM atsiliepimai
            JOIN naudotojai ON atsiliepimai.naudotojas = naudotojai.id
            JOIN nuomotojai ON atsiliepimai.nuomotojas = nuomotojai.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching reviews: " . $e->getMessage();
}
?>

<!-- Display any messages -->
<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<!-- Reviews Section -->
<section id="reviews">
    <h2>Naudotojų atsiliepimai</h2>
    <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <h3><?php echo htmlspecialchars($review['user_name']); ?> - <?php echo htmlspecialchars($review['yacht_owner_name']); ?></h3>
                <p><strong>Vertinimas:</strong> <?php echo $review['ivertinimas']; ?> / 5</p>
                <p><strong>Atsiliepimas:</strong> <?php echo nl2br(htmlspecialchars($review['tekstas'])); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Review Submission Form (only visible to users) -->
    <?php if ($user_id >= 1000): ?> <!-- Only users can submit reviews -->
        <h2>Palik atsiliepimą:</h2>
        <form action="" method="POST">
            <label for="yacht_owner_name">Jachtos nuomotojo vardas:</label><br>
            <select id="yacht_owner_name" name="yacht_owner_name" required>
                <option value="">Pasirink savininką:</option>
                <?php foreach ($yacht_owners as $owner): ?>
                    <option value="<?php echo htmlspecialchars($owner['varpav']); ?>">
                        <?php echo htmlspecialchars($owner['varpav']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="rating">Vertinimas (1-5):</label><br>
            <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

            <label for="review_text">Atsiliepimas:</label><br>
            <textarea id="review_text" name="review_text" rows="4" required></textarea><br><br>

            <input type="submit" value="Pateikti">
        </form>
    <?php else: ?>
        <p style="color: red;">Tik naudotojai gali palikti atsiliepimus.</p>
    <?php endif; ?>
</section>

<?php
// Include footer if necessary
// include('footer.php');
?>
</body>
</html>
