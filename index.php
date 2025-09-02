<?php
require_once 'config.php';

// Récupérer toutes les pizzas
$pizzas = $pdo->query("SELECT * FROM pizzas ORDER BY nom")->fetchAll();

// Récupérer les allergènes pour chaque pizza
foreach ($pizzas as &$pizza) {
    $pizza['allergenes'] = $pdo->query(
        "
        SELECT a.id, a.nom, a.code, a.couleur
        FROM allergenes a
        JOIN pizza_allergene pa ON a.id = pa.allergene_id
        WHERE pa.pizza_id = " . $pizza['id']
    )->fetchAll();
}
unset($pizza);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des Pizzas</title>
    <script src="https://raw.githubusercontent.com/BTS-SIO-Saint-Louis-PEINTE/Cyber_Pizza/main/jquery.js"></script>




    <link rel="stylesheet" href="style.css">

    <style>
        .button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .button:hover {
            background-color: #b71c1c;
        }

        .allergen-tag {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            margin-right: 5px;
            font-size: 0.8em;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🍕 La pizza de Châteaulin</h1>
        <?php if (isLoggedIn()): ?>
            <div class="auth">
                <span>Connecté en tant que <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="button">Déconnexion</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="button">Connexion</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <a href="about.php" class="button" style="background-color: #1976d2;">À propos</a>
        <?php if (isLoggedIn()): ?>
            <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                <a href="add_edit.php" class="button">Ajouter une pizza</a>
                <a href="allergenes.php" class="button">Gérer les allergènes</a>
                <a href="clients.php" class="button">Gérer les clients</a>
                <a href="backup.php" class="button" style="background-color: #6a1b9a;">Sauvegarde BDD</a>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div style="padding: 10px; background: #4CAF50; color: white; margin-bottom: 20px;">
                <?= $_GET['success'] ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div style="padding: 10px; background: #f44336; color: white; margin-bottom: 20px;">
                <?= $_GET['error'] ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach ($pizzas as $pizza): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: white;">
                    <div class="pizza-image-container">
                        <img src="<?php echo ($pizza['image'] ?: 'default.jpg'); ?>"
                            alt="<?php echo $pizza['nom']; ?>"
                            class="pizza-image">
                    </div>
                    <h2 style="margin-top: 0;"><?= $pizza['nom'] ?></h2>
                    <p><?= $pizza['description'] ?></p>
                    <p><strong>Prix:</strong> <?= $pizza['prix'] ?> €</p>

                    <?php if (!empty($pizza['allergenes'])): ?>
                        <div style="margin: 10px 0;">
                            <strong>Allergènes:</strong>
                            <?php foreach ($pizza['allergenes'] as $allergene): ?>
                                <span class="allergen-tag" style="background-color: <?= $allergene['couleur'] ?>">
                                    <?= $allergene['code'] ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isLoggedIn()): ?>
                        <div style="margin-top: 10px;">
                            <a href="add_edit.php?id=<?= $pizza['id'] ?>" class="button" style="background: #ff9800;">Modifier</a>
                            <a href="delete.php?id=<?= $pizza['id'] ?>" class="button" style="background: #f44336;"
                                onclick="return confirm('Voulez-vous vraiment supprimer cette pizza ?')">Supprimer</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div style="margin-top: 40px; padding: 20px; background: #f9f9f9; border-radius: 8px; text-align: center;">
    <h3 style="margin-top: 0; color: #333;">🔍 Légende des allergènes</h3>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; max-width: 800px; margin: 0 auto;">
        <?php
        // Récupérer tous les allergènes pour la légende
        $stmt = $pdo->query("SELECT * FROM allergenes ORDER BY nom");
        $allergenesLegend = $stmt->fetchAll();

        foreach ($allergenesLegend as $allergene):
        ?>
            <div style="display: flex; align-items: center; gap: 8px; background: white; padding: 8px 12px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <span style="display: block; width: 20px; height: 20px; border-radius: 50%; background-color: <?= $allergene['couleur'] ?>;"></span>
                <div>
                    <strong><?= $allergene['code'] ?></strong>: <?= $allergene['nom'] ?><br>
                    <small style="color: #666; font-size: 0.8em;"><?= $allergene['description'] ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>

</html>