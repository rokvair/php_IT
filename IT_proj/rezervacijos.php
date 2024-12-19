<?php
// Include the config.php to access the PDO connection
include('config.php');

// Include header.php for the page header (optional)
include('header.php');

// Start the session
session_start();

// Ensure the user is logged in, otherwise redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle the reservation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yacht_id = $_POST['yacht_id'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Check if the dates are valid
    if ($from_date >= $to_date) {
        $error_message = "Klaida: Rezervacijos pradžios data turi būti anksčiau nei pabaigos data.";
    } else {
        // Check if the yacht is already reserved for the selected dates
        $sql = "SELECT * FROM rezervacijos WHERE jachta = :jachta
                AND ((laikotarpis_nuo <= :to_date AND laikotarpis_iki >= :from_date))";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jachta', $yacht_id);
        $stmt->bindParam(':from_date', $from_date);
        $stmt->bindParam(':to_date', $to_date);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error_message = "Klaida: Šiuo laikotarpiu jachta jau užimta.";
        } else {
            // Insert the reservation into the database
            $sql = "INSERT INTO rezervacijos (naudotojas, jachta, laikotarpis_nuo, laikotarpis_iki)
                    VALUES (:user_id, :yacht_id, :from_date, :to_date)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':yacht_id', $yacht_id);
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);

            if ($stmt->execute()) {
                $success_message = "Rezervacija sėkmingai atlikta!";
            } else {
                $error_message = "Klaida! Nepavyko atlikti rezervacijos.";
            }
        }
    }
}

// Fetch available yachts from the database
$yachts = [];
try {
    $sql = "SELECT id, pavadinimas FROM jachtos"; // Assuming `pavadinimas` is the yacht name column
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $yachts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Klaida: Nepavyko gauti jachtų sąrašo.";
}
?>

<!-- Display error or success messages -->
<?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>

<!-- Reservation form -->
<form action="" method="POST">
    <label for="yacht_id">Pasirinkite jachtą:</label><br>
    <select id="yacht_id" name="yacht_id" required>
        <!-- List of yachts will be dynamically populated here by PHP -->
        <?php foreach ($yachts as $yacht): ?>
            <option value="<?php echo $yacht['id']; ?>"><?php echo $yacht['pavadinimas']; ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="from_date">Rezervacijos pradžios data:</label><br>
    <input type="datetime-local" id="from_date" name="from_date" required><br><br>

    <label for="to_date">Rezervacijos pabaigos data:</label><br>
    <input type="datetime-local" id="to_date" name="to_date" required><br><br>

    <input type="submit" value="Rezervuoti">
</form>

<!-- Include footer or other sections if needed -->
</main>
</body>
</html>



