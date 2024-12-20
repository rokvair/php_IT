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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Yachts</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Add basic styling for the yacht cards */
        .yachts-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .yacht-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            width: 300px;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .yacht-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .yacht-card h2 {
            margin: 10px 0;
            font-size: 20px;
            color: #333;
        }

        .yacht-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .yacht-card .btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .yacht-card .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
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

    <?php include('footer.php'); ?>
</body>
</html>
