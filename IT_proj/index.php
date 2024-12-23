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
    echo "<p>Klaida gaunant jachtas: " . $e->getMessage() . "</p>";
}
?>

<!-- Main content of the homepage -->
<section id="home">
    <div class="hero">
        <h1>Sveiki atvykę į geriausią jachtų nuomos paslaugą!</h1>
        <p>Atraskite gražiausias jachtas nepamirštamoms patirtims jūroje.</p>
        <a href="jachts.php" class="button">Peržiūrėkite mūsų jachtas</a>
    </div>

    <div class="introduction">
        <h2>Apie mūsų paslaugą</h2>
        <p>Siūlome platų jachtų pasirinkimą visoms progoms. Nesvarbu, ar ieškote ramaus poilsio, nuotykių kupino kruizo, ar prabangaus pabėgimo, turime jums tinkamą jachtą.</p>
        <p>Mūsų laivynas aprūpintas aukščiausios kokybės patogumais, kad uſtikrintume saugią ir patogų kelionę vandenyje.</p>
    </div>

    <div class="featured-yachts">
        <h2>Rekomenduojamos jachtos</h2>
        <div class="yacht-list">
            <?php if (isset($yachts) && count($yachts) > 0): ?>
                <?php foreach ($yachts as $yacht): ?>
                    <div class="yacht">
                        <h3><?php echo htmlspecialchars($yacht['pavadinimas']); ?></h3>
                        <p><?php echo htmlspecialchars($yacht['aprasas']); ?></p>
                        <a href="jachts.php?id=<?php echo $yacht['id']; ?>" class="button">Peržiūrėti detales</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Šiuo metu nėra rekomenduojamų jachtų.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="call-to-action">
        <h2>Pasiruošę išplaukti?</h2>
        <p>Užsisakykite jachtą jau dabar ir mėgaukitės nepamirštama patirtimi vandenyje.</p>
        <a href="rezervacijos.php" class="button">Rezervuoti</a>
    </div>
</section>

<!-- Footer or additional sections can be added here -->

<?php
// Optionally, you can include a footer here if desired
include('footer.php');
?>
</body>
</html>
