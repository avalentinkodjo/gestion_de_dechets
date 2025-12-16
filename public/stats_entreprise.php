<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

// Nombre de clients desservis par mois
$stmt = $pdo->prepare("
    SELECT MONTH(date_heure) AS mois, YEAR(date_heure) AS annee, COUNT(DISTINCT id) AS total_clients 
    FROM demandes 
    WHERE user_id = ? 
    GROUP BY annee, mois 
    ORDER BY annee ASC, mois ASC
");

$stmt->execute([$_SESSION['id']]);
$total_clients = $stmt->fetchAll(PDO::FETCH_ASSOC); 

// Répartition par type de déchets collectés
$stmt = $pdo->prepare("
    SELECT type_dechet, COUNT(DISTINCT id) AS total 
    FROM demandes 
    WHERE user_id = ? 
    GROUP BY type_dechet
");
$stmt->execute([$_SESSION['id']]);
$stats_type = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclure la bibliothèque TCPDF
require_once('../tcpdf/tcpdf.php');

function moisAnneeFrancais($mois, $annee) {
    $mois_fr = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
        4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
        10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    return $mois_fr[$mois] . ' ' . $annee;
}

function exportPDF($data_clients, $data_dechets) {
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Gestion Déchets');
    $pdf->SetTitle('Rapport de Collecte');

    // Utilisation de DejaVuSans pour les emojis
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetHeaderData('', 0, '♻️ TogoEthic - Rapport Statistique', 'Généré le : ' . date('d/m/Y'));
    $pdf->setHeaderFont(['dejavusans', '', 10]);
    $pdf->setFooterFont(['dejavusans', '', 8]);
    $pdf->SetMargins(15, 30, 15);
    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 20);

    $pdf->AddPage();

    // Titre principal
    $pdf->SetFont('dejavusans', 'B', 18);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(0, 12, ' Statistiques de Collecte', 0, 1, 'C');

    // Sous-titre
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Rapport généré automatiquement pour votre entreprise.', 0, 1, 'C');

    // Section 1 : Clients desservis
    $pdf->Ln(12);
    $pdf->SetFont('dejavusans', 'B', 14);
    $pdf->SetTextColor(0, 102, 51);
    $pdf->Cell(0, 10, '👥 Clients desservis par mois', 0, 1);

    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    foreach ($data_clients as $client) {
        $mois = moisAnneeFrancais($client['mois'], $client['annee']);
        $pdf->Cell(0, 8, " $mois - Clients : " . $client['total_clients'], 0, 1);
    }

    // Section 2 : Types de déchets
    $pdf->Ln(10);
    $pdf->SetFont('dejavusans', 'B', 14);
    $pdf->SetTextColor(0, 102, 51);
    $pdf->Cell(0, 10, ' Répartition des types de déchets collectés', 0, 1);

    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    foreach ($data_dechets as $dechet) {
        $pdf->Cell(0, 8, "♻️ Type : " . $dechet['type_dechet'] . " - Total : " . $dechet['total'], 0, 1);
    }

    // Pied de page
    $pdf->Ln(10);
    $pdf->SetFont('dejavusans', 'I', 10);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 10, 'Document généré par la plateforme de TogoEthic - Tous droits réservés', 0, 1, 'C');

    $pdf->Output('Statistiques_Collecte.pdf', 'I');
}



// Récupérer les données (comme tu l'as déjà fait)
$data_clients = $total_clients;
$data_dechets = $stats_type;

// Appeler la fonction pour générer le PDF
if (isset($_GET['export_pdf'])) {
    exportPDF($data_clients, $data_dechets);
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de l'entreprise</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/statistique_entreprisess.css">
</head>
<body>
    <header class="header">
        <a href="dashboard_entreprise.php" class="btn-retour"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1><i class="fas fa-chart-line"></i> Tableau de bord des performances</h1>
    </header>
    <a href="?export_pdf=true" class="btn-export"><i class="fas fa-file-pdf"></i> Exporter les données en PDF</a>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users"></i> Nombre de clients desservis
            </div>
            <div class="card-body">
                <canvas id="collecteChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-recycle"></i> Répartition des types de déchets
            </div>
            <div class="card-body">
                <canvas id="typeDechetsChart"></canvas>
            </div>
        </div>
    </div>

    <script>

        // 📊 Graphique des collectes par mois
        var ctx1 = document.getElementById('collecteChart').getContext('2d');
        var collecteChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($total_clients as $s) { echo "'" . moisAnneeFrancais($s['mois'], $s['annee']) . "',"; } ?>],
                datasets: [{
                    label: 'Nombre de clients desservis',
                    data: [<?php foreach ($total_clients as $s) { echo $s['total_clients'] . ","; } ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // 📊 Graphique de répartition des types de déchets
        var ctx2 = document.getElementById('typeDechetsChart').getContext('2d');
        var typeDechetsChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($stats_type as $t) { echo "'" . $t['type_dechet'] . "',"; } ?>],
                datasets: [{
                    data: [<?php foreach ($stats_type as $t) { echo $t['total'] . ","; } ?>],
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4caf50', '#9966ff']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
