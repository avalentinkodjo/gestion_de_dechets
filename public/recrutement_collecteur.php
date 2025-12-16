<?php
session_start();
include '../config/database.php';

// Initialisation des filtres
$condition = [];
$params = [];

// Vérifier les filtres soumis par le formulaire
if (!empty($_GET['ville'])) {
    $condition[] = "ville LIKE ?";
    $params[] = "%" . $_GET['ville'] . "%";
}

if (!empty($_GET['adresse'])) {
    $condition[] = "adresse LIKE ?";
    $params[] = "%" . $_GET['adresse'] . "%";
}

// Construire la requête SQL
$sql = "SELECT * FROM profil_collecteurs WHERE statut='pas recruté' ";
if (!empty($condition)) {
    $sql .= " AND " . implode(" AND ", $condition);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recrutement des Collecteurs</title>
    <link rel="stylesheet" href="css/recrutementsss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="back-button">
                <a href="dashboard_entreprise.php"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>

            <h1><i class="fas fa-recycle"></i> Plateforme TogoEthic</h1>
            <p>🌱 Recrutement des collecteurs de déchets en attente</p>
        </header>

        <!-- Formulaire de recherche -->
        <form method="GET" class="search-form" autocomplete="off">
            <input type="text" name="ville" placeholder="🌍 Ville">
            <input type="text" name="adresse" placeholder="🏠 Adresse">
            <button type="submit" class="btn-search"><i class="fas fa-search"></i> Filtrer</button>
        </form>

        <section class="collecteurs-list">
            <?php foreach ($results as $result): ?>
                <div class="collecteur-card">
                    <img src="../uploads/<?= !empty($result['photo']) ? htmlspecialchars($result['photo']) : 'default.png' ?>" alt="Photo de profil">
                    <div class="info">
                        <h3><i class="fas fa-user"></i> <?= htmlspecialchars($result['nom']) ?></h3>
                        <p><i class="fas fa-phone-alt"></i> <?= htmlspecialchars($result['telephone']) ?></p>
                        <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($result['email']) ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($result['adresse']) ?> - <?= htmlspecialchars($result['ville']) ?></p>
                    </div>
                    <button class="btn-contact" data-id="<?= $result['id'] ?>"><i class="fas fa-handshake"></i> Recruter</button>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".btn-contact").click(function() {
            let collecteurId = $(this).data("id");

            $.ajax({
                url: "recruter_collecteur.php",
                type: "POST",
                data: { id: collecteurId },
                success: function(response) {
                    try {
                        let res = JSON.parse(response);
                        if (res.success) {
                            alert("✅ Collecteur recruté avec succès !");
                            location.reload();
                        } else {
                            alert("❌ Erreur : " + res.message);
                        }
                    } catch (e) {
                        alert("❌ Erreur inattendue lors du traitement de la réponse.");
                    }
                },
                error: function() {
                    alert("❌ Une erreur s'est produite lors de la requête AJAX.");
                }
            });
        });
    });
    </script>
</body>
</html>
