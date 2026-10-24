<?php
include("./infra/connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = trim($_POST['userName'] ?? '');
    $userPassword = $_POST['userPassword'] ?? '';

    $stmt = $connection->prepare(
        'SELECT id, name, password FROM users WHERE name = ? LIMIT 1'
    );
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($userPassword, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: index.php');
        exit;
    }

    $loginError = 'Invalid username or password.';
    $stmt->close();
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferrorame Login</title>
</head>
<body>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
        <!-- name form -->
        <label for="Name">Name</label>
        <input type="text" id="userName" name="userName" required>
        <!-- password form -->
        <label for="Password">Password</label>
        <input type="password" id="userPassword" name="userPassword" required>
        <!-- submit button -->
        <input type="submit" value="Login">

        <?php if (isset($loginError)): ?>
            <p><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

    </form>
</body>
</html>

