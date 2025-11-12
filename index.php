<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Accueil - Recherche</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <div class="header-inner">
        <a href="index.php" class="home-btn">Accueil</a>
    </div>
</header>

    <main class="home">
        <h1>Recherche un animal</h1>
        <form action="recherche.php" method="get" class="big-search">
            <input type="text" name="search" id="search-input-main" class="autocomplete" placeholder="Essayez : chien, chat, cheval, dauphin..." autocomplete="off" />
            <button type="submit">Rechercher</button>
            <div id="suggestions-main" class="suggestions" aria-live="polite"></div>
        </form>
    </main>

    <script src="script.js"></script>
</body>
</html>
