<?php
// Include the configuration and reusable header
include('config.php');
include('header.php');

// Fetch all yachts from the database using PDO
try {
    $sql = "SELECT * FROM jachtos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching yachts: " . $e->getMessage() . "</p>";
}

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Yachts</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>

<h1>Available Yachts</h1>
<div class="yachts-container">
    <?php if (count($result) > 0): ?>
        <?php foreach ($result as $row): ?>
            <div class="yacht-card">
                <!-- Image and Yacht Details -->
                <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['pavadinimas']) ?>" class="yacht-image">
                <h2><?= htmlspecialchars($row['pavadinimas']) ?></h2>
                <p><strong>Price:</strong> €<?= number_format($row['kaina'], 2) ?>/day</p>
                <p><?= htmlspecialchars($row['aprasas']) ?></p>
                <a href="rezervacijos.php?jachta_id=<?= $row['id'] ?>" class="btn">Reserve Now</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No yachts available at the moment.</p>
    <?php endif; ?>
</div>

<?php
// Include the footer
include('footer.php');
?>
</body>
</html>



