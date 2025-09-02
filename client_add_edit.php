<?php
require_once 'config.php';

$isEditing = isset($_GET['id']);
$client = [
    'id' => '',
    'nom' => '',
    'telephone' => '',
    'adresse' => '',
    'numero_carte' => '',
    'points_clients' => 0
];
$selectedAllergenes = [];

if ($isEditing) {
    $client = $pdo->query("SELECT * FROM clients WHERE id = {$_GET['id']}")->fetch();
    $selectedAllergenes = array_column($pdo->query("
        SELECT allergene_id FROM client_allergenes WHERE client_id = {$_GET['id']}
    ")->fetchAll(), 'allergene_id');
}

// Récupérer tous les allergènes
$allergenes = $pdo->query("SELECT * FROM allergenes ORDER BY nom")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEditing ? 'Modifier' : 'Ajouter' ?> un client</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header">
        <h1><?= $isEditing ? '📝 Modifier' : '➕ Ajouter' ?> un client</h1>
        <div class="auth">
            <span>Connecté en tant que <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="button">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <a href="clients.php" class="button" style="margin-bottom: 20px;">← Retour à la liste</a>

        <div class="form-container">
            <form action="client_save.php" method="post">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">

                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input type="text" id="nom" name="nom" value="<?= $client['nom'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="<?= $client['telephone'] ?>">
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <textarea id="adresse" name="adresse"><?= $client['adresse'] ?></textarea>
                </div>

                <div class="form-group">
                    <label for="numero_carte">Numéro de carte fidélité</label>
                    <input type="text" id="numero_carte" name="numero_carte" value="<?= $client['numero_carte'] ?>">
                </div>

                <div class="form-group">
                    <label for="religion">Religion (pour restrictions alimentaires)</label>
                    <select id="religion" name="religion">
                        <option value="">Aucune préférence</option>
                        <option value="halal" <?= $client['religion'] == 'Halal' ? 'selected' : '' ?>>Halal</option>
                        <option value="casher" <?= $client['religion'] == 'Kasher' ? 'selected' : '' ?>>Kasher</option>
                        <option value="Hindou" <?= $client['religion'] == 'Hindou' ? 'selected' : '' ?>>Indou</option>
                        <option value="Jaïnisme" <?= $client['religion'] == 'Jaïnisme' ? 'selected' : '' ?>>Jaïnisme</option>
                        <option value="vegetarien" <?= $client['religion'] == 'vegetarien' ? 'selected' : '' ?>>Végétarien (pour raisons religieuses)</option>
                        <option value="autre" <?= $client['religion'] == 'autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="points_clients">Points de fidélité</label>
                    <input type="number" id="points_clients" name="points_clients" value="<?= $client['points_clients'] ?>">
                </div>

                <div class="form-group">
                    <label>Allergènes</label>
                    <div class="allergen-list">
                        <?php foreach ($allergenes as $allergene): ?>
                            <div class="allergen-item">
                                <input type="checkbox"
                                    id="allergene-<?= $allergene['id'] ?>"
                                    name="allergenes[]"
                                    value="<?= $allergene['id'] ?>"
                                    <?= in_array($allergene['id'], $selectedAllergenes) ? 'checked' : '' ?>>
                                <label for="allergene-<?= $allergene['id'] ?>" class="allergen-label">
                                    <span class="allergen-color" style="background-color: <?= $allergene['couleur'] ?>"></span>
                                    <?= $allergene['nom'] ?> (<?= $allergene['code'] ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="button success">
                        <?= $isEditing ? 'Mettre à jour' : 'Ajouter' ?>
                    </button>
                    <a href="clients.php" class="button" style="background-color: var(--gray-color);">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>