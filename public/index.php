<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Gestion des Déchets</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <?php if (isset($_SESSION['id'])): ?>
                    <li><a href="dashboard.php">Tableau de bord</a></li>
                    <li><a href="logout.php">Se déconnecter</a></li>
                <?php else: ?>
                    <li><a href="login.php">Se connecter</a></li>
                    <li><a href="register.php">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Bienvenue sur la plateforme de gestion des déchets</h1>
            <p>Un environnement plus propre commence avec nous !</p>
        </section>

        <?php if (isset($_SESSION['id'])): ?>
            <?php 
                // Afficher un message personnalisé en fonction du rôle de l'utilisateur
                $role = $_SESSION['role'];
                if ($role == 'citoyen') {
                    echo "<section class='welcome-message'><h2>Bienvenue, citoyen !</h2><p>Vous pouvez demander une collecte de déchets et suivre vos demandes.</p></section>";
                } elseif ($role == 'collecteur') {
                    echo "<section class='welcome-message'><h2>Bienvenue, collecteur !</h2><p>Consultez les demandes de collecte et effectuez vos missions.</p></section>";
                } elseif ($role == 'entreprise') {
                    echo "<section class='welcome-message'><h2>Bienvenue, entreprise !</h2><p>Gérez vos collecteurs et suivez l'état des collectes.</p></section>";
                } elseif ($role == 'gestionnaire') {
                    echo "<section class='welcome-message'><h2>Bienvenue, gestionnaire !</h2><p>Gérez les utilisateurs et coordonnez les collectes.</p></section>";
                }
            ?>
        <?php else: ?>
            <section class="intro">
                <h2>Rejoignez-nous dès aujourd'hui !</h2>
                <p>Créez un compte pour commencer à demander des collectes, devenir collecteur ou gérer les collectes d'une entreprise.</p>
            </section>
        <?php endif; ?>

        <section class="services">
            <h2>Nos services</h2>
            <div class="service-card">
                <h3>Pour les citoyens</h3>
                <p>Faites une demande de collecte de déchets et suivez l'état de votre demande.</p>
            </div>
            <div class="service-card">
                <h3>Pour les collecteurs</h3>
                <p>Répondez aux demandes de collecte et effectuez vos missions efficacement.</p>
            </div>
            <div class="service-card">
                <h3>Pour les entreprises</h3>
                <p>Gérez vos collecteurs et consultez les avis des citoyens sur leurs services.</p>
            </div>
        </section>

        <section class="contact">
            <h2>Contactez-nous</h2>
            <p>Si vous avez des questions ou des suggestions, contactez-nous à <strong>contact@gestiondechets.com</strong>.</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Plateforme de Gestion des Déchets - Tous droits réservés.</p>
    </footer>
</body>
</html>
