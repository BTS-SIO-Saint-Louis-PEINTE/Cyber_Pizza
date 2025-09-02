<?php
// Initialisation
session_start();
require_once 'config.php';  // Connexion à la DB


// Récupération des allergènes
$allergenes = $pdo->query("SELECT * FROM allergenes")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = (float)$_POST['prix'];
    $image = $_POST['image'];
    $selectedAllergenes = $_POST['allergenes'] ?? [];

    try {
        if ($id) {
            // Mise à jour
            $pdo->query("UPDATE pizzas SET
                nom = '$nom',
                description = '$description',
                prix = $prix,
                image = '$image'
                WHERE id = $id");

            // Suppression des anciens allergènes
            $pdo->query("DELETE FROM pizza_allergene WHERE pizza_id = $id");
            $message = "Pizza mise à jour avec succès!";
        } else {
            // Insertion
            $pdo->query("INSERT INTO pizzas (nom, description, prix, image)
                VALUES ('$nom', '$description', $prix, '$image')");
            $id = $pdo->lastInsertId();
            $message = "Pizza ajoutée avec succès!";
        }

        // Ajout des allergènes
        foreach ($selectedAllergenes as $allergeneId) {
            $pdo->query("INSERT INTO pizza_allergene (pizza_id, allergene_id)
                VALUES ($id, $allergeneId)");
        }

        // Redirection avec succès
        header("Location: add_edit.php?id=$id&success=1");
        exit();
    } catch (Exception $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}

// Récupération des données pour édition
$pizza = null;
$selectedAllergenes = [];
$success = isset($_GET['success']);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $pizza = $pdo->query("SELECT * FROM pizzas WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

    if ($pizza) {
        $selectedAllergenes = $pdo->query("
            SELECT allergene_id FROM pizza_allergene WHERE pizza_id = $id
        ")->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pizza ? 'Éditer' : 'Ajouter' ?> une pizza</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #d32f2f;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .allergene-checkbox {
            margin: 5px 0;
        }
        .allergene-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
        }
        button {
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #b71c1c;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #d32f2f;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $pizza ? 'Éditer' : 'Ajouter' ?> une pizza</h1>

        <?php if ($success): ?>
            <div class="success">Pizza enregistrée avec succès!</div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= $pizza['id'] ?? '' ?>">

            <div class="form-group">
                <label for="nom">Nom de la pizza:</label>
                <input type="text" id="nom" name="nom"
                       value="<?= $pizza['nom'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= $pizza['description'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="prix">Prix (€):</label>
                <input type="number" id="prix" name="prix"
                       step="0.01" min="0"
                       value="<?= $pizza['prix'] ?? '0.00' ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Image (nom du fichier):</label>
                <input type="text" id="image" name="image"
                       value="<?= $pizza['image'] ?? 'default.jpg' ?>">
            </div>

            <div class="form-group">
                <label>Allergènes:</label>
                <?php if (empty($allergenes)): ?>
                    <p>Aucun allergène disponible</p>
                <?php else: ?>
                    <?php foreach ($allergenes as $allergene): ?>
                        <div class="allergene-checkbox">
                            <input type="checkbox"
                                   name="allergenes[]"
                                   value="<?= $allergene['id'] ?>"
                                   <?= in_array($allergene['id'], $selectedAllergenes) ? 'checked' : '' ?>
                                   id="allergene-<?= $allergene['id'] ?>">
                            <label for="allergene-<?= $allergene['id'] ?>">
                                <span class="allergene-color" style="background-color: <?= $allergene['couleur'] ?>"></span>
                                <?= $allergene['nom'] ?> (<?= $allergene['code'] ?>)
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit"><?= $pizza ? 'Mettre à jour' : 'Ajouter' ?></button>
            <a href="index.php" class="back-link">Retour à la liste</a>
        </form>
    </div>
</body>
</html>
