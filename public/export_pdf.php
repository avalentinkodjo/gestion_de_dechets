<?php
// Inclure la bibliothèque TCPDF
require_once('../tcpdf/tcpdf.php');

// Fonction pour exporter en PDF avec les images des graphiques
function exportPDFWithGraphs($img1, $img2) {
    // Créer un objet TCPDF
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Titre du PDF
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Statistiques de Collecte des Dechets', 0, 1, 'C');

    // Ajouter le premier graphique (Collectes par mois)
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Nombre de clients desservis par mois:', 0, 1);

    // Convertir l'image base64 en fichier image
    $img1_path = 'temp_graph1.png';
    file_put_contents($img1_path, base64_decode(explode(",", $img1)[1]));

    // Ajouter l'image au PDF
    $pdf->Image($img1_path, 15, 60, 180); // Modifier la taille et la position selon ton besoin

    // Ajouter le deuxième graphique (Répartition des types de déchets)
    $pdf->Ln(100); // Espacement après le premier graphique
    $pdf->Cell(0, 10, 'Répartition des types de déchets:', 0, 1);

    // Convertir l'image base64 en fichier image
    $img2_path = 'temp_graph2.png';
    file_put_contents($img2_path, base64_decode(explode(",", $img2)[1]));

    // Ajouter l'image au PDF
    $pdf->Image($img2_path, 15, 120, 180); // Modifier la taille et la position selon ton besoin

    // Sortir le PDF (nom du fichier)
    $pdf->Output('Statistiques_Collecte_avec_Graphiques.pdf', 'I');

    // Supprimer les fichiers temporaires
    unlink($img1_path);
    unlink($img2_path);
}

// Récupérer les images envoyées depuis JavaScript
if (isset($_POST['img1']) && isset($_POST['img2'])) {
    exportPDFWithGraphs($_POST['img1'], $_POST['img2']);
    exit();
}
?>
