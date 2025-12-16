<?php
session_start();
include '../config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Assure-toi que PHPMailer est installé via Composer


if (!isset($_SESSION['id'])) {
    echo json_encode(["success" => false, "message" => "Utilisateur non authentifié"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $collecteur_id = intval($_POST['id']);

    try {
        // Récupérer les informations du collecteur depuis profil_collecteurs
        $stmt = $pdo->prepare("SELECT * FROM profil_collecteurs WHERE id = ?");
        $stmt->execute([$collecteur_id]);
        $collecteur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$collecteur) {
            echo json_encode(["success" => false, "message" => "Collecteur introuvable"]);
            exit;
        }

        // Vérifier si le collecteur est déjà recruté
        if ($collecteur['statut'] === 'déjà recruté') {
            echo json_encode(["success" => false, "message" => "Ce collecteur est déjà recruté."]);
            exit;
        }

        // Récupérer l'ID de l'entreprise connectée (modifie cette ligne selon ton système d'authentification)
        $entreprise_id = $_SESSION['id']; // Assure-toi que l'utilisateur est bien une entreprise

        // Insérer les données dans la table collecteurs
        $stmt = $pdo->prepare("INSERT INTO collecteurs (entreprise_id, nom, telephone, email, adresse, ville, photo, collecteur_id) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $entreprise_id,
            $collecteur['nom'],
            $collecteur['telephone'],
            $collecteur['email'],
            $collecteur['adresse'],
            $collecteur['ville'],
            $collecteur['photo'],
            $collecteur['user_id']
        ]);

        // Mettre à jour le statut dans profil_collecteurs
        $stmt = $pdo->prepare("UPDATE profil_collecteurs SET statut = 'déjà recruté' WHERE id = ?");
        $stmt->execute([$collecteur_id]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $e->getMessage()]);
    }

    // Récupérer l'email du collecteur
    $stmt = $pdo->prepare("SELECT email, nom FROM profil_collecteurs WHERE id = ?");
    $stmt->execute([$collecteur_id]);
    $collecteurs = $stmt->fetch(PDO::FETCH_ASSOC);


    //Recupération des infos du recrutement
    $sql = $pdo->prepare("SELECT nom, email FROM utilisateurs WHERE id = ?");
    $sql->execute([$_SESSION['id']]);
    $reslut = $sql->fetch(PDO::FETCH_ASSOC);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Remplace par ton SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'valamoussou14@gmail.com'; 
        $mail->Password = 'poqk hqjz uwso trxb';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('valamoussou14@gmail.com', 'TogoEthic');
        $mail->addAddress($collecteurs['email'], $collecteurs['nom']);
        
        $mail->isHTML(true);
        $mail->Subject = 'Notification de recrutement';
        $mail->Body = "
            Bonjour <b>{$collecteurs['nom']}</b>,<br><br>
            Vous avez été recruté par une entreprise de collecte.<br>
            <b>Nom de l'entreprise :</b> {$reslut['nom']}<br>
            <b>Email de l'entreprise :</b> {$reslut['email']}<br><br>
            Merci de vous rendre sur votre tableau de bord pour plus d’informations.<br>
            <a href='http://localhost/projets/gestion_de_dechets/public/login.php'>Accéder à votre tableau de bord</a>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur d'envoi de l'email : {$mail->ErrorInfo}");
    }

    // Mettre à jour les notifications du collecteur
    $notification = "Vous êtes recruté par l'entreprise : {$reslut['nom']} - {$reslut['email']}";
    $stmt = $pdo->prepare("UPDATE collecteurs SET notifications = ? WHERE email = ?");
    $stmt->execute([$notification, $collecteurs['email']]);
    


} else {
    echo json_encode(["success" => false, "message" => "Requête invalide"]);
}
?>
