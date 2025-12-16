<?php
include '../config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Assure-toi que PHPMailer est installé via Composer

if (isset($_POST['assigner_collecteur'])) {
    $demande_id = $_POST['demande_id'];
    $collecteur_id = $_POST['collecteur_id'];

    // Mettre à jour la demande avec le collecteur assigné
    $stmt = $pdo->prepare("UPDATE demandes SET collecteur_id = ? WHERE id = ?");
    if ($stmt->execute([$collecteur_id, $demande_id])) {
        // Changer le statut du collecteur à "occupé"
        $stmt = $pdo->prepare("UPDATE collecteurs SET statut = 'occupé' WHERE id = ?");
        $stmt->execute([$collecteur_id]);

        header("Location: ../public/collectes_en_cours.php?success=1");
        exit();

    } else {
        header("Location: ../public/collectes_en_cours.php?error=1");
        exit();
    }


    // Récupérer l'email du collecteur
    $stmt = $pdo->prepare("SELECT email, nom FROM collecteurs WHERE id = ?");
    $stmt->execute([$collecteur_id]);
    $collecteur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les infos de la collecte
    $stmt = $pdo->prepare("SELECT localisation, type_dechet, date_heure FROM demandes WHERE id = ?");
    $stmt->execute([$demande_id]);
    $demande = $stmt->fetch(PDO::FETCH_ASSOC);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Remplace par ton SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'valamoussou14@gmail.com'; 
        $mail->Password = 'poqk hqjz uwso trxb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('valamoussou14@gmail.com', 'TogoEthic');
        $mail->addAddress($collecteur['email'], $collecteur['nom']);
        
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle collecte assignée';
        $mail->Body = "
            Bonjour <b>{$collecteur['nom']}</b>,<br><br>
            Vous avez été assigné à une nouvelle collecte.<br>
            <b>Localisation :</b> {$demande['localisation']}<br>
            <b>Type de déchet :</b> {$demande['type_dechet']}<br>
            <b>Date et Heure :</b> {$demande['date_heure']}<br><br>
            Merci de vous rendre sur votre page TogoEthic pour plus d’informations.<br>
            <a href='http://localhost/projets/gestion_de_dechets/public/auth.html'>Accéder à page TogoEthic</a>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur d'envoi de l'email : {$mail->ErrorInfo}");
    }

    // Mettre à jour les notifications du collecteur
    $notification = "Nouvelle collecte assignée : {$demande['localisation']} - {$demande['type_dechet']} à {$demande['date_heure']}";
    $stmt = $pdo->prepare("UPDATE collecteurs SET notifications = ? WHERE id = ?");
    $stmt->execute([$notification, $collecteur_id]);

}
?>
