<?php
require_once __DIR__ . "/../infra/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = trim($_POST['userName'] ?? '');
    $userEmail = trim($_POST['userEmail'] ?? '');
    $userPhone = trim($_POST['userPhone'] ?? '');
    $userPassword = $_POST['userPassword'] ?? '';

    $passwordHash = password_hash($userPassword, PASSWORD_DEFAULT);
    $isAdmin = 0;
    $statement = $conexao->prepare(
        'INSERT INTO users (userName, userEmail, userPhone, userPassword, adm) VALUES (?, ?, ?, ?, ?)'
    );

    if (!$statement) {
        die("Erro ao preparar o cadastro: " . $conexao->error);
    }

    $statement->bind_param('ssssi', $userName, $userEmail, $userPhone, $passwordHash, $isAdmin);

    if (!$statement->execute()) {
        die("Erro ao cadastrar usuário: " . $statement->error);
    }

    $statement->close();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferrorame Register</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
        <label for="Name">Name</label>
        <input type="text" id="userName" name="userName" required>

        <label for="Email">Email</label>
        <input type="email" id="userEmail" name="userEmail" required>

        <label for="Phone">Phone Number</label>
        <input type="tel" id="userPhone" name="userPhone" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" maxlength="15" inputmode="numeric" required>

        <label for="Password">Password</label>
        <input type="password" id="userPassword" name="userPassword" required>

        <input type="submit" value="Submit">
    </form>

    <script>
        const phoneInput = document.getElementById('userPhone');

        phoneInput.addEventListener('input', () => {
            let phone = phoneInput.value.replace(/\D/g, '').slice(0, 11);

            if (phone.length > 2) {
                phone = `(${phone.slice(0, 2)}) ${phone.slice(2)}`;
            } else if (phone.length > 0) {
                phone = `(${phone}`;
            }

            if (phone.length > 10) {
                phone = `${phone.slice(0, 10)}-${phone.slice(10)}`;
            }

            phoneInput.value = phone;
        });
    </script>
</body>
</html>