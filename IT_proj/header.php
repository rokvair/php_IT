<?php
// header.php

// Start the session (only call once, ideally here)
session_start();

// Set UTF-8 character encoding for the entire page
header('Content-Type: text/html; charset=UTF-8'); // Ensure proper encoding for PHP
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set UTF-8 encoding for the page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- For older IE versions -->
    <title>Jachtų Nuomos Svetainė</title>
    
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="styles.css"> 
    
    <!-- Optional: Include a meta tag to handle the character set in a broader context -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Pagrindinis</a></li>
                <li><a href="jachts.php">Jachtos</a></li>
                <li><a href="rezervacijos.php">Rezervacijos</a></li>
                <li><a href="atsiliepimai.php">Atsiliepimai</a></li>
                <li><a href="messages.php">Žinutės</a></li>
                
                <!-- Check if the user is logged in -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_id'] < 1000): ?>
                        <!-- Show Owner Dashboard link only if logged in as owner -->
                        <li><a href="owner_dashboard.php">Savininko Skydelis</a></li>
                    <?php endif; ?>
                    
                    <!-- Show Logout link if user is logged in -->
                    <li><a href="logout.php" class="logout-button">Atsijungti</a></li>
                <?php else: ?>
                    <!-- Show Login and Register links if not logged in -->
                    <li><a href="login.php" class="login-button">Prisijungti</a></li>
                    <li><a href="register.php" class="register-button">Registruotis</a></li>
                    <!-- Show Owner Login link -->
                    <li><a href="login_owner.php" class="login-button">Savininko Prisijungimas</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
