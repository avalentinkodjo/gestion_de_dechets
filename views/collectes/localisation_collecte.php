<?php
require '../../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID invalide !");
}

$id = $_GET['id'];


// Récupérer les détails du travailleur
$stmt = $pdo->prepare("SELECT * FROM demandes WHERE id = ?");
$stmt->execute([$id]);
$demandes = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demandes) {
    die("Localisation introuvable !");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Localisation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/localisation.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHZyxMa_m_16KNjwhcQukCvXdSLdyPdBM&callback=initMap&v=weekly" async></script>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-map-marked-alt"></i> Localisation de la demande</h1>
        </header>

        <div id="map"></div>

        <div class="info-box">
            <h3><i class="fas fa-map-marker-alt"></i> Adresse</h3>
            <p><?php echo htmlspecialchars($demandes['localisation']); ?></p>
            <h3><i class="fas fa-trash-alt"></i> Type de Déchet</h3>
            <p><?php echo htmlspecialchars($demandes['type_dechet']); ?></p>
        </div>

        <a href="mes_collecte.php" class="btn"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>

    <script>
        function initMap() {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: { lat: <?php echo $demandes['latitude']; ?>, lng: <?php echo $demandes['longitude']; ?> }
            });

            new google.maps.Marker({
                position: { lat: <?php echo $demandes['latitude']; ?>, lng: <?php echo $demandes['longitude']; ?> },
                map: map,
                title: "Demande à <?php echo htmlspecialchars($demandes['localisation']); ?>",
            });
        }
    </script>
</body>
</html>
