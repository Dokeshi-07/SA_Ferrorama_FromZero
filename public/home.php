<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: home.php');
    exit;
}

$userName = $_SESSION['userName'] ?? null;
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferrorame Home</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="bodyHome">
    <!-- navbar -->
    <?php include("../assets/presets/navbar.php"); ?>

    <!-- log in and register -->
    <a href="login.php">Login</a>
    <br>
    <a href="register.php">Register</a>

    <!-- logout button -->
    <form method="POST" action="home.php">
        <button id="logoutButton" type="submit" name="logout" value="1">Log out</button>
    </form>

    <!-- logged in content -->
    <?php if ($userName !== null): ?>
        <h2>You're logged in, <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>!</h2>
        <a href="trains.php">trains page</a> <br>
        <a href="routes.php">routes page</a>
    <?php endif; ?>
</body>
</html>