<?php
require_once 'config.php';

try {
    $id = $_GET['id'];

    // Supprimer les associations avec les allergènes
    $pdo->query("DELETE FROM client_allergenes WHERE client_id = $id");

    // Supprimer le client
    $pdo->query("DELETE FROM clients WHERE id = $id");

    header("Location: clients.php?success=" . urlencode("Client supprimé avec succès"));
} catch (Exception $e) {
    header("Location: clients.php?error=" . urlencode("Erreur lors de la suppression: " . $e->getMessage()));
}
?>
