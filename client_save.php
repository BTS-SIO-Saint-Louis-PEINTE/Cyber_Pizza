<?php
require_once 'config.php';

$id = $_POST['id'] ?? null;
$nom = $_POST['nom'];
$telephone = $_POST['telephone'];
$adresse = $_POST['adresse'];
$numero_carte = $_POST['numero_carte'];
$points_clients = $_POST['points_clients'];
$allergenes = $_POST['allergenes'] ?? [];

try {
    // Remplacez les requêtes UPDATE et INSERT par :
    if ($id) {
        // Mise à jour
        $pdo->query("
        UPDATE clients SET
            nom = '$nom',
            telephone = '$telephone',
            adresse = '$adresse',
            numero_carte = '$numero_carte',
            points_clients = $points_clients,
            religion = '{$_POST['religion']}'
        WHERE id = $id
    ");
    } else {
        // Insertion
        $pdo->query("
        INSERT INTO clients
            (nom, telephone, adresse, numero_carte, points_clients, religion)
        VALUES
            ('$nom', '$telephone', '$adresse', '$numero_carte', $points_clients, '{$_POST['religion']}')
    ");
        $id = $pdo->lastInsertId();
    }


    // Gestion des allergènes
    $pdo->query("DELETE FROM client_allergenes WHERE client_id = $id");
    foreach ($allergenes as $allergene_id) {
        $pdo->query("INSERT INTO client_allergenes (client_id, allergene_id) VALUES ($id, $allergene_id)");
    }

    header("Location: clients.php?success=" . urlencode($id ? "Client mis à jour" : "Client ajouté avec succès"));
} catch (Exception $e) {
    header("Location: clients.php?error=" . urlencode("Erreur: " . $e->getMessage()));
}
