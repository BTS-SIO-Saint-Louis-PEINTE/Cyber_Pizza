<?php
require_once 'config.php';

// Fonction pour créer une sauvegarde
function createBackup($filename) {
    global $pdo;

    // Récupérer toutes les tables
    $tables = [];
    $result = $pdo->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    // Contenu du fichier SQL
    $output = "-- Sauvegarde de la base de données pizza_db\n";
    $output .= "-- Généré le : " . date('Y-m-d H:i:s') . "\n\n";

    foreach ($tables as $table) {
        // Structure de la table
        $output .= "--\n-- Structure de la table `$table`\n--\n\n";
        $output .= "DROP TABLE IF EXISTS `$table`;\n";

        $result = $pdo->query("SHOW CREATE TABLE `$table`");
        $row = $result->fetch(PDO::FETCH_NUM);
        $output .= $row[1] . ";\n\n";

        // Données de la table
        $output .= "--\n-- Déchargement des données de la table `$table`\n--\n\n";

        $result = $pdo->query("SELECT * FROM `$table`");
        $numFields = $result->columnCount();

        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $output .= "INSERT INTO `$table` VALUES(";
            $values = [];

            for ($i = 0; $i < $numFields; $i++) {
                if (isset($row[$i])) {
                    $values[] = $pdo->quote($row[$i]);
                } else {
                    $values[] = 'NULL';
                }
            }

            $output .= implode(',', $values) . ");\n";
        }

        $output .= "\n";
    }

    // Enregistrer le fichier
    file_put_contents($filename, $output);

    return $filename;
}

// Récupérer les arguments passés en ligne de commande
$filename = $argv[1] ?? '';

if (empty($filename)) {
    die("Nom de fichier manquant\n");
}

try {
    $backupFile = createBackup($filename);
    echo "Sauvegarde créée avec succès: $backupFile\n";
} catch (Exception $e) {
    die("Erreur: " . $e->getMessage() . "\n");
}
