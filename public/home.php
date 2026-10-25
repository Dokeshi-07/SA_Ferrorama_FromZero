<?php
session_start();
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
    <?php include("../assets/presets/navbar.php"); ?>
    <?php if ($userName !== null): ?>
        <h2>You're logged in, <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>!</h2>
    <?php endif; ?>
    <button id="logoutButton"type="button">log out</button>
    <script>
        const logout = document.getElementById("logoutButton");
        button.addEventListener("click", () => 
            <?php
                session_destroy();
            ?>
            window.location.reload();
        )
    </script>
    <!-- <img src="../assets/img/trainStation5.jpg" alt="Train Station"> -->
</body>
</html>