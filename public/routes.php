<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
        $routeName = trim($_POST['routeName'] ?? '');
        $routeState = trim($_POST['routeState'] ?? '');
        $routeLocal = trim($_POST['routeLocal'] ?? '');

        if ($routeName === '' || $routeState === '' || $routeLocal === '') {
            $formError = 'Fill in all route fields.';
        } else {
            $statement = $conexao->prepare(
                'INSERT INTO routes (routeName, routeState, routeLocal) VALUES (?, ?, ?)'
            );

            if (!$statement) {
                $formError = 'Could not prepare the route.';
            } else {
                $statement->bind_param('sss', $routeName, $routeState, $routeLocal);

                if ($statement->execute()) {
                    $formSuccess = 'Route added successfully.';
                } elseif ($statement->errno === 1062) {
                    $formError = 'A route with this name already exists.';
                } else {
                    $formError = 'Could not add the route.';
                }

                $statement->close();
            }
        }
    }

    $routes = $conexao->query(
        'SELECT id, routeName, routeState, routeLocal FROM routes ORDER BY id DESC'
    );
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Routes</title>
        <link rel="stylesheet" href="../assets/style/style.css">
    </head>
    <body class="data-page">
        <?php include("../assets/presets/navbar.php"); ?>

        <main class="data-layout">
            <section class="data-form-panel">
                <h1>Add route</h1>
                <?php if ($formError !== null): ?>
                    <p class="form-message form-error"><?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <?php if ($formSuccess !== null): ?>
                    <p class="form-message form-success"><?= htmlspecialchars($formSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>

                <form method="POST" action="routes.php">
                    <label for="routeName">Name</label>
                    <input type="text" id="routeName" name="routeName" required>

                    <label for="routeState">State</label>
                    <select id="routeState" name="routeState" required>
                        <option value="">Select a state</option>
                        <?php foreach ($brazilStates as $state): ?>
                            <option value="<?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="routeLocal">Location</label>
                    <input type="text" id="routeLocal" name="routeLocal" required>

                    <button type="submit">Add route</button>
                </form>
            </section>

            <section class="data-table-panel">
                <h2>Routes</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>State</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($route = $routes->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($route['routeName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($route['routeState'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($route['routeLocal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                       <td><a class="edit-button" href="routesEdit.php?id=<?= (int) $route['id']; ?>">Edit</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>
    </html>
    
</body>
</html>