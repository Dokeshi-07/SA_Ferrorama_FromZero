<?php
include("./infra/connection.php");
session_start();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferrorame Login</title>
</head>
<body>
    <form method="POST" action="">
        <!-- name form -->
        <label for="Name">Name</label>
        <input type="text" id="nameUser" name="userName" required>
        <!-- password form -->
        <label for="Password">Passoword</label>
        <input type="password" id="userPassword" name="userPassoword" required>
        <!-- submit button -->
        <input type="submit" value="Login">

    </form>
</body>
</html>

<?php
    sub
?>