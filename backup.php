<?php
require_once 'config.php';

// Fonction pour créer une sauvegarde
function faireBackup($filename) {
    $script_sav = 'backup_script.php'; // Chemin relatif
    $php_path = 'php';

    // Passer seulement le nom de fichier comme argument
    $command = "$php_path $script_sav " . escapeshellarg($filename);

    $output = [];
    $returnVar = 0;

    exec($command, $output, $returnVar);

    return $returnVar == 0;
}

// Traiter la demande de sauvegarde
if (isset($_GET['backup'])) {
    $backupFile = 'backups/pizza_db_backup_' . date('Y-m-d_H-i-s') . '.sql';

    // Créer le dossier backups s'il n'existe pas
    if (!file_exists('backups')) {
        mkdir('backups', 0777, true);
    }

    try {
        $success = faireBackup($backupFile);
        if ($success) {
            $message = "Sauvegarde créée avec succès";
        } else {
            throw new Exception("Échec de la création de la sauvegarde");
        }
    } catch (Exception $e) {
        $message = "Erreur lors de la création de la sauvegarde : " . $e->getMessage();
    }
}

// Traiter la demande de restauration
if (isset($_POST['restore']) && isset($_FILES['backup_file'])) {
    $file = $_FILES['backup_file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $tmpName = $file['tmp_name'];
        $content = file_get_contents($tmpName);

        try {
            // Exécuter chaque requête SQL
            $queries = explode(';', $content);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $pdo->exec($query);
                }
            }

            $message = "Base de données restaurée avec succès!";
        } catch (Exception $e) {
            $message = "Erreur lors de la restauration : " . $e->getMessage();
        }
    } else {
        $message = "Erreur lors du téléchargement du fichier.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sauvegarde de la base de données</title>
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
            padding: 20px;
        }

        h1 {
            color: #d32f2f;
            text-align: center;
        }

        .backup-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .backup-list {
            margin-top: 20px;
        }

        .backup-list ul {
            list-style: none;
            padding: 0;
        }

        .backup-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .backup-list a {
            color: #d32f2f;
            text-decoration: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="file"] {
            margin-top: 5px;
        }

        .button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #b71c1c;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>💾 Sauvegarde de la base de données</h1>
        <a href="index.php" class="button">← Retour à l'accueil</a>

        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, 'Erreur') !== false ? 'error' : 'success'; ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="backup-section">
            <h2>Créer une sauvegarde</h2>
            <p>Créez une sauvegarde complète de la base de données.</p>
            <a href="?backup=1" class="button">Créer une sauvegarde</a>
        </div>

        <div class="backup-section">
            <h2>Restaurer une sauvegarde</h2>
            <p>Restaurez la base de données à partir d'un fichier de sauvegarde.</p>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="backup_file">Fichier de sauvegarde (.sql) :</label>
                    <input type="file" id="backup_file" name="backup_file" required>
                </div>
                <button type="submit" name="restore" class="button" style="background-color: #ff9800;">
                    Restaurer la sauvegarde
                </button>
            </form>
        </div>

        <?php if (file_exists('backups') && is_dir('backups')): ?>
            <div class="backup-section">
                <h2>Sauvegardes existantes</h2>
                <div class="backup-list">
                    <ul>
                        <?php
                        $backups = glob('backups/*.sql');
                        rsort($backups); // Trier par date décroissante

                        if (empty($backups)) {
                            echo '<li>Aucune sauvegarde disponible</li>';
                        } else {
                            foreach ($backups as $backup) {
                                $filename = basename($backup);
                                $date = str_replace(['pizza_db_backup_', '.sql'], '', $filename);
                                $date = str_replace('_', ' ', $date);
                                $date = str_replace('-', '/', $date);
                                echo "<li>$date - <a href='$backup' download>Télécharger</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
