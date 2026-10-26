<?php
require_once __DIR__ . "/../infra/connection.php";

$brazilStates = [
    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
    'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
    'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];

$formError = null;
$formSuccess = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainName = trim($_POST['trainName'] ?? '');
    $trainType = trim($_POST['trainType'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $trainState = trim($_POST['trainState'] ?? '');
    $routeName = trim($_POST['routeName'] ?? '');

    if ($trainName === '' || $trainType === '' || $cargo === '' || $trainState === '' || $routeName === '') {
        $formError = 'Fill in all train fields.';
    } else {
        $statement = $conexao->prepare(
            'INSERT INTO train (trainName, trainType, cargo, trainState, routeName) VALUES (?, ?, ?, ?, ?)'
        );

        if (!$statement) {
            $formError = 'Could not prepare the train.';
        } else {
            $statement->bind_param('sssss', $trainName, $trainType, $cargo, $trainState, $routeName);

            if ($statement->execute()) {
                $formSuccess = 'Train added successfully.';
            } elseif ($statement->errno === 1452) {
                $formError = 'Select an existing route for the train.';
            } else {
                $formError = 'Could not add the train.';
            }

            $statement->close();
        }
    }
}

$routes = $conexao->query('SELECT routeName FROM routes ORDER BY routeName');
$trains = $conexao->query(
    'SELECT id, trainName, trainType, cargo, trainState, routeName FROM train ORDER BY id DESC'
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trains</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="data-page">
    <?php include("../assets/presets/navbar.php"); ?>

    <main class="data-layout">
        <section class="data-form-panel">
            <h1>Add train</h1>
            <?php if ($formError !== null): ?>
                <p class="form-message form-error"><?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <?php if ($formSuccess !== null): ?>
                <p class="form-message form-success"><?= htmlspecialchars($formSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form method="POST" action="trains.php">
                <label for="trainName">Name</label>
                <input type="text" id="trainName" name="trainName" required>

                <label for="trainType">Type</label>
                <input type="text" id="trainType" name="trainType" required>

                <label for="cargo">Cargo</label>
                <input type="text" id="cargo" name="cargo" required>

                <label for="trainState">State</label>
                <select id="trainState" name="trainState" required>
                    <option value="">Select a state</option>
                    <?php foreach ($brazilStates as $state): ?>
                        <option value="<?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="routeName">Route</label>
                <select id="routeName" name="routeName" required>
                    <option value="">Select a route</option>
                    <?php while ($route = $routes->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($route['routeName'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($route['routeName'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Add train</button>
            </form>
        </section>

        <section class="data-table-panel">
            <h2>Trains</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Cargo</th>
                            <th>State</th>
                            <th>Route</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($train = $trains->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($train['trainName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($train['trainType'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($train['cargo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($train['trainState'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($train['routeName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><a class="edit-button" href="trainsEdit.php?id=<?= (int) $train['id']; ?>">Edit</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>