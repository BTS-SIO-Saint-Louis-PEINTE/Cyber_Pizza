<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('index.php');
}

try {
    // Supprimer les associations avec les allergènes
    $pdo->prepare("DELETE FROM pizza_allergene WHERE pizza_id = ?")->execute([$_GET['id']]);

    // Supprimer la pizza
    $stmt = $pdo->prepare("DELETE FROM pizzas WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    redirect('index.php?success=Pizza supprimée avec succès');
} catch (PDOException $e) {
    redirect('index.php?error=Erreur lors de la suppression: ' . $e->getMessage());
}
