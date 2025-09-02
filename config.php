<?php

// Vérifier que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');



try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Chemins et constantes
define('IMAGE_PATH', 'images/');
define('ALLERGENES', [
    1 => ['nom' => 'Gluten', 'code' => 'Glu', 'couleur' => '#ffebee', 'description' => 'Présent dans le blé, seigle, orge'],
    2 => ['nom' => 'Lait', 'code' => 'Lai', 'couleur' => '#fff8e1', 'description' => 'Inclut le lactose'],
    3 => ['nom' => 'Œufs', 'code' => 'Œuf', 'couleur' => '#e8f5e9', 'description' => 'Protéines d\'œuf'],
    4 => ['nom' => 'Poisson', 'code' => 'Poi', 'couleur' => '#e1f5fe', 'description' => 'Poissons et produits à base de poisson'],
    5 => ['nom' => 'Arachides', 'code' => 'Ara', 'couleur' => '#f3e5f5', 'description' => 'Cacahuètes'],
    6 => ['nom' => 'Soja', 'code' => 'Soj', 'couleur' => '#f1f8e9', 'description' => 'Fèves de soja et produits dérivés'],
    7 => ['nom' => 'Fruits à coque', 'code' => 'Fru', 'couleur' => '#fff3e0', 'description' => 'Noix, amandes, noisettes, etc.'],
    8 => ['nom' => 'Céléri', 'code' => 'Cél', 'couleur' => '#fce4ec', 'description' => 'Inclut le céleri-rave et les feuilles'],
    9 => ['nom' => 'Moutarde', 'code' => 'Mou', 'couleur' => '#f9fbe7', 'description' => 'Graines de moutarde et produits dérivés'],
    10 => ['nom' => 'Graines de sésame', 'code' => 'Ses', 'couleur' => '#f5f5f5', 'description' => 'Huile et graines de sésame'],
    11 => ['nom' => 'Anhydride sulfureux', 'code' => 'Sul', 'couleur' => '#e0f7fa', 'description' => 'Sulfites en concentration > 10mg/kg'],
    12 => ['nom' => 'Lupin', 'code' => 'Lup', 'couleur' => '#e8eaf6', 'description' => 'Farine et graines de lupin'],
    13 => ['nom' => 'Mollusques', 'code' => 'Mol', 'couleur' => '#e1bee7', 'description' => 'Moules, calamars, escargots, etc.']
]);

// Fonction pour obtenir les allergènes d'une pizza
function getPizzaAllergenes($pdo, $pizzaId) {
    $stmt = $pdo->prepare("
        SELECT a.id, a.nom, a.code, a.couleur
        FROM allergenes a
        JOIN pizza_allergene pa ON a.id = pa.allergene_id
        WHERE pa.pizza_id = ?
    ");
    $stmt->execute([$pizzaId]);
    return $stmt->fetchAll();
}

// Fonction pour vérifier si un utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Fonction pour rediriger
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
