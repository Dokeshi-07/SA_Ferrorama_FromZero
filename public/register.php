<?php
include("./infra/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferrorame Register</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
    <form method="POST" action="">
        <label for="Name">Name</label>
        <input type="text" id="userName" name="userName" required>

        <label for="Email">Email</label>
        <input type="email" id="userEmail" name="userEmail" required>

        <label for="Phone">Phone Number</label>
        <input type="tel" id="userPhone" pattern="[0-9]{2}-[0-9]{5}-[0-9]{4}" required>

        <label for="Password">Password</label>
        <input type="password" id="userPassword">

        <input type="submit" value="Submit">
    </form>
</body>
</html>