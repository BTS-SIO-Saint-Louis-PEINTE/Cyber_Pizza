<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Récupérer tous les allergènes
$stmt = $pdo->query("SELECT * FROM allergenes ORDER BY nom");
$allergenes = $stmt->fetchAll();

// Initialisation des variables pour le formulaire
$editAllergene = [
    'id' => '',
    'nom' => '',
    'code' => '',
    'couleur' => '',
    'description' => ''
];
$isEditing = false;
$error = '';
$success = $_GET['success'] ?? '';

// Traitement de l'ajout/modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $code = strtoupper(trim($_POST['code']));
    $couleur = trim($_POST['couleur']);
    $description = trim($_POST['description']);

    if (empty($nom) || empty($code)) {
        $error = "Le nom et le code sont obligatoires";
    } else {
        try {
            if (isset($_POST['add'])) {
                // Ajout d'un nouvel allergène
                $stmt = $pdo->prepare("INSERT INTO allergenes (nom, code, couleur, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $code, $couleur, $description]);
                redirect('allergenes.php?success=Allergène ajouté avec succès');
            } elseif (isset($_POST['update'])) {
                // Mise à jour d'un allergène
                $id = $_POST['id'];
                $stmt = $pdo->prepare("
                    UPDATE allergenes
                    SET nom = ?, code = ?, couleur = ?, description = ?
                    WHERE id = ?
                ");
                $stmt->execute([$nom, $code, $couleur, $description, $id]);
                redirect('allergenes.php?success=Allergène mis à jour avec succès');
            }
        } catch (PDOException $e) {
            $error = "Erreur: " . $e->getMessage();
        }
    }
}

// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $pdo->beginTransaction();

        // Supprimer les associations avec les pizzas
        $pdo->prepare("DELETE FROM pizza_allergene WHERE allergene_id = ?")->execute([$id]);

        // Supprimer l'allergène
        $pdo->prepare("DELETE FROM allergenes WHERE id = ?")->execute([$id]);

        $pdo->commit();
        redirect('allergenes.php?success=Allergène supprimé avec succès');
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

// Préparation pour l'édition
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM allergenes WHERE id = ?");
    $stmt->execute([$id]);
    $editAllergene = $stmt->fetch() ?: $editAllergene;
    $isEditing = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Allergènes</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function updateColorPreview() {
            const colorInput = document.getElementById('couleur');
            const colorPreview = document.getElementById('color-preview');
            colorPreview.style.backgroundColor = colorInput.value;
        }

        function confirmDelete(id) {
            if (confirm('Voulez-vous vraiment supprimer cet allergène ?')) {
                window.location.href = 'allergenes.php?delete=' + id;
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>🚨 Gestion des Allergènes</h1>
        <div class="auth">
            <span>Connecté en tant que <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="button">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <a href="index.php" class="button" style="margin-bottom: 20px; display: inline-block;">← Retour aux pizzas</a>

        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            <!-- Formulaire d'ajout/modification -->
            <div class="form-container">
                <h2><?php echo $isEditing ? 'Modifier' : 'Ajouter'; ?> un allergène</h2>

                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $editAllergene['id']; ?>">

                    <div class="form-group">
                        <label for="nom">Nom complet:</label>
                        <input type="text" id="nom" name="nom"
                               value="<?php echo $editAllergene['nom']; ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="code">Code (3 lettres):</label>
                        <input type="text" id="code" name="code"
                               value="<?php echo $editAllergene['code']; ?>"
                               maxlength="3"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="couleur">Couleur:</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="color" id="couleur" name="couleur"
                                   value="<?php echo $editAllergene['couleur'] ?: '#ffebee'; ?>"
                                   onchange="updateColorPreview()">
                            <div id="color-preview" style="
                                width: 30px;
                                height: 30px;
                                border-radius: 50%;
                                background-color: <?php echo $editAllergene['couleur'] ?: '#ffebee'; ?>;
                                border: 1px solid #ccc;
                            "></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"><?php echo $editAllergene['description']; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <?php if ($isEditing): ?>
                            <button type="submit" name="update" class="button success">Mettre à jour</button>
                            <a href="allergenes.php" class="button warning">Annuler</a>
                        <?php else: ?>
                            <button type="submit" name="add" class="button success">Ajouter</button>
                            <button type="reset" class="button warning">Réinitialiser</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Liste des allergènes existants -->
            <div class="form-container">
                <h2>Liste des allergènes</h2>

                <?php if (empty($allergenes)): ?>
                    <p class="message">Aucun allergène enregistré.</p>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Couleur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allergenes as $allergene): ?>
                                    <tr>
                                        <td>
                                            <span class="allergen"
                                                  style="background-color: <?php echo $allergene['couleur']; ?>">
                                                <?php echo $allergene['code']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $allergene['nom']; ?></td>
                                        <td><?php echo $allergene['description'] ?: '-'; ?></td>
                                        <td>
                                            <div style="
                                                width: 20px;
                                                height: 20px;
                                                border-radius: 50%;
                                                background-color: <?php echo $allergene['couleur']; ?>;
                                                display: inline-block;
                                            "></div>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="allergenes.php?edit=<?php echo $allergene['id']; ?>"
                                               class="button warning" style="padding: 5px 10px; font-size: 12px;">
                                                Modifier
                                            </a>
                                            <button onclick="confirmDelete(<?php echo $allergene['id']; ?>)"
                                                    class="button error" style="padding: 5px 10px; font-size: 12px;">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
