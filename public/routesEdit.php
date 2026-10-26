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
$route = ['routeName' => '', 'routeState' => '', 'routeLocal' => ''];

if (!$id) {
    http_response_code(400);
    exit('A valid route id is required.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $route['routeName'] = trim($_POST['routeName'] ?? '');
    $route['routeState'] = trim($_POST['routeState'] ?? '');
    $route['routeLocal'] = trim($_POST['routeLocal'] ?? '');

    foreach ($route as $field => $value) {
        if ($value === '') $errors[] = ucfirst($field) . ' is required.';
    }

    if (!in_array($route['routeState'], $brazilStates, true)) {
        $errors[] = 'Select a valid Brazilian state.';
    }

    if (!$errors) {
        $statement = $conexao->prepare(
            'UPDATE routes SET routeName = ?, routeState = ?, routeLocal = ? WHERE id = ?'
        );

        if (!$statement) {
            $errors[] = 'Could not prepare the route update.';
        } else {
            $statement->bind_param(
                'sssi',
                $route['routeName'],
                $route['routeState'],
                $route['routeLocal'],
                $id
            );

            if ($statement->execute()) {
                $statement->close();
                header('Location: routesEdit.php?id=' . $id . '&saved=1');
                exit;
            }

            $errors[] = $statement->errno === 1062
                ? 'A route with this name already exists.'
                : 'Could not update the route.';
            $statement->close();
        }
    }
} else {
    $statement = $conexao->prepare(
        'SELECT routeName, routeState, routeLocal FROM routes WHERE id = ?'
    );
    $statement->bind_param('i', $id);
    $statement->execute();
    $result = $statement->get_result();
    $route = $result->fetch_assoc();
    $statement->close();

    if ($route === null) {
        http_response_code(404);
        exit('Route not found.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit route</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="data-page">
    <?php include("../assets/presets/navbar.php"); ?>

    <main class="data-layout">
        <section class="data-form-panel">
            <h1>Edit route</h1>

            <?php if ($saved): ?>
                <p class="form-message form-success" role="status">Route changes saved successfully.</p>
            <?php endif; ?>

            <?php foreach ($errors as $error): ?>
                <p class="form-message form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>

            <form method="post" action="routesEdit.php?id=<?= (int) $id; ?>">
                <input type="hidden" name="id" value="<?= (int) $id; ?>">

                <label for="routeName">Name</label>
                <input type="text" id="routeName" name="routeName" value="<?= htmlspecialchars($route['routeName'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="routeState">State</label>
                <select id="routeState" name="routeState" required>
                    <option value="">Select a state</option>
                    <?php foreach ($brazilStates as $state): ?>
                        <option value="<?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>" <?= $route['routeState'] === $state ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="routeLocal">Location</label>
                <input type="text" id="routeLocal" name="routeLocal" value="<?= htmlspecialchars($route['routeLocal'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <button type="submit">Save changes</button>
            </form>

            <p><a href="routes.php"><button type="button">Go back</button></a></p>
        </section>
    </main>
</body>
</html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>