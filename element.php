<?php
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo 'Identifiant invalide.';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM items WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$item = $stmt->fetch();
if (!$item) {
    http_response_code(404);
    echo 'Élément non trouvé.';
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= htmlspecialchars($item['name']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <div class="header-inner">
        <a href="index.php" class="home-btn">Accueil</a>
    </div>
</header>
    <main>
        <h2><?= htmlspecialchars($item['name']) ?></h2>
        <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
        
    </main>

    <script src="script.js"></script>
</body>
</html>
