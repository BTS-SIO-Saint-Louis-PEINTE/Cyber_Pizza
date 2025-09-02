<?php
require_once 'config.php';

// Récupérer tous les clients
$clients = $pdo->query("SELECT * FROM clients ORDER BY nom")->fetchAll();

// Récupérer les allergènes pour chaque client
foreach ($clients as &$client) {
    $client['allergenes'] = $pdo->query("
        SELECT a.id, a.nom, a.code, a.couleur
        FROM allergenes a
        JOIN client_allergenes ca ON a.id = ca.allergene_id
        WHERE ca.client_id = {$client['id']}
    ")->fetchAll();
}
unset($client);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header">
        <h1>👥 Gestion des Clients</h1>
        <div class="auth">
            <span>Connecté en tant que <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="button">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <a href="index.php" class="button" style="margin-bottom: 20px;">← Retour aux pizzas</a>

        <?php if (isset($_GET['success'])): ?>
            <div class="message success"><?= $_GET['success'] ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="message error"><?= $_GET['error'] ?></div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Liste des clients</h2>
            <a href="client_add_edit.php" class="button success">+ Ajouter un client</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Carte fidélité</th>
                        <th>Points</th>
                        <th>Religion</th> 
                        <th>Allergènes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= $client['nom'] ?></td>
                            <td><?= $client['telephone'] ?></td>
                            <td><?= $client['adresse'] ?></td>
                            <td><?= $client['numero_carte'] ?: 'Aucune' ?></td>
                            <td><?= $client['points_clients'] ?></td>
                            <td><?= $client['religion'] ?: 'Aucune' ?></td>
                            



                            <td>
                                <?php foreach ($client['allergenes'] as $allergene): ?>
                                    <span class="allergen" style="background-color: <?= $allergene['couleur'] ?>">
                                        <?= $allergene['code'] ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (empty($client['allergenes'])): ?>
                                    Aucun
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <a href="client_add_edit.php?id=<?= $client['id'] ?>" class="button" style="background-color: var(--warning);">Modifier</a>
                                <a href="client_delete.php?id=<?= $client['id'] ?>" class="button error" onclick="return confirm('Voulez-vous vraiment supprimer ce client ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>