<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - La Pizza de Châteaulin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .about-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .section h2 {
            color: #d32f2f;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .stat-item {
            text-align: center;
            flex: 1;
            min-width: 150px;
            padding: 15px;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #d32f2f;
        }

        .team-member {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
        }

        .team-member img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }

        .team-info h3 {
            margin: 0 0 5px 0;
            color: #d32f2f;
        }

        .team-info p {
            margin: 0;
            color: #666;
        }

        .rgpd-section {
            border: 1px solid #ddd;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
        }

        .rgpd-section h3 {
            color: #d32f2f;
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🍕 La Pizza de Châteaulin</h1>
        <?php if (isLoggedIn()): ?>
            <div class="auth">
                <span>Connecté en tant que <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="button">Déconnexion</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="button">Connexion</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <a href="index.php" class="button" style="margin-bottom: 20px;">← Retour à l'accueil</a>

        <div class="about-container">
            <div class="section">
                <h2>🏠 Notre Histoire</h2>
                <p>
                    Fondée en 1995 au cœur de Châteaulin, <strong>La Pizza de Châteaulin</strong> est devenue une institution locale,
                    réputée pour ses pizzas artisanales cuites au feu de bois. Ce qui a commencé comme un petit restaurant
                    familial est aujourd'hui une entreprise prospère employant 15 personnes et servant plus de 500 pizzas
                    par jour.
                </p>
                <p>
                    Notre secret ? Des ingrédients frais et locaux, une pâte pétrie chaque matin, et une passion inégalée
                    pour l'art de la pizza. Nous sommes fiers de perpétuer les recettes traditionnelles tout en innovant
                    avec des créations originales qui ravissent nos clients.
                </p>

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2659.497248122764!2d-4.088062723273804!3d48.19703744701922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48113199d669b5df%3A0x8926e4b28e0f27da!2sColl%C3%A8ge%20Lyc%C3%A9e%20BTS%20-%20Saint-Louis!5e0!3m2!1sfr!2sfr!4v1756549490848!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <div class="section">
                <h2>📊 Nos Chiffres Clés</h2>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            // Compter le nombre de pizzas
                            $stmt = $pdo->query("SELECT COUNT(*) FROM pizzas");
                            echo $stmt->fetchColumn();
                            ?>
                        </div>
                        <div>Pizzas au menu</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            // Compter le nombre de clients (exemple)
                            $stmt = $pdo->query("SELECT COUNT(*) FROM clients");
                            echo $stmt->fetchColumn();
                            ?>
                        </div>
                        <div>Clients fidèles</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">15</div>
                        <div>Employés</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">1995</div>
                        <div>Année de fondation</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>👨‍🍳 Notre Équipe</h2>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Yann">
                    <div class="team-info">
                        <h3>Yann</h3>
                        <p>Fondateur et Chef Pizzaiolo</p>
                        <p>Maître artisan depuis 30 ans</p>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Marie">
                    <div class="team-info">
                        <h3>Marie</h3>
                        <p>Gérante et Responsable Qualité</p>
                        <p>Experte en gestion et normes HACCP</p>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="Pierre">
                    <div class="team-info">
                        <h3>Pierre</h3>
                        <p>Chef de Cuisine</p>
                        <p>Inventeur de la pizza Hawaienne</p>
                    </div>
                </div>
            </div>



            <div class="section">
                <h2>📍 Nos Coordonnées</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                    <div style="flex: 1; min-width: 200px;">
                        <h3>📍 Adresse</h3>
                        <p>
                            La Pizza de Châteaulin<br>
                            63 Grand Rue<br>
                            29150 Châteaulin<br>
                            France
                        </p>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <h3>🕒 Horaires</h3>
                        <p>
                            <strong>Lundi - Jeudi:</strong> 11h30 - 14h00 / 18h30 - 22h30<br>
                            <strong>Vendredi - Samedi:</strong> 11h30 - 23h00<br>
                            <strong>Dimanche:</strong> 18h30 - 22h00<br>
                            <em>Fermé le lundi midi</em>
                        </p>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <h3>📞 Contact</h3>
                        <p>
                            Tél: 02 00 00 00 00<br>
                            Email: contact@pizzachateaulin.fr<br>
                            Site: www.pizzachateaulin.fr
                        </p>
                    </div>
                </div>
            </div>

            <div class="section" style="text-align: center;">
                <h2>📢 Rejoignez-nous sur les réseaux !</h2>
                <div style="display: flex; justify-content: center; gap: 15px; margin-top: 15px;">
                    <a href="#" style="display: inline-block;">
                        <img src="https://img.icons8.com/color/48/000000/facebook-new.png" alt="Facebook" style="width: 40px;">
                    </a>
                    <a href="#" style="display: inline-block;">
                        <img src="https://img.icons8.com/color/48/000000/instagram-new.png" alt="Instagram" style="width: 40px;">
                    </a>
                    <a href="#" style="display: inline-block;">
                        <img src="https://img.icons8.com/color/48/000000/tripadvisor.png" alt="TripAdvisor" style="width: 40px;">
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
