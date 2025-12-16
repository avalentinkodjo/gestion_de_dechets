<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: auth.html");
    exit();
}

include '../config/database.php';
$stmt = $pdo->prepare("SELECT * FROM demandes WHERE statut = 'en attente'");
$stmt->execute();
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collecte en attente</title>
    <link rel="stylesheet" href="css/Collecteenattente.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <header>
        <nav class="navbar">
            <h1>Gestion des Déchets - Togo</h1>
            <div>
                <a href="dashboard_entreprise.php" class="btn">Accueil</a>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="container">
            <h2>Accepter une demande de collecte</h2>
            <p>Faites un geste pour l'environnement. Demandez une collecte de déchets à domicile !</p>
        </div>
    </section>

    <div class="card">
        <h3> 📋 Demandes de collecte en attente</h3>
        <div id="cd">
            <?php if (count($demandes) > 0): ?>
                <?php foreach ($demandes as $demande): ?>
                    <div class="demande">
                        <p id="lo"><strong>📍 Localisation :</strong> <?= htmlspecialchars($demande['localisation']) ?></p>
                        <button id="loc">
                            <a href="localisation.php?id=<?= $demande['id'] ?>">
                                <i class="fa-solid fa-location-dot fa-bounce" style="color: #21e21d;"></i> Voir
                            </a>
                        </button>
                        <p><strong>📌 Type de déchet :</strong> <?= ucfirst($demande['type_dechet']) ?></p>
                        <p><strong>📅 Date et heure :</strong> <?= $demande['date_heure'] ?></p>
                        <img src="../uploads/<?= !empty($demande['photo']) ? htmlspecialchars($demande['photo']) : 'déchet_default.jpg' ?>" alt="Photo du déchet" width="100">

                        <!-- Formulaire avec classe AJAX -->
                        <form class="form-accept" method="post" autocomplete="off">
                            <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                            <button type="submit" name="accepter">✅ Accepter la collecte</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune demande récente.</p>
            <?php endif; ?>
        </div>  
    </div>

    <!-- Script AJAX -->
    <script>
    $(document).ready(function () {
        $(".form-accept").on("submit", function (e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();

            $.ajax({
                type: "POST",
                url: "../controllers/AcceptDemandeController.php",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Collecte acceptée',
                            text: response.success,
                        }).then(() => {
                            location.reload(); // Recharge la page après confirmation
                        });
                    } else if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.error,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur serveur',
                        text: 'Une erreur est survenue. Veuillez réessayer.',
                    });
                }
            });
        });
    });
    </script>

</body>
</html>
