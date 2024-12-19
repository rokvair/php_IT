<?php
require_once 'config.php'; // Include the database connection

// Include the header
require_once 'header.php';

// Fetch featured yachts from the database
try {
    $sql = "SELECT id, pavadinimas, aprasas FROM jachtos LIMIT 3"; // Fetch 3 featured yachts
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $yachts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching yachts: " . $e->getMessage() . "</p>";
}
?>

<!-- Main content of the homepage -->
<section id="home">
    <div class="hero">
        <h1>Welcome to the Best Yacht Rental Service!</h1>
        <p>Explore beautiful yachts for unforgettable experiences at sea.</p>
        <a href="jachts.php" class="button">View Our Yachts</a>
    </div>

    <div class="introduction">
        <h2>About Our Service</h2>
        <p>We offer a wide variety of yachts for all occasions. Whether you're looking for a relaxing vacation, an adventurous cruise, or a luxurious getaway, we have the perfect yacht for you.</p>
        <p>Our fleet is equipped with top-notch amenities to ensure you have a safe and comfortable journey on the water.</p>
    </div>

    <div class="featured-yachts">
        <h2>Featured Yachts</h2>
        <div class="yacht-list">
            <?php if (isset($yachts) && count($yachts) > 0): ?>
                <?php foreach ($yachts as $yacht): ?>
                    <div class="yacht">
                        <h3><?php echo htmlspecialchars($yacht['pavadinimas']); ?></h3>
                        <p><?php echo htmlspecialchars($yacht['aprasas']); ?></p>
                        <a href="jachts.php?id=<?php echo $yacht['id']; ?>" class="button">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No featured yachts available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="call-to-action">
        <h2>Ready to Set Sail?</h2>
        <p>Book your yacht today and enjoy a memorable experience on the water.</p>
        <a href="rezervacijos.php" class="button">Make a Reservation</a>
    </div>
</section>

<!-- Footer or additional sections can be added here -->

<?php
// Optionally, you can include a footer here if desired
include('footer.php');
?>
</body>
</html>



