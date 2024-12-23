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
    echo "<p>Klaida gaunant jachtas: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galimos jachtos</title>
    <link rel="stylesheet" href="styles.css"> <!-- Nuoroda į CSS failą -->
    <style>
        /* Pridėkite pagrindinį stilių jachtų kortelėms */
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

        .yacht-images {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 10px;
            padding: 10px;
        }

        .yacht-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
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
    <h1>Galimos jachtos</h1>
    <div class="yachts-container">
        <?php if (count($result) > 0): ?>
            <?php foreach ($result as $row): ?>
                <div class="yacht-card">
                    <!-- Rodyti kelias nuotraukas -->
                    <div class="yacht-images">
                        <?php 
                        $photos = explode(',', $row['foto']); // Suskaidyti 'foto' lauką pagal kablelius
                        foreach ($photos as $photo): 
                            $photo = trim($photo); // Pašalinti papildomas tarpines
                            if ($photo): // Užtikrinti, kad nuotraukos URL nėra tuščias
                        ?>
                            <img src="<?= htmlspecialchars($photo) ?>" 
                                 alt="<?= htmlspecialchars($row['pavadinimas']) ?>">
                        <?php endif; endforeach; ?>
                    </div>
                    <!-- Jachtos Detalės -->
                    <h2><?= htmlspecialchars($row['pavadinimas']) ?></h2>
                    <p><strong>Kaina:</strong> €<?= number_format($row['kaina'], 2) ?>/diena</p>
                    <p><?= htmlspecialchars($row['aprasas']) ?></p>
                    <a href="rezervacijos.php?jachta_id=<?= $row['id'] ?>" class="btn">Rezervuoti dabar</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Šiuo metu nėra galimų jachtų.</p>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
