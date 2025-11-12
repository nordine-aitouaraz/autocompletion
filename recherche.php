<?php
require_once __DIR__ . '/db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json; charset=utf-8');
    $term = $search;
    $start = $term . '%';
    $contains = '%' . $term . '%';

    $stmt1 = $pdo->prepare('SELECT id, name FROM items WHERE name LIKE :start ORDER BY name LIMIT 6');
    $stmt1->execute([':start' => $start]);
    $exact = $stmt1->fetchAll();

    $stmt2 = $pdo->prepare('SELECT id, name FROM items WHERE name LIKE :contains AND name NOT LIKE :start ORDER BY name LIMIT 6');
    $stmt2->execute([':contains' => $contains, ':start' => $start]);
    $containsArr = $stmt2->fetchAll();

    echo json_encode(['exact' => $exact, 'contains' => $containsArr]);
    exit;
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Résultats pour <?= htmlspecialchars($search) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <div class="header-inner">
        <a href="index.php" class="home-btn">Accueil</a>
    </div>
    </header>

    <main>
        <h2>Résultats pour : "<?= htmlspecialchars($search) ?>"</h2>

        <?php if ($search === ''): ?>
            <p>Veuillez saisir un terme de recherche.</p>
        <?php else:
            $start = $search . '%';
            $contains = '%' . $search . '%';
            $stmt = $pdo->prepare('SELECT * FROM items WHERE name LIKE :contains ORDER BY (name LIKE :start) DESC, name LIMIT 100');
            $stmt->execute([':contains' => $contains, ':start' => $start]);
            $rows = $stmt->fetchAll();
        ?>
            <?php if (count($rows) === 0): ?>
                <p>Aucun résultat.</p>
            <?php else: ?>
                <ul class="results">
                <?php foreach ($rows as $row): ?>
                    <li>
                        <a href="element.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a>
                        <p class="small-desc"><?= htmlspecialchars(substr($row['description'], 0, 150)) ?><?= strlen($row['description'])>150? '...' : '' ?></p>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <script src="script.js"></script>
</body>
</html>
