<?php
// add_yacht.php

// Įtraukti duomenų bazės ryšį
include('config.php');

// Pradėti sesiją
session_start();

// Patikrinti, ar vartotojas yra prisijungęs kaip savininkas
if (!isset($_SESSION['user_id'])) {
    header('Location: login_owner.php'); // Nukreipti į savininko prisijungimą, jei neprisijungęs
    exit;
}

$error_message = '';

// Tvarkyti formos pateikimą
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gauti ir išvalyti vartotojo įvestis
    $pavadinimas = $_POST['pavadinimas'];
    $foto = $_POST['foto'];
    $kaina = $_POST['kaina'];
    $aprasas = $_POST['aprasas'];
    $savId = $_SESSION['user_id']; // Gauti prisijungusio savininko ID

    // Įrašyti naują jachtą į duomenų bazę
    try {
        $sql = "INSERT INTO jachtos (pavadinimas, foto, kaina, savId, aprasas) VALUES (:pavadinimas, :foto, :kaina, :savId, :aprasas)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':pavadinimas', $pavadinimas);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':kaina', $kaina);
        $stmt->bindParam(':savId', $savId);
        $stmt->bindParam(':aprasas', $aprasas);

        $stmt->execute();

        // Nukreipti į savininko skydelį po jachtos pridėjimo
        header('Location: owner_dashboard.php');
        exit;
    } catch (PDOException $e) {
        $error_message = "Klaida: " . $e->getMessage();
    }
}

?>

<!-- Įtraukti antraštę -->
<?php include('header.php'); ?>

<main>
    <h1>Pridėti Naują Jachtą</h1>

    <!-- Rodyti klaidos pranešimą, jei jis yra -->
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="add_yacht.php">
        <label for="pavadinimas">Jachtos Pavadinimas:</label><br>
        <input type="text" id="pavadinimas" name="pavadinimas" required><br><br>

        <label for="foto">Nuotrauka (Failo Pavadinimas):</label><br>
        <input type="text" id="foto" name="foto" required><br><br>

        <label for="kaina">Kaina:</label><br>
        <input type="number" step="0.01" id="kaina" name="kaina" required><br><br>

        <label for="aprasas">Aprašas:</label><br>
        <textarea id="aprasas" name="aprasas" required></textarea><br><br>

        <input type="submit" value="Pridėti Jachtą">
    </form>
</main>

</body>
</html>
