<?php
// add_yacht.php

// Include database connection
include('config.php');

// Start the session
session_start();

// Check if the user is logged in as an owner
if (!isset($_SESSION['user_id'])) {
    header('Location: login_owner.php'); // Redirect to owner login if not logged in
    exit;
}

$error_message = '';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $pavadinimas = $_POST['pavadinimas'];
    $foto = $_POST['foto'];
    $kaina = $_POST['kaina'];
    $aprasas = $_POST['aprasas'];
    $savId = $_SESSION['user_id']; // Get the logged-in owner's ID

    // Insert the new yacht into the database
    try {
        $sql = "INSERT INTO jachtos (pavadinimas, foto, kaina, savId, aprasas) VALUES (:pavadinimas, :foto, :kaina, :savId, :aprasas)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':pavadinimas', $pavadinimas);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':kaina', $kaina);
        $stmt->bindParam(':savId', $savId);
        $stmt->bindParam(':aprasas', $aprasas);

        $stmt->execute();

        // Redirect to the owner's dashboard after adding the yacht
        header('Location: owner_dashboard.php');
        exit;
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

?>

<!-- Include the header -->
<?php include('header.php'); ?>

<main>
    <h1>Add a New Yacht</h1>

    <!-- Display error message if there is one -->
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="add_yacht.php">
        <label for="pavadinimas">Yacht Name:</label><br>
        <input type="text" id="pavadinimas" name="pavadinimas" required><br><br>

        <label for="foto">Photo (Image Filename):</label><br>
        <input type="text" id="foto" name="foto" required><br><br>

        <label for="kaina">Price:</label><br>
        <input type="number" step="0.01" id="kaina" name="kaina" required><br><br>

        <label for="aprasas">Description:</label><br>
        <textarea id="aprasas" name="aprasas" required></textarea><br><br>

        <input type="submit" value="Add Yacht">
    </form>
</main>

</body>
</html>
