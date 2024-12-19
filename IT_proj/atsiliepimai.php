<?php
// Include the header
include('header.php');

// Database connection setup
$host = 'localhost'; // Database host
$dbname = 'laivai'; // Database name
$username = 'stud'; // Database username
$password = 'stud'; // Database password

// Initialize the PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $yacht_owner_id = $_POST['yacht_owner_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    // Insert the review into the database
    try {
        $sql = "INSERT INTO atsiliepimai (nuomotojas, naudotojas, tekstas, ivertinimas) 
                VALUES (:yacht_owner_id, :user_id, :review_text, :rating)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':yacht_owner_id', $yacht_owner_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);

        if ($stmt->execute()) {
            $success_message = "Your review has been submitted successfully!";
        } else {
            $error_message = "There was an error submitting your review.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
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
    <h2>User Reviews</h2>
    <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <h3><?php echo htmlspecialchars($review['user_name']); ?> - <?php echo htmlspecialchars($review['yacht_owner_name']); ?></h3>
                <p><strong>Rating:</strong> <?php echo $review['ivertinimas']; ?> / 5</p>
                <p><strong>Review:</strong> <?php echo nl2br(htmlspecialchars($review['tekstas'])); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Review Submission Form -->
    <h2>Leave a Review</h2>
    <form action="" method="POST">
        <label for="user_id">Your User ID:</label><br>
        <input type="number" id="user_id" name="user_id" required><br><br>

        <label for="yacht_owner_id">Yacht Owner ID:</label><br>
        <input type="number" id="yacht_owner_id" name="yacht_owner_id" required><br><br>

        <label for="rating">Rating (1-5):</label><br>
        <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

        <label for="review_text">Review:</label><br>
        <textarea id="review_text" name="review_text" rows="4" required></textarea><br><br>

        <input type="submit" value="Submit Review">
    </form>
</section>

<?php
// Include footer if necessary
// include('footer.php');
?>
</body>
</html>



