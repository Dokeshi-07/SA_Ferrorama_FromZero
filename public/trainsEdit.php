<?php
require_once __DIR__ . "/../infra/connection.php";

$brazilStates = [
    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
    'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
    'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$errors = [];
$saved = filter_input(INPUT_GET, 'saved', FILTER_VALIDATE_BOOLEAN);
$train = ['trainName' => '', 'trainType' => '', 'cargo' => '', 'trainState' => '', 'routeName' => ''];

if (!$id) {
    http_response_code(400);
    exit('A valid train id is required.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $train['trainName'] = trim($_POST['trainName'] ?? '');
    $train['trainType'] = trim($_POST['trainType'] ?? '');
    $train['cargo'] = trim($_POST['cargo'] ?? '');
    $train['trainState'] = trim($_POST['trainState'] ?? '');
    $train['routeName'] = trim($_POST['routeName'] ?? '');

    foreach ($train as $field => $value) {
        if ($value === '') $errors[] = ucfirst($field) . ' is required.';
    }

    if (!$errors) {
        $statement = $conexao->prepare(
            'UPDATE train SET trainName = ?, trainType = ?, cargo = ?, trainState = ?, routeName = ? WHERE id = ?'
        );
        $statement->bind_param(
            'sssssi',
            $train['trainName'],
            $train['trainType'],
            $train['cargo'],
            $train['trainState'],
            $train['routeName'],
            $id
        );
        $statement->execute();
        $statement->close();
        header('Location: trainsEdit.php?id=' . $id . '&saved=1');
        exit;
    }
} else {
    $statement = $conexao->prepare(
        'SELECT trainName, trainType, cargo, trainState, routeName FROM train WHERE id = ?'
    );
    $statement->bind_param('i', $id);
    $statement->execute();
    $result = $statement->get_result();
    $train = $result->fetch_assoc();
    $statement->close();
    if ($train === null) {
        http_response_code(404);
        exit('Train not found.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit train</title>
</head>
<body>
    <h1>Edit train</h1>
    <?php if ($saved): ?>
        <p role="status">Train changes saved successfully.</p>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endforeach; ?>
    
    <form method="post" action="trainsEdit.php?id=<?= (int) $id; ?>">
        <input type="hidden" name="id" value="<?= (int) $id; ?>">
        <label for="trainName">Name</label>
        <input type="text" id="trainName" name="trainName" value="<?= htmlspecialchars($train['trainName'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="trainType">Type</label>
        <input type="text" id="trainType" name="trainType" value="<?= htmlspecialchars($train['trainType'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="cargo">Cargo</label>
        <input type="text" id="cargo" name="cargo" value="<?= htmlspecialchars($train['cargo'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="trainState">State</label>
        <select id="trainState" name="trainState" required>
            <option value="">Select a state</option>
            <?php foreach ($brazilStates as $state): ?>
                <option value="<?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>" <?= $train['trainState'] === $state ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="routeName">Route</label>
        <input type="text" id="routeName" name="routeName" value="<?= htmlspecialchars($train['routeName'], ENT_QUOTES, 'UTF-8'); ?>" required>

        <button type="submit">Save changes</button>
    </form>
    <p><a href="trains.php"><button type="button">Go back</button></a></p>
</body>
</html>